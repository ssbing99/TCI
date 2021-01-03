<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAssignmentsRequest;
use App\Http\Requests\Admin\UpdateAssignmentsRequest;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class AssignmentsController extends Controller
{

    use FileUploadTrait;

    /**
     * Display a listing of Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('lesson_access')) {
            return abort(401);
        }
        $courses = $courses = Course::has('category')->ofTeacher()->pluck('title', 'id')->prepend('Please select', '');

        return view('backend.assignments.index', compact('courses'));
    }

    /**
     * Display a listing of Lessons via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $lessons = "";
        $lessons = Lesson::query()->where('live_lesson', '=', 0)->whereIn('course_id', Course::ofTeacher()->pluck('id'));
        $assignment = Assignment::query();


        if ($request->lesson_id != "") {
            $assignment = $assignment->where('lesson_id', (int)$request->lesson_id)->orderBy('created_at', 'desc');
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $assignment = $assignment->where('lesson_id', (int)$request->lesson_id)->orderBy('created_at', 'desc')->onlyTrashed();
        }


        if (auth()->user()->can('lesson_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('lesson_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($assignment)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.assignments', 'label' => 'id', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.assignments.show', ['assignment' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.assignments.edit', ['assignment' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.assignments.destroy', ['assignment' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                if (auth()->user()->can('test_view')) {
                    if ($q->test != "") {
                        $view .= '<a href="' . route('admin.tests.index', ['lesson_id' => $q->id]) . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.tests.title') . '</a>';
                    }
                }

                return $view;
            })
            ->editColumn('course', function ($q) {
                return ($q->course) ? $q->course->title : 'N/A';
            })
            ->editColumn('lesson_image', function ($q) {
                return ($q->lesson_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->lesson_image) . '">' : 'N/A';
            })
            ->editColumn('free_lesson', function ($q) {
                return ($q->free_lesson == 1) ? "Yes" : "No";
            })
            ->editColumn('published', function ($q) {
                return ($q->published == 1) ? "Yes" : "No";
            })
            ->rawColumns(['lesson_image', 'actions'])
            ->make();
    }

    /**
     * Show the form for creating new Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');

        return view('backend.assignments.create', compact('courses'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param  \App\Http\Requests\StoreAssignmentsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssignmentsRequest $request)
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_assignment = Assignment::where('slug', '=', $slug)->first();
        if ($slug_assignment != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $assignment = Assignment::create($request->except('downloadable_files', 'assignment_image')
            + ['position' => Assignment::where('lesson_id', $request->lesson_id)->max('position') + 1]);

        $assignment->slug = $slug;
        $assignment->save();




        //Saving  videos
//        if ($request->media_type != "") {
//            $model_type = Assignment::class;
//            $model_id = $assignment->id;
//            $size = 0;
//            $media = '';
//            $url = '';
//            $video_id = '';
//            $name = $assignment->title . ' - video';
//
//            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
//                $video = $request->video;
//                $url = $video;
//                $video_id = array_last(explode('/', $request->video));
//                $media = Media::where('url', $video_id)
//                    ->where('type', '=', $request->media_type)
//                    ->where('model_type', '=', 'App\Models\Assignment')
//                    ->where('model_id', '=', $assignment->id)
//                    ->first();
//                $size = 0;
//            } elseif ($request->media_type == 'upload') {
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
//                        ->where('model_type', '=', 'App\Models\Assignment')
//                        ->where('model_id', '=', $assignment->id)
//                        ->first();
//                }
//            } elseif ($request->media_type == 'embed') {
//                $url = $request->video;
//                $filename = $assignment->title . ' - video';
//            }
//
//            if ($media == null) {
//                $media = new Media();
//                $media->model_type = $model_type;
//                $media->model_id = $model_id;
//                $media->name = $name;
//                $media->url = $url;
//                $media->type = $request->media_type;
//                $media->file_name = $video_id;
//                $media->size = 0;
//                $media->save();
//            }
//        }

//        $request = $this->saveAllFiles($request, 'downloadable_files', Assignment::class, $assignment);

        if (($request->slug == "") || $request->slug == null) {
            $assignment->slug = str_slug($request->title);
            $assignment->save();
        }

//        $sequence = 1;
//        if (count($assignment->course->courseTimeline) > 0) {
//            $sequence = $assignment->course->courseTimeline->max('sequence');
//            $sequence = $sequence + 1;
//        }

        return redirect()->route('admin.assignments.index', ['lesson_id' => $request->lesson_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Assignment.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }
        $videos = '';

        $assignment = Assignment::with('media')->findOrFail($id);
        if ($assignment->media) {
            $videos = $assignment->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
        }

        return view('backend.assignments.edit', compact('assignment', 'videos'));
    }

    /**
     * Update Assignment in storage.
     *
     * @param  \App\Http\Requests\UpdateAssignmentsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignmentsRequest $request, $id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }

        $slug = "";
        if (($request->slug == "") || $request->slug == null) {
            $slug = str_slug($request->title);
        } elseif ($request->slug != null) {
            $slug = $request->slug;
        }

        $slug_assignment = Assignment::where('slug', '=', $slug)->where('id', '!=', $id)->first();
        if ($slug_assignment != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $assignment = Assignment::findOrFail($id);
        $assignment->update($request->except('downloadable_files', 'assignment_image'));
        $assignment->slug = $slug;
        $assignment->save();

        //Saving  videos
//        if ($request->media_type != "") {
//            $model_type = Assignment::class;
//            $model_id = $assignment->id;
//            $size = 0;
//            $media = '';
//            $url = '';
//            $video_id = '';
//            $name = $assignment->title . ' - video';
//            $media = $assignment->mediavideo;
//            if ($media == "") {
//                $media = new  Media();
//            }
//            if ($request->media_type != 'upload') {
//                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
//                    $video = $request->video;
//                    $url = $video;
//                    $video_id = array_last(explode('/', $request->video));
//                    $size = 0;
//                } elseif ($request->media_type == 'embed') {
//                    $url = $request->video;
//                    $filename = $assignment->title . ' - video';
//                }
//                $media->model_type = $model_type;
//                $media->model_id = $model_id;
//                $media->name = $name;
//                $media->url = $url;
//                $media->type = $request->media_type;
//                $media->file_name = $video_id;
//                $media->size = 0;
//                $media->save();
//            }
//
//            if ($request->media_type == 'upload') {
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
//                        ->where('model_type', '=', 'App\Models\Assignment')
//                        ->where('model_id', '=', $assignment->id)
//                        ->first();
//
//                    if ($media == null) {
//                        $media = new Media();
//                    }
//                    $media->model_type = $model_type;
//                    $media->model_id = $model_id;
//                    $media->name = $name;
//                    $media->url = $url;
//                    $media->type = $request->media_type;
//                    $media->file_name = $video_id;
//                    $media->size = 0;
//                    $media->save();
//                }
//            }
//        }
//        if ($request->hasFile('add_pdf')) {
//            $pdf = $assignment->mediaPDF;
//            if ($pdf) {
//                $pdf->delete();
//            }
//        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Assignment::class, $assignment);

//        $sequence = 1;
//        if (count($assignment->course->courseTimeline) > 0) {
//            $sequence = $assignment->course->courseTimeline->max('sequence');
//            $sequence = $sequence + 1;
//        }

//        if ((int)$request->published == 1) {
//            $timeline = CourseTimeline::where('model_type', '=', Assignment::class)
//                ->where('model_id', '=', $assignment->id)
//                ->where('course_id', $request->course_id)->first();
//            if ($timeline == null) {
//                $timeline = new CourseTimeline();
//            }
//            $timeline->course_id = $request->course_id;
//            $timeline->model_id = $assignment->id;
//            $timeline->model_type = Assignment::class;
//            $timeline->sequence = $sequence;
//            $timeline->save();
//        }


        return redirect()->route('admin.assignments.index', ['lesson_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
    }


    /**
     * Display Assignment.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $assignment = Assignment::findOrFail($id);


        return view('backend.assignments.show', compact('assignment'));
    }


    /**
     * Remove Assignment from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $assignment = Assignment::findOrFail($id);
//        $assignment->chapterStudents()->where('course_id', $assignment->course_id)->forceDelete();
        $assignment->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Assignment at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Assignment::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Assignment from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $assignment = Assignment::onlyTrashed()->findOrFail($id);
        $assignment->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Assignment from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $assignment = Assignment::onlyTrashed()->findOrFail($id);

        if (File::exists(public_path('/storage/uploads/'.$assignment->assignment_image))) {
            File::delete(public_path('/storage/uploads/'.$assignment->assignment_image));
            File::delete(public_path('/storage/uploads/thumb/'.$assignment->assignment_image));
        }
//
//        $timelineStep = CourseTimeline::where('model_id', '=', $id)
//            ->where('course_id', '=', $assignment->course->id)->first();
//        if ($timelineStep) {
//            $timelineStep->delete();
//        }

        $assignment->forceDelete();



        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
