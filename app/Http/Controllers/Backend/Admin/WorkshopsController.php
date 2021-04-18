<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Media;
use App\Models\Workshop;
use function foo\func;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class WorkshopsController extends Controller
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
        return view('backend.workshops.index');
    }

    /**
     * Display a listing of Workshops via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $workshops = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('workshop_delete')) {
                return abort(401);
            }
            $workshops = Workshop::query()->onlyTrashed()
//                ->whereHas('category')
                ->ofTeacher()->orderBy('created_at', 'desc');
        } elseif (request('teacher_id') != "") {
            $id = request('teacher_id');
            $workshops = Workshop::query()->ofTeacher()
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('workshop_user.user_id', '=', $id);
                })->orderBy('created_at', 'desc');
        } else {
            $workshops = Workshop::query()->ofTeacher()
                ->orderBy('created_at', 'desc');
        }


        if (auth()->user()->can('workshop_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('workshop_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($workshops->get())
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.workshops', 'label' => 'id', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.workshops.show', ['workshop' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.workshops.edit', ['workshop' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.workshops.destroy', ['workshop' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                if ($q->published == 1) {
                    $type = 'action-unpublish';
                } else {
                    $type = 'action-publish';
                }

                $view .= view('backend.datatable.'.$type)
                    ->with(['route' => route('admin.workshops.publish', ['id' => $q->id])])->render();
                return $view;
            })
            ->addColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    if($teachers!="")
                        $teachers.=', ';
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
//            ->editColumn('workshop_image', function ($q) {
//                return ($q->workshop_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->workshop_image) . '">' : 'N/A';
//            })
            ->addColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-dark p-1 mr-1' >" . trans('labels.backend.workshops.fields.published') . "</p>" : "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.workshops.fields.unpublished') . "</p>";

                return $text;
            })
            ->editColumn('price', function ($q) {
                if ($q->free == 1) {
                    return trans('labels.backend.workshops.fields.free');
                }
                return $q->price;
            })
            ->rawColumns(['teachers', 'workshop_image', 'actions', 'status'])
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
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'id');

        return view('backend.workshops.create', compact('teachers'));
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
            'teachers.*' => 'exists:users,id',
            'title' => 'required',
        ]);


        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Workshop::where('slug', '=', $slug)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }


        $workshop = Workshop::create($request->all());
        $workshop->slug = $slug;
        $workshop->save();

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Workshop::class;
            $model_id = $workshop->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $workshop->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = array_last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Workshop')
                    ->where('model_id', '=', $workshop->id)
                    ->first();
                $size = 0;
            } elseif ($request->media_type == 'upload') {
//                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
//                    $file = \Illuminate\Support\Facades\Request::file('video_file');
//                    $filename = time() . '-' . $file->getClientOriginalName();
//                    $size = $file->getSize() / 1024;
//                    $path = public_path() . '/storage/uploads/';
//                    $file->move($path, $filename);
//
//                    $video_id = $filename;
//                    $url = asset('storage/uploads/' . $filename);
//
//                    $media = Media::where('type', '=', $request->media_type)
//                        ->where('model_type', '=', 'App\Models\Workshop')
//                        ->where('model_id', '=', $workshop->id)
//                        ->first();
//
//                }
                $video_id = $request->video_file;
                $url = asset('storage/uploads/' . $video_id);
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Workshop')
                    ->where('model_id', '=', $workshop->id)
                    ->first();

                if ($request->video_subtitle && $request->video_subtitle != null) {
                    $subtitle_id = $request->video_subtitle;
                    $subtitle_url = asset('storage/uploads/' . $subtitle_id);

                    $subtitle = Media::where('url', $subtitle_id)
                        ->where('type', '=', 'subtitle')
                        ->where('model_type', '=', 'App\Models\Workshop')
                        ->where('model_id', '=', $workshop->id)
                        ->first();
                    if ($subtitle == null) {
                        $subtitle = new Media();
                        $subtitle->model_type = $model_type;
                        $subtitle->model_id = $model_id;
                        $subtitle->name = $name.' - subtitle';
                        $subtitle->url = $subtitle_url;
                        $subtitle->type = 'subtitle';
                        $subtitle->file_name = $subtitle_id;
                        $subtitle->size = 0;
                        $subtitle->save();
                    }
                }
            } elseif ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $workshop->title . ' - video';
            }

            if ($media == null) {
                $media = new Media();
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }
        }

        $request = $this->saveAllFiles($request, 'workshop_image', Workshop::class, $workshop);

        if($request->images != null){
            $workshop->images = $request->images;
            $workshop->save();
        }

        if ((int)$request->price == 0) {
            $workshop->price = null;
            $workshop->save();
        }


        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $workshop->teachers()->sync($teachers);


        return redirect()->route('admin.workshops.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('workshop_view')) {
            return abort(401);
        }
        $teachers = User::get()->pluck('name', 'id');

        $workshop = Workshop::findOrFail($id);

        return view('backend.workshops.show', compact('workshop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('workshop_edit')) {
            return abort(401);
        }
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'id');


        $workshop = Workshop::findOrFail($id);

        return view('backend.workshops.edit', compact('workshop', 'teachers'));
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
        if (!Gate::allows('workshop_edit')) {
            return abort(401);
        }
        $workshop = Workshop::findOrFail($id);

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_lesson = Workshop::where('slug', '=', $slug)->where('id', '!=', $workshop->id)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        //Saving  videos
        if ($request->media_type != "" || $request->media_type  != null) {
            if ($workshop->mediavideo) {
                $workshop->mediavideo->delete();
            }
            $model_type = Workshop::class;
            $model_id = $workshop->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $workshop->title . ' - video';
            $media = $workshop->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = array_last(explode('/', $request->video));
                    $size = 0;
                } elseif ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $workshop->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {
                if ($request->video_file != null) {
                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Workshop')
                        ->where('model_id', '=', $workshop->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = url('storage/uploads/'.$request->video_file);
                    $media->type = $request->media_type;
                    $media->file_name = $request->video_file;
                    $media->size = 0;
                    $media->save();
                }
            }
        }


        $workshop->update($request->all());
        if (($request->slug == "") || $request->slug == null) {
            $workshop->slug = str_slug($request->title);
            $workshop->save();
        }

        if(request('delete') != null && request('delete') != ""){
            \Log::info(request('delete'));
            \Log::info($workshop->image);

            $deleted = '';
            foreach ($workshop->image as $img){
                $remain = true;
                foreach (request('delete') as $item) {
                    if($img == $item)
                        $remain = false;
                }
                if($remain) {
                    $deleted .= ($deleted == ''? '': ',').$img;
                }
            }
            $workshop->images = ($deleted == ''? NULL : $deleted);
            $workshop->save();
        }

        $request = $this->saveAllFiles($request, 'workshop_image', Workshop::class, $workshop);

        if($request->images != null){
            if($workshop->images != null && $workshop->images != '')
                $workshop->images .= ','.$request->images;
            else
                $workshop->images = $request->images;
            $workshop->save();
        }

        if ((int)$request->price == 0) {
            $workshop->price = null;
            $workshop->save();
        }

        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $workshop->teachers()->sync($teachers);

        return redirect()->route('admin.workshops.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('workshop_delete')) {
            return abort(401);
        }
        $workshop = Workshop::findOrFail($id);
        if ($workshop->students->count() >= 1) {
            return redirect()->route('admin.workshops.index')->withFlashDanger(trans('alerts.backend.general.delete_warning'));
        } else {
            $workshop->delete();
        }


        return redirect()->route('admin.workshops.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Workshop at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('workshop_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Workshop::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Workshop from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('workshop_delete')) {
            return abort(401);
        }
        $workshop = Workshop::onlyTrashed()->findOrFail($id);
        $workshop->restore();

        return redirect()->route('admin.workshops.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Workshop from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('workshop_delete')) {
            return abort(401);
        }
        $workshop = Workshop::onlyTrashed()->findOrFail($id);
        $workshop->forceDelete();

        return redirect()->route('admin.workshops.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Publish / Unpublish workshops
     *
     * @param  Request
     */
    public function publish($id)
    {
        if (!Gate::allows('workshop_edit')) {
            return abort(401);
        }

        $workshop = Workshop::findOrFail($id);
        if ($workshop->published == 1) {
            $workshop->published = 0;
        } else {
            $workshop->published = 1;
        }
        $workshop->save();

        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

}
