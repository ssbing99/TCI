<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAssignmentsRequest;
use App\Http\Requests\Admin\StoreAttachmentsRequest;
use App\Http\Requests\Admin\UpdateAssignmentsRequest;
use App\Models\Assignment;
use App\Models\AssignmentAttachment;
use App\Models\AssignmentAttachmentGroup;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\FileUploadTrait;
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
                        ->with(['route' => route('admin.assignments.show', ['assignment' => $q->id, 'lesson_id' => $request->lesson_id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.assignments.edit', ['assignment' => $q->id, 'lesson_id' => $request->lesson_id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.assignments.destroy', ['assignment' => $q->id, 'lesson_id' => $request->lesson_id])])
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
            ->addColumn('sequence', function ($q) {

                if ($q->rearrangement == 1 && !is_null($q->rearrangement_type) && $q->rearrangement_type == 'admin') {
                    $sequence = '<a href="' . route('admin.assignments.rearrangement.list', ['assignment_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-arrow-circle-right"></a>';
                    return $sequence;
                }
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
            ->rawColumns(['lesson_image', 'sequence', 'actions'])
            ->make();
    }

    public function getDataAttachment(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $assAttachment = AssignmentAttachmentGroup::query();


        if ($request->assignment_id != "") {
            $assAttachment = $assAttachment->where('assignment_id', (int)$request->assignment_id)->orderBy('created_at', 'desc');
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $assAttachment = $assAttachment->where('assignment_id', (int)$request->assignment_id)->orderBy('created_at', 'desc')->onlyTrashed();
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

        return DataTables::of($assAttachment)
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
                        ->with(['route' => route('admin.assignments.attachment.sequence', ['id' => $q->id, 'assignment_id' => $request->assignment_id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.assignments.rearrangement.edit', ['id' => $q->id, 'assignment_id' => $request->assignment_id])])
                        ->render();
                    $view .= $edit;
                }

                return $view;
            })
            ->rawColumns(['actions'])
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


        return redirect()->route('admin.assignments.index', ['lesson_id' => $request->lesson_id])->withFlashSuccess(__('alerts.backend.general.updated'));
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

    public function rearrangementList($assignment_id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }

        return view('backend.assignments.rearrangement', compact('assignment_id'));
    }

    public function rearrangement($assignment_id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }

        return view('backend.assignments.rearrangement-create', compact('assignment_id'));
    }

    public function storeRearrangement(Request $request, $assignment_id)
    {
        $request->all();

        $assignment = Assignment::findOrFail($assignment_id);

        //TODO: Need to separate as attachment

        $attachmentGroup = new AssignmentAttachmentGroup();

        $attachmentGroup->assignment_id = $assignment_id;
        $attachmentGroup->title = $request->title;
        $attachmentGroup->full_text = $request->full_text;
        $attachmentGroup->user_id = auth()->user()->id;
        $attachmentGroup->save();

        return redirect()->route('admin.assignments.rearrangement.list',['assignment_id'=>$assignment_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }

    public function editRearrangement($assignment_id, $id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $attachment = AssignmentAttachmentGroup::findOrFail($id);

        return view('backend.assignments.rearrangement-edit', compact('assignment', 'attachment'));
    }

    public function updateRearrangement(Request $request, $assignment_id, $id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $attachmentGroup = AssignmentAttachmentGroup::findOrFail($id);

        \Log::info($request->all());

        $attachmentGroup->title = $request->title;
        $attachmentGroup->full_text = $request->full_text;
        $attachmentGroup->save();

        return redirect()->route('admin.assignments.rearrangement.list',['assignment_id'=>$assignment_id]);
    }

    public function attachment($assignment_id, $group_id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');


        return view('backend.assignments.attachment', compact('assignment_id', 'courses', 'group_id'));
    }

    public function attachmentList($assignment_id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }

        return view('backend.assignments.rearrangement', compact('assignment_id'));
    }


    public function storeAttachment(StoreAttachmentsRequest $request, $assignment_id, $group_id)
    {
        $request->all();

        $assignment = Assignment::findOrFail($assignment_id);
        $attach_group = AssignmentAttachmentGroup::findOrFail($group_id);
        $position = AssignmentAttachment::where('group_id', $group_id)->max('position') + 1;

        \Log::info($position);

        //TODO: Need to separate as attachment

        $attachment = new AssignmentAttachment();
        $hasAttacment = false;

        if(isset($request->title_attach)){
            $attachment->title = $request->title_attach;
        }
        if(isset($request->description_attach)){
            $attachment->full_text = $request->description_attach;
        }
        if(isset($request->vimeoVideo)){
            $attachment->vimeo_id = $request->vimeoVideo;
            $hasAttacment = true;
        }
        if(isset($request->youtubeVideo)){
            $attachment->youtube_id = $request->youtubeVideo;
            $hasAttacment = true;
        }
        if(isset($request->position)){
            $attachment->position = $request->position;
        }else{
            $attachment->position = 1;
        }
        if(isset($request->metaData)){
            $attachment->meta_title = $request->metaData;
        }

        if($request->hasFile('video_file') || $request->hasFile('attachment_file')){
            $hasAttacment = true;
        }

        if(!$hasAttacment){
            return back()->withFlashDanger('Upload a file or insert video ID.');
        }
        /**
         *
         * OLD-FLOW : All media in one attachment
         */

        if($hasAttacment){
            $attachment->group_id = $attach_group->id;
            $attachment->user_id = auth()->user()->id;
            $attachment->position = $position;
            $attachment->save();


            if ($request->input('vimeoVideo')) {
                $video = $request->vimeoVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->vimeoVideo));

                $media = new Media();
                $media->model_type = AssignmentAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'vimeo';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->input('youtubeVideo')) {
                $video = $request->youtubeVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->youtubeVideo));

                $media = new Media();
                $media->model_type = AssignmentAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'youtube';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            //Saving  videos
            if($request->hasFile('video_file')){
                $model_type = AssignmentAttachment::class;
                $model_id = $attachment->id;
                $name = $attachment->title . ' - video';

                $file = \Illuminate\Support\Facades\Request::file('video_file');
                $filename = time() . '-' . $file->getClientOriginalName();
                $size = $file->getSize() / 1024;
                \Log::info('SIZE :::: :'.$size);
                $path = public_path() . '/storage/uploads/';
                $file->move($path, $filename);

                $video_id = $filename;
                $url = asset('storage/uploads/' . $filename);

                $media = Media::where('url', $video_id)
                    ->where('type', '=', 'upload')
                    ->where('model_type', '=', 'App\Models\AssignmentAttachment')
                    ->where('model_id', '=', $attach_group->id)
                    ->first();

                if ($media == null) {
                    $media = new Media();
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = 'upload';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();

            }

//            $cntFile = 0;
//
//            foreach ($request->file('attachment_file') as $item) {
//                \Log::info('attachment_file>>>>'. $item);
//                $new_attach = '';
//                if($cntFile > 0) {
//                    $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $attach_group->id);
//                    $new_attach->position = $position++;
//                    $new_attach->save();
//                }
//
//                $media = $this->saveOneMedia($item, AssignmentAttachment::class, ($cntFile == 0? $attachment : $new_attach));
//
//                $cntFile++;
//
//            }

            $request = $this->saveAllFiles($request, 'attachment_file', AssignmentAttachment::class, $attachment, true);

        }

        return redirect()->route('admin.assignments.attachment.sequence',['assignment_id'=>$assignment_id, 'id' => $attach_group->id])->withFlashSuccess(__('alerts.backend.general.created'));
    }

    public function editAttachment($assignment_id, $group_id, $id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $attachment = AssignmentAttachment::findOrFail($id);

        return view('backend.assignments.edit-attachment', compact('assignment', 'attachment', 'group_id'));
    }

    public function updateAttachment(Request $request, $assignment_id, $group_id, $id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $attach_group = AssignmentAttachmentGroup::findOrFail($group_id);
        $attachment = AssignmentAttachment::findOrFail($id);

        \Log::info($request->all());

        $hasAttacment = false;
        $addVimeo = false;
        $addYoutube = false;
        $deleteMedia = [];

        if(isset($request->title)){
            $attachment->title = $request->title;
        }
        if(isset($request->full_text)){
            $attachment->full_text = $request->full_text;
        }
        if(isset($request->position)){
            $attachment->position = $request->position;
        }else{
            $attachment->position = 1;
        }
        if(isset($request->meta_title)){
            $attachment->meta_title = $request->meta_title;
        }

        if(isset($request->vimeo_id)){
            $addVimeo = true;
            if($attachment->vimeo_id !==$request->vimeo_id){
                $deleteMedia[] = ('vimeo');
            }else{
                $addVimeo = false;
            }

            $attachment->vimeo_id = $request->vimeo_id;
            $hasAttacment = true;
        }elseif(!isset($request->vimeo_id) && isset($attachment->vimeo_id)){
            // means remove compared to DB
            $attachment->vimeo_id = null;
            $deleteMedia[] = ('vimeo');
        }

        if(isset($request->youtube_id)){
            $addYoutube = true;
            if($attachment->youtube_id !== $request->youtube_id){
                $deleteMedia[] = ('youtube');
            }else{
                $addYoutube = false;
            }

            $attachment->youtube_id = $request->youtube_id;
            $hasAttacment = true;
        }elseif(!isset($request->youtube_id) && isset($attachment->youtube_id)){
            // means remove compared to DB
            $attachment->youtube_id = null;
            $deleteMedia[] = ('youtube');
        }

        //check existing media
        if(!$hasAttacment){
            if($attachment->media->count() > 0){
                foreach($attachment->media as $_media){
                    if(str_contains($_media->type,'image') || $_media->type == 'upload'){
                        //upload file is not removable
                        $hasAttacment = true;
                        break;
                    }
                }

            }
        }

        if(!$hasAttacment){
            return back()->withFlashDanger('Upload a file or insert video ID.');
        }

        // compare prev and before for vimeo / youtube
        if($hasAttacment){
            $attachment->save();

            if ($addVimeo && $request->input('vimeo_id')) {
                $video = $request->vimeo_id;
                $url = $video;
                $video_id = array_last(explode('/', $request->vimeo_id));

                $media = new Media();
                $media->model_type = AssignmentAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'vimeo';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($addYoutube && $request->input('youtube_id')) {
                $video = $request->youtube_id;
                $url = $video;
                $video_id = array_last(explode('/', $request->youtube_id));

                $media = new Media();
                $media->model_type = AssignmentAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'youtube';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

        }

        return redirect()->route('admin.assignments.attachment.sequence',['assignment_id'=>$assignment_id, 'id' => $group_id]);
    }

    public function attachmentSequence($assignment_id, $id)
    {
        $assignment = Assignment::findOrFail($assignment_id);
        $attachment = AssignmentAttachmentGroup::findOrFail($id);

        return view('backend.assignments.sequence', compact('assignment','attachment'));
    }

    public function updateSequence(Request $request, $assignment_id, $group_id)
    {

        \Log::info($request->changeSeq);

        if(isset($request->changeSeq)) {
            $changeSeq = json_decode($request->changeSeq);

            $seqArray = [];
            foreach ($changeSeq as $key => $val) {
                $seqArray['d' . $key] = $val;
            }

            $assignment = Assignment::findOrFail($assignment_id);
            $attach_group = AssignmentAttachmentGroup::findOrFail($group_id);

            foreach ($attach_group->attachments as $item) {
                if (!empty($seqArray['d' . $item->id])) {
                    $new_seq = $seqArray['d' . $item->id];
                    $item->position = $new_seq;
                    $item->save();
                }
            }
        }
        return back()->withFlashSuccess(__('alerts.backend.general.updated'));

    }

    public function deleteAttachment($assignment_id, $id)
    {
//        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
//        $submission = Submission::where('id', $submission_id)->first();
        $attachment = AssignmentAttachment::findOrFail($id);

        if($attachment != null){
//            foreach ($attachment->media as $delItem) {
//                $this->deleteMedia($delItem);
//            }

            // Only delete attachment as the media might be using by student
            $attachment->delete();
        }

        return back();
    }

    public function deleteMedia($media)
    {
        if($media){
            //Delete Related data
            $filename = $media->file_name;

            $media->delete();

            //Delete Photo
            $destinationPath = public_path() . '/storage/uploads/'.$filename;
            if (file_exists($destinationPath)) {
                unlink($destinationPath);
            }
        }
    }

    public function createAttachmentClass($title, $desc, $metaData, $group_id){
        $attachment = new AssignmentAttachment();

        if(isset($title)){
            $attachment->title = $title;
        }
        if(isset($desc)){
            $attachment->full_text = $desc;
        }
        if(isset($metaData)){
            $attachment->meta_title = $metaData;
        }

        $attachment->group_id = $group_id;
        $attachment->user_id = auth()->user()->id;

        return $attachment;
    }
}
