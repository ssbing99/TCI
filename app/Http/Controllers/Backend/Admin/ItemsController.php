<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreItemsRequest;
use App\Http\Requests\Admin\UpdateItemsRequest;
use App\Models\Item;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class ItemsController extends Controller
{

    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('item_access')) {
            return abort(401);
        }

        return view('backend.items.index');
    }

    /**
     * Display a listing of Items via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $items = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('item_delete')) {
                return abort(401);
            }
            $items = Item::query()->onlyTrashed()
                ->ofTeacher()->orderBy('created_at', 'desc');
        } else {
            $items = Item::query()
                ->orderBy('created_at', 'desc');
        }


        if (auth()->user()->can('item_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('item_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('item_delete')) {
            $has_delete = true;
        }

        return DataTables::of($items)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.items', 'label' => 'id', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.items.show', ['item' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.items.edit', ['item' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.items.destroy', ['item' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                if ($q->published == 1) {
                    $type = 'action-unpublish';
                } else {
                    $type = 'action-publish';
                }

                $view .= view('backend.datatable.'.$type)
                    ->with(['route' => route('admin.items.publish', ['id' => $q->id])])->render();
                return $view;
            })
            ->editColumn('item_image', function ($q) {
                return ($q->item_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->item_image) . '">' : 'N/A';
            })
            ->addColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-dark p-1 mr-1' >" . trans('labels.backend.courses.fields.published') . "</p>" : "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.unpublished') . "</p>";
//                if (auth()->user()->isAdmin()) {
//                    $text .= ($q->featured == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-warning p-1 mr-1' >" . trans('labels.backend.courses.fields.featured') . "</p>" : "";
//                    $text .= ($q->trending == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-success p-1 mr-1' >" . trans('labels.backend.courses.fields.trending') . "</p>" : "";
//                    $text .= ($q->popular == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.popular') . "</p>" : "";
//                }

                return $text;
            })
            ->editColumn('price', function ($q) {
                if ($q->price <= 0) {
                    return trans('labels.backend.courses.fields.free');
                }
                return $q->price;
            })
            ->editColumn('discount', function ($q) {
                return $q->discount;
            })
            ->editColumn('stock_count', function ($q) {
                return $q->stock_count;
            })
            ->rawColumns(['item_image', 'actions', 'status'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('item_create')) {
            return abort(401);
        }
        $admin = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 1);
        })->get()->pluck('name', 'id');

        return view('backend.items.create', compact('admin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemsRequest $request)
    {
        if (!Gate::allows('item_create')) {
            return abort(401);
        }

        $request->all();
        $request = $this->saveFiles($request);

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Item::where('slug', '=', $slug)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }


        $item = Item::create($request->all());
        $item->slug = $slug;
        $item->save();

        if ((int)$request->price == 0) {
            $item->price = null;
            $item->save();
        }


        return redirect()->route('admin.items.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('item_view')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);

        return view('backend.items.items-show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('item_edit')) {
            return abort(401);
        }

        $item = Item::findOrFail($id);

        return view('backend.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemsRequest $request, $id)
    {
        if (!Gate::allows('item_edit')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Item::where('slug', '=', $slug)->where('id', '!=', $item->id)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }



        $request = $this->saveFiles($request);

        $item->update($request->all());
        if (($request->slug == "") || $request->slug == null) {
            $item->slug = str_slug($request->title);
            $item->save();
        }
        if ((int)$request->price == 0) {
            $item->price = null;
            $item->save();
        }

        return redirect()->route('admin.items.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.items.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Items at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('item_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Item::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    /**
     * Restore Item from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('admin.items.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Item from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('item_delete')) {
            return abort(401);
        }
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return redirect()->route('admin.items.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Publish / Unpublish items
     *
     * @param  Request
     */
    public function publish($id)
    {
        if (!Gate::allows('item_edit')) {
            return abort(401);
        }

        $item = Item::findOrFail($id);
        if ($item->published == 1) {
            $item->published = 0;
        } else {
            $item->published = 1;
        }
        $item->save();

        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }
}
