<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Requests\Admin\StoreAttachmentsRequest;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Media;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

class LessonsController extends Controller
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

        return view('backend.lessons.index', compact('courses'));
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


        if ($request->course_id != "") {
            $lessons = $lessons->where('course_id', (int)$request->course_id)->orderBy('created_at', 'desc');
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $lessons = Lesson::query()->where('live_lesson', '=', 0)->with('course')->orderBy('created_at', 'desc')->onlyTrashed();
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

        return DataTables::of($lessons)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.lessons', 'label' => 'id', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.lessons.show', ['lesson' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.lessons.edit', ['lesson' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.lessons.destroy', ['lesson' => $q->id])])
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
            ->addColumn('assignments', function ($q) {

                $assignment = '<a href="' . route('admin.assignments.index', ['lesson_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
                return $assignment;
            })
            ->addColumn('sequence', function ($q) {

                $sequence = '<a href="' . route('admin.lessons.attachment', ['lesson_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.lessons.attachment.sequence', ['lesson_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
                return $sequence;
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
            ->rawColumns(['lesson_image', 'assignments', 'sequence', 'actions'])
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
        return view('backend.lessons.create', compact('courses'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param  \App\Http\Requests\StoreLessonsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonsRequest $request)
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

        $slug_lesson = Lesson::where('slug', '=', $slug)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $lesson = Lesson::create($request->except('downloadable_files', 'lesson_image')
            + ['position' => Lesson::where('course_id', $request->course_id)->max('position') + 1]);

        $lesson->slug = $slug;
        $lesson->save();




        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = array_last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Lesson')
                    ->where('model_id', '=', $lesson->id)
                    ->first();
                $size = 0;
            } elseif ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();
                }
            } elseif ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $lesson->title . ' - video';
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

        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        if (($request->slug == "") || $request->slug == null) {
            $lesson->slug = str_slug($request->title);
            $lesson->save();
        }

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ($lesson->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }

        return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.created'));
    }

    /**
     * Show the form for editing Lesson.
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
        $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');

        $lesson = Lesson::with('media')->findOrFail($id);
        if ($lesson->media) {
            $videos = $lesson->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
        }

        return view('backend.lessons.edit', compact('lesson', 'courses', 'videos'));
    }

    /**
     * Update Lesson in storage.
     *
     * @param  \App\Http\Requests\UpdateLessonsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessonsRequest $request, $id)
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

        $slug_lesson = Lesson::where('slug', '=', $slug)->where('id', '!=', $id)->first();
        if ($slug_lesson != null) {
            return back()->withFlashDanger(__('alerts.backend.general.slug_exist'));
        }

        $lesson = Lesson::findOrFail($id);
        $lesson->update($request->except('downloadable_files', 'lesson_image'));
        $lesson->slug = $slug;
        $lesson->save();

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Lesson::class;
            $model_id = $lesson->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $lesson->title . ' - video';
            $media = $lesson->mediavideo;
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
                    $filename = $lesson->title . ' - video';
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
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $lesson->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
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
            }
        }
        if ($request->hasFile('add_pdf')) {
            $pdf = $lesson->mediaPDF;
            if ($pdf) {
                $pdf->delete();
            }
        }


        $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);

        $sequence = 1;
        if (count($lesson->course->courseTimeline) > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence');
            $sequence = $sequence + 1;
        }

        if ((int)$request->published == 1) {
            $timeline = CourseTimeline::where('model_type', '=', Lesson::class)
                ->where('model_id', '=', $lesson->id)
                ->where('course_id', $request->course_id)->first();
            if ($timeline == null) {
                $timeline = new CourseTimeline();
            }
            $timeline->course_id = $request->course_id;
            $timeline->model_id = $lesson->id;
            $timeline->model_type = Lesson::class;
            $timeline->sequence = $sequence;
            $timeline->save();
        }


        return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.updated'));
    }


    /**
     * Display Lesson.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();

        $lesson = Lesson::findOrFail($id);


        return view('backend.lessons.show', compact('lesson', 'tests', 'courses'));
    }


    /**
     * Remove Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::findOrFail($id);
        $lesson->chapterStudents()->where('course_id', $lesson->course_id)->forceDelete();
        $lesson->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Lesson at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Lesson::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        $lesson = Lesson::onlyTrashed()->findOrFail($id);

        if (File::exists(public_path('/storage/uploads/'.$lesson->lesson_image))) {
            File::delete(public_path('/storage/uploads/'.$lesson->lesson_image));
            File::delete(public_path('/storage/uploads/thumb/'.$lesson->lesson_image));
        }

        $timelineStep = CourseTimeline::where('model_id', '=', $id)
            ->where('course_id', '=', $lesson->course->id)->first();
        if ($timelineStep) {
            $timelineStep->delete();
        }

        $lesson->forceDelete();



        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    public function attachment($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        $courses = Course::get()->pluck('title', 'id')->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();

        $lesson = Lesson::findOrFail($id);


        return view('backend.lessons.attachment', compact('lesson', 'tests', 'courses'));
    }


    public function storeAttachment(StoreAttachmentsRequest $request, $lesson_id)
    {
        $request->all();

        $lesson = Lesson::findOrFail($lesson_id);
        $position = LessonAttachment::where('lesson_id', $lesson_id)->max('position') + 1;
        $course = $lesson->course;

        \Log::info($position);

        //TODO: Need to separate as attachment

        $attachment = new LessonAttachment();
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
            $attachment->lesson_id = $lesson->id;
            $attachment->user_id = auth()->user()->id;
            $attachment->position = $position;
            $attachment->save();


            if ($request->input('vimeoVideo')) {
                $video = $request->vimeoVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->vimeoVideo));

                $media = new Media();
                $media->model_type = LessonAttachment::class;;
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
                $media->model_type = LessonAttachment::class;;
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
                $model_type = LessonAttachment::class;
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
                    ->where('model_type', '=', 'App\Models\LessonAttachment')
                    ->where('model_id', '=', $lesson->id)
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

            $request = $this->saveAllFiles($request, 'attachment_file', LessonAttachment::class, $attachment, true);

        }

        return redirect()->route('admin.lessons.index',['course_id'=>$course->id])->withFlashSuccess(__('alerts.backend.general.created'));
    }

    public function editAttachment($lesson_id, $id)
    {
        $lesson = Lesson::findOrFail($lesson_id);
        $attachment = LessonAttachment::findOrFail($id);


        return view('backend.lessons.edit-attachment', compact('lesson', 'attachment'));
    }

    public function updateAttachment(Request $request, $lesson_id, $id)
    {
        $lesson = Lesson::findOrFail($lesson_id);
        $attachment = LessonAttachment::findOrFail($id);

        \Log::info($request->all());

        $hasAttacment = false;
        $addVimeo = false;
        $addYoutube = false;
        $deleteMedia = [];

        if(isset($request->title_attach)){
            $attachment->title = $request->title_attach;
        }
        if(isset($request->description_attach)){
            $attachment->full_text = $request->description_attach;
        }
        if(isset($request->position)){
            $attachment->position = $request->position;
        }else{
            $attachment->position = 1;
        }
        if(isset($request->metaData)){
            $attachment->meta_title = $request->metaData;
        }

        if(isset($request->vimeoVideo)){
            $addVimeo = true;
            if($attachment->vimeo_id !==$request->vimeoVideo){
                $deleteMedia[] = ('vimeo');
            }else{
                $addVimeo = false;
            }

            $attachment->vimeo_id = $request->vimeoVideo;
            $hasAttacment = true;
        }elseif(!isset($request->vimeoVideo) && isset($attachment->vimeo_id)){
            // means remove compared to DB
            $attachment->vimeo_id = null;
            $deleteMedia[] = ('vimeo');
        }

        if(isset($request->youtubeVideo)){
            $addYoutube = true;
            if($attachment->youtube_id !== $request->youtubeVideo){
                $deleteMedia[] = ('youtube');
            }else{
                $addYoutube = false;
            }

            $attachment->youtube_id = $request->youtubeVideo;
            $hasAttacment = true;
        }elseif(!isset($request->youtubeVideo) && isset($attachment->youtube_id)){
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

            // delete
            foreach ($deleteMedia as $delItem) {
                if($delItem == 'vimeo'){
                    $this->deleteMedia($attachment->mediaVimeo);
                }

                if($delItem == 'youtube'){
                    $this->deleteMedia($attachment->mediaYoutube);
                }
            }

            if ($addVimeo && $request->input('vimeoVideo')) {
                $video = $request->vimeoVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->vimeoVideo));

                $media = new Media();
                $media->model_type = LessonAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'vimeo';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($addYoutube && $request->input('youtubeVideo')) {
                $video = $request->youtubeVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->youtubeVideo));

                $media = new Media();
                $media->model_type = LessonAttachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'youtube';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

        }


        return redirect()->route('admin.lessons.attachment.sequence', ['lesson_id' => $lesson->id]);
    }

    public function attachmentSequence($lesson_id)
    {
        $lesson = Lesson::findOrFail($lesson_id);

        return view('backend.lessons.sequence', compact('lesson'));
    }

    public function updateSequence(Request $request, $lesson_id)
    {

        \Log::info($request->changeSeq);

        if(isset($request->changeSeq)) {
            $changeSeq = json_decode($request->changeSeq);

            $seqArray = [];
            foreach ($changeSeq as $key => $val) {
                $seqArray['d' . $key] = $val;
            }

            $lesson = Lesson::findOrFail($lesson_id);

            foreach ($lesson->attachments as $item) {
                if (!empty($seqArray['d' . $item->id])) {
                    $new_seq = $seqArray['d' . $item->id];
                    $item->position = $new_seq;
                    $item->save();
                }
            }
        }
        return back()->withFlashSuccess(__('alerts.backend.general.updated'));

    }

    public function deleteAttachment($lesson_id, $id)
    {
//        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
//        $submission = Submission::where('id', $submission_id)->first();
        $attachment = LessonAttachment::findOrFail($id);

        if($attachment != null){
            foreach ($attachment->media as $delItem) {
                $this->deleteMedia($delItem);
            }

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
}
