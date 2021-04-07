<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Course;
use App\Models\Gift;
use App\Models\Media;
use App\Models\Workshop;
use function foo\func;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class GiftsController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('backend.gifts.index');
    }

    /**
     * Display a listing of Gifts via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $gifts = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('gift_delete')) {
                return abort(401);
            }
            $gifts = Gift::query()->onlyTrashed()
//                ->whereHas('category')
                ->orderBy('created_at', 'desc');
        } else {
            $gifts = Gift::query()
                ->orderBy('created_at', 'desc');
        }


        if (auth()->user()->can('gift_view')) {
//            $has_view = true;
        }
        if (auth()->user()->can('gift_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($gifts->get())
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.gifts', 'label' => 'id', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.gifts.show', ['gift' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.gifts.edit', ['gift' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.gifts.destroy', ['gift' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                if ($q->published == 1) {
                    $type = 'action-unpublish';
                } else {
                    $type = 'action-publish';
                }

//                $view .= view('backend.datatable.'.$type)
//                    ->with(['route' => route('admin.gifts.publish', ['id' => $q->id])])->render();
                return $view;
            })
            ->addColumn('lessons', function ($q) {

                return $q->lesson_amount;
            })
            ->editColumn('price', function ($q) {
                if ($q->free == 1) {
                    return trans('labels.backend.gifts.fields.free');
                }
                return $q->price;
            })
            ->rawColumns([ 'actions'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = array('0' => 'Course', '1' => 'Review', '2' => 'One-to-One Mentorship');

        $courses = Course::where('published', '=', 1)
            ->where('portfolio_review', '=', 1)->pluck('title', 'id')->prepend('none','0')->toArray();


        return view('backend.gifts.create', compact('courses','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required',
        ]);
        \Log::info($request->all());
        $gift = Gift::create($request->except('course_id'));

        if($request->input('category_id')){
            if($request->get('category_id') == '1'){
                $gift->course_id = $request->get('course_id');
                $gift->portfolio_review = 1;
                $gift->save();
            }
            if($request->get('category_id') == '2'){
                $gift->mentorship = 1;
                $gift->save();
            }
        }

        if ((int)$request->price == 0) {
            $gift->price = null;
            $gift->save();
        }


        return redirect()->route('admin.gifts.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('gift_view')) {
            return abort(401);
        }
        $gift = Gift::findOrFail($id);

        return view('backend.gifts.show', compact('gift'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('gift_edit')) {
            return abort(401);
        }

        $categories = array('0' => 'Course', '1' => 'Review', '2' => 'One-to-One Mentorship');

        $courses = Course::where('published', '=', 1)
            ->where('portfolio_review', '=', 1)->pluck('title', 'id')->prepend('none','0')->toArray();

        $gift = Gift::findOrFail($id);

        return view('backend.gifts.edit', compact('gift', 'categories','courses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('gift_edit')) {
            return abort(401);
        }
        $gift = Gift::findOrFail($id);

        \Log::info($request->all());
        $gift->update($request->except('course_id'));

        if($request->input('category_id')){
            if($request->get('category_id') == '1'){
                $gift->course_id = $request->get('course_id');
                $gift->portfolio_review = 1;
                $gift->save();
            }else{
                $gift->course_id = NULL;
                $gift->portfolio_review = 0;
                $gift->save();
            }
            if($request->get('category_id') == '2'){
                $gift->mentorship = 1;
                $gift->save();
            }else{
                $gift->mentorship = 0;
                $gift->save();
            }
        }

        if ((int)$request->price == 0) {
            $gift->price = null;
            $gift->save();
        }

        return redirect()->route('admin.gifts.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('gift_delete')) {
            return abort(401);
        }
        $gift = Gift::findOrFail($id);
        $gift->delete();


        return redirect()->route('admin.gifts.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Gift at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('gift_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Gift::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Gift from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('gift_delete')) {
            return abort(401);
        }
        $gift = Gift::onlyTrashed()->findOrFail($id);
        $gift->restore();

        return redirect()->route('admin.gifts.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Gift from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('gift_delete')) {
            return abort(401);
        }
        $gift = Gift::onlyTrashed()->findOrFail($id);
        $gift->forceDelete();

        return redirect()->route('admin.gifts.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Publish / Unpublish gifts
     *
     * @param  Request
     */
    public function publish($id)
    {
        if (!Gate::allows('gift_edit')) {
            return abort(401);
        }

        $gift = Gift::findOrFail($id);
        if ($gift->published == 1) {
            $gift->published = 0;
        } else {
            $gift->published = 1;
        }
        $gift->save();

        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

}
