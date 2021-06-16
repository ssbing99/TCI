<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreAttachmentsRequest;
use App\Http\Requests\Admin\StoreSubmissionsRequest;
use App\Mail\Frontend\FlexiMail;
use App\Models\Assignment;
use App\Models\AssignmentAttachmentGroup;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Log;
use App\Models\Media;
use App\Models\Submission;
use App\Models\SuggestAttachment;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{

    use FileUploadTrait;

    private $path;

    public function __construct()
    {
        $path = 'frontend';
        if (session()->has('display_type')) {
            if (session('display_type') == 'rtl') {
                $path = 'frontend-rtl';
            } else {
                $path = 'frontend';
            }
        } else if (config('app.display_type') == 'rtl') {
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }


    public function readAllLog($teacher_id)
    {
        $logs = Log::where('teacher_id', $teacher_id)->where('unread', 1)->get();
        if(count($logs) > 0){
            foreach($logs as $log){
                $log->unread = 0;
                $log->update();
            }
        }

        return back();
    }

    public function createLog($title, $desc, $user_id, $teacher_id, $submission_id)
    {
        $log = new Log();
        $log->user_id = $user_id;
        $log->submission_id = $submission_id;
        $log->teacher_id = $teacher_id;
        $log->title = $title;
        $log->description = $desc;
        $log->unread = 1;
        $log->save();
    }

    public function getAssgType($assignment, $type) {

        $reType = $assignment->rearrangement != 0;

        if($type == null) {
            return  !$reType;
        }

        if($type == 'RE') {
            return  $reType;
        }

        if($type == 'AD') {
            return  ($reType && !is_null($assignment->rearrangement_type) && $assignment->rearrangement_type == 'admin' );
        }

        if($type == 'ST') {
            return  ($reType && !is_null($assignment->rearrangement_type) && $assignment->rearrangement_type == 'student' );
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $completed_assignments = "";
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();
        $otherSubmission = Submission::where('assignment_id', $id)->where('user_id','!=', auth()->user()->id)->orderBy('created_at')->get();

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

        $completed_assignments = \Auth::user()->submissions()
            ->where('assignment_id', $assignment->id)
            ->get()
            ->toArray();

        $view_path = returnPathByTheme($this->path.'.courses.assignment', 5,'-');

        return view($view_path, compact('assignment', 'course','lesson', 'completed_assignments','otherSubmission'));
    }

    public function showWithSubmission($id)
    {
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

        $view_path = returnPathByTheme($this->path.'.courses.student-assignment', 5,'-');

        return view($view_path, compact('assignment', 'course','lesson'));
    }

    /**
     * Display the attachment
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubmission($id)
    {
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();

        $reTypeAD = $this->getAssgType($assignment, 'AD');
        $reTypeST = $this->getAssgType($assignment, 'ST');
        $groups = [];

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

        $submission = Submission::withoutGlobalScope('filter')
            ->where('assignment_id', $assignment->id)
            ->where('user_id', \Auth::user()->id)->first();

        if($reTypeAD || $reTypeST) {

            $collection = new Collection($submission->suggestions);
            $groups = $collection->groupBy(function($item, $key) {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('m/d/Y H:i:s');
            });

        }

        $view_path = returnPathByTheme($this->path.'.courses.submission', 5,'-');

        return view($view_path, compact('assignment', 'course','lesson', 'submission', 'groups'));
    }

    public function showStudentSubmission($assignment_id, $submission_id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();

        $reTypeAD = $this->getAssgType($assignment, 'AD');
        $reTypeST = $this->getAssgType($assignment, 'ST');
        $groups = [];

        $submission = Submission::withoutGlobalScope('filter')
            ->where('id', $submission_id)->first();

        if($reTypeAD || $reTypeST) {

            $collection = new Collection($submission->suggestions);
            $groups = $collection->groupBy(function($item, $key) {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('m/d/Y H:i:s');
            });

            $view_path = returnPathByTheme($this->path . '.courses.student-re-submission', 5, '-');
        }else {
            $view_path = returnPathByTheme($this->path . '.courses.student-submission', 5, '-');
        }

        return view($view_path, compact( 'submission','assignment', 'groups'));
    }

    private function studentPostedInCourseMail($teachers, $content)
    {
        try {
            foreach ($teachers as $teacher) {
                $content['receiver_name'] = $teacher->name;
                \Mail::to($teacher->email)->send(new FlexiMail($content, 'studentPostedInCourseMail', 'Student Posted In Course'));
            }
        }catch (\Exception $e){
            \Log::info($e);
        }
    }

    private function instructorPostedInCourseMail($email, $content)
    {
        try {
            \Mail::to($email)->send(new FlexiMail($content, 'instructorPostedInCourseMail', 'Instructor Posted In Course'));
        }catch (\Exception $e){
            \Log::info($e);
        }
    }

    private function instructorPostedInCourseMultiMail($students)
    {
        try {
            foreach ($students as $student) {
                $content['receiver_name'] = $student->name;
                \Mail::to($student->email)->send(new FlexiMail($content, 'instructorPostedInCourseMail', 'Instructor Posted In Course'));
            }
        }catch (\Exception $e){
            \Log::info($e);
        }
    }

    /**
     * Display the submission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createSubmission($id)
    {
        $rearrangement = null;
        $attachments = null;
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();

        $rearrangement = $assignment->rearrangementGroup();

        if ($rearrangement != null) {
            $attachments = $rearrangement->attachments;
        }

        $reTypeAD = $this->getAssgType($assignment, 'AD');

        if($reTypeAD) {
            $view_path = returnPathByTheme($this->path . '.courses.add-re-submission', 5, '-');
        }else {
            $view_path = returnPathByTheme($this->path . '.courses.add-submission', 5, '-');
        }

        return view($view_path, compact('assignment','attachments'));
    }

    public function storeSubmission(StoreSubmissionsRequest $request, $assignment_id)
    {
        ini_set('memory_limit', '-1');
        $request->all();
        \Log::info($request->all());
        $assignment = Assignment::find($assignment_id);

        $submission = new Submission();
        $submission->user_id = auth()->user()->id;
        $submission->assignment_id = $assignment_id;
        $submission->title = $request->title;
        $submission->description = $request->description;
//\Log::info(json_encode($submission));
        $submission->save();

        $this->createLog(auth()->user()->full_name.' has submitted the assignment',NULL, auth()->user()->id, $assignment->lesson->course->teachers()->first()->id, $submission->id);

        //TODO: attachment

        $attachment = new Attachment();
        $hasAttacment = false;

        if(isset($request->title_attach)){
            $attachment->title = $request->title_attach;
            $hasAttacment = true;
        }
        if(isset($request->description_attach)){
            $attachment->full_text = $request->description_attach;
            $hasAttacment = true;
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
            $hasAttacment = true;
        }else{
            $attachment->position = 1;
        }
        if(isset($request->metaData)){
            $attachment->meta_title = $request->metaData;
            $hasAttacment = true;
        }

        if($request->hasFile('video_file') || $request->hasFile('attachment_file')){
            $hasAttacment = true;
        }

        \Log::info('$hasAttacment: '.$hasAttacment);

        if($hasAttacment){
            $attachment->submission_id = $submission->id;
            $attachment->user_id = auth()->user()->id;
            $attachment->position = 1;
            $attachment->save();

            //Saving  videos
            if($request->hasFile('video_file')){
                $model_type = Attachment::class;
                $model_id = $attachment->id;
                $name = $attachment->title . ' - video';

                $file = \Illuminate\Support\Facades\Request::file('video_file');
                $filename = time() . '-' . $file->getClientOriginalName();
                $size = $file->getSize() / 1024;
                $path = public_path() . '/storage/uploads/';
                $file->move($path, $filename);

                $video_id = $filename;
                $url = asset('storage/uploads/' . $filename);

                $media = Media::where('url', $video_id)
                    ->where('type', '=', 'upload')
                    ->where('model_type', '=', 'App\Models\Attachment')
                    ->where('model_id', '=', $submission->id)
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

            $cntFile = 0;
            $position = 2;

            foreach ($request->file('attachment_file') as $item) {
                \Log::info('attachment_file>>>>'. $item);
                $new_attach = '';
                if($cntFile > 0) {
                    $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
                    $new_attach->position = $position++;
                    $new_attach->save();
                }

                $media = $this->saveOneMedia($item, Attachment::class, ($cntFile == 0? $attachment : $new_attach));

                $cntFile++;

            }

//            $request = $this->saveAllFiles($request, 'attachment_file', Attachment::class, $attachment, true);

        }

        dispatch(function () use ($assignment) {
            $content['student_name'] = auth()->user()->name;
            $content['title'] = $assignment->lesson->course->title;
            $this->studentPostedInCourseMail($assignment->lesson->course->teachers, $content);
        })->afterResponse();

//        if($hasAttacment){
//            /**
//             *
//             * NEW FLOW
//             * sequence , separate attachment
//             */
//            $attachment_list = new Collection();
//
//            if($request->input('vimeoVideo') && isset($request->vimeoVideo)){
//                $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//                $new_attach->vimeo_id = $request->vimeoVideo;
//                $new_attach->position = $position++;
//
//                $attachment_list->push($new_attach);
//            }
//
//            if($request->input('youtubeVideo') && isset($request->youtubeVideo)){
//                $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//                $new_attach->youtube_id = $request->youtubeVideo;
//                $new_attach->position = $position++;
//
//                $attachment_list->push($new_attach);
//            }
//
//            if($request->hasFile('video_file')){
//                $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//                $new_attach->attach_video = 'attach-video';
//                $new_attach->position = $position++;
//
//                $attachment_list->push($new_attach);
//            }
//
//            if($request->hasFile('attachment_file')){
//                $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//                $new_attach->attach_file = 'attach-file';
//                $new_attach->position = $position++;
//
//                $attachment_list->push($new_attach);
//            }
//
//            foreach ($attachment_list as $item) {
//                \Log::info('$item v '.isset($item->vimeo_id));
//                \Log::info('$item y '.isset($item->youtube_id));
//                \Log::info('$item av '.isset($item->attach_video));
//                \Log::info('$item aF '.isset($item->attach_file));
//
//                // Save vimeo
//                if(isset($item->vimeo_id)){
//                    $item->save();
//
//                    // save media
//                    $video = $request->vimeoVideo;
//                    $url = $video;
//                    $video_id = array_last(explode('/', $request->vimeoVideo));
//
//                    $media = new Media();
//                    $media->model_type = Attachment::class;;
//                    $media->model_id = $item->id;;
//                    $media->name = $video_id;
//                    $media->url = $url;
//                    $media->type = 'vimeo';
//                    $media->file_name = $video_id;
//                    $media->size = 0;
//                    $media->save();
//                }
//
//                // Save youtube
//                if(isset($item->youtube_id)){
//                    $item->save();
//
//                    // save media
//                    $video = $request->youtubeVideo;
//                    $url = $video;
//                    $video_id = array_last(explode('/', $request->youtubeVideo));
//
//                    $media = new Media();
//                    $media->model_type = Attachment::class;;
//                    $media->model_id = $item->id;;
//                    $media->name = $video_id;
//                    $media->url = $url;
//                    $media->type = 'youtube';
//                    $media->file_name = $video_id;
//                    $media->size = 0;
//                    $media->save();
//                }
//
//                // Save Video file
//                if(isset($item->attach_video)){
//                    $item->save();
//
//                    // save media
//                    $model_type = Attachment::class;
//                    $model_id = $item->id;
//                    $name = $item->title . ' - video';
//
//                    $file = \Illuminate\Support\Facades\Request::file('video_file');
//                    $filename = time() . '-' . $file->getClientOriginalName();
//                    $size = $file->getSize() / 1024;
//                    \Log::info('SIZE :::: :'.$size);
//                    $path = public_path() . '/storage/uploads/';
//                    $file->move($path, $filename);
//
//                    $video_id = $filename;
//                    $url = asset('storage/uploads/' . $filename);
//
//                    $media = Media::where('url', $video_id)
//                        ->where('type', '=', 'upload')
//                        ->where('model_type', '=', 'App\Models\Attachment')
//                        ->where('model_id', '=', $submission->id)
//                        ->first();
//
//                    if ($media == null) {
//                        $media = new Media();
//                    }
//                    $media->model_type = $model_type;
//                    $media->model_id = $model_id;
//                    $media->name = $name;
//                    $media->url = $url;
//                    $media->type = 'upload';
//                    $media->file_name = $video_id;
//                    $media->size = 0;
//                    $media->save();
//                }
//
//                // Save File
//                if(isset($item->attach_file)){
//                    $item->save();
//
//                    // save media
//                    $request = $this->saveAllFiles($request, 'attachment_file', Attachment::class, $item, true);
//                }
//            }
//            /**
//             *
//             * END NEW FLOW
//             */
//        }        
        
        return redirect()->route('submission.show', ['id' => $assignment_id])->withFlashSuccess('Submission created!');
    }

    public function storeSubmissionRearrangement(StoreSubmissionsRequest $request, $assignment_id)
    {
        ini_set('memory_limit', '-1');
        $request->all();
        \Log::info($request->all());
        \Log::info('storeSubmission');
        $assignment = Assignment::find($assignment_id);

        $submission = new Submission();
        $submission->user_id = auth()->user()->id;
        $submission->assignment_id = $assignment_id;
        $submission->title = $request->title;
        $submission->description = $request->description;
        $submission->save();

        $this->createLog(auth()->user()->full_name.' has submitted the assignment',NULL, auth()->user()->id, $assignment->lesson->course->teachers()->first()->id, $submission->id);

        //TODO: attachment

        $rearrangement = $assignment->rearrangementGroup();

        if ($rearrangement != null) {
            $changeSeq = json_decode($request->changeSeq);

            $seqArray = [];
            foreach ($changeSeq as $key => $val) {
                $seqArray['d' . $key] = $val;
            }

            foreach ($rearrangement->attachments as $item) {
                $new_seq = 0;
                if (!empty($seqArray['d' . $item->id])) {
                    $new_seq = $seqArray['d' . $item->id];
                }

                $new_attach = $this->createAttachmentClass(NULL, NULL, NULL, $submission->id);
                $new_attach->a_group_id = $rearrangement->id;
                $new_attach->position = $new_seq;
                $new_attach->save();

                if(isset($item->media) && !$item->media->isEmpty()) {
                    $_media = $item->media->first();

                    // copy from rearrangement attachment
                    $media = new Media();
                    $media->model_type = Attachment::class;
                    $media->model_id = $new_attach->id;
                    if($_media->type == 'upload' || str_contains($_media->type,'youtube') || str_contains($_media->type,'vimeo')){
                        $media->url = $_media->url;
                    } else {
                        $media->name = $_media->name;
                    }

                    $media->type = $_media->type;
                    $media->file_name = $_media->file_name;
                    $media->size = 0;
                    $media->save();

                }

            }
        }

        dispatch(function () use ($assignment) {
            $content['student_name'] = auth()->user()->name;
            $content['title'] = $assignment->lesson->course->title;
            $this->studentPostedInCourseMail($assignment->lesson->course->teachers, $content);
        })->afterResponse();

        return redirect()->route('submission.show', ['id' => $assignment_id])->withFlashSuccess('Submission created!');
    }

    public function storeNewSequence(Request $request, $assignment_id, $submission_id, $groupId)
    {
        $assignment = Assignment::find($assignment_id);

        $reType = $this->getAssgType($assignment, 'RE');
        $reTypeAD = $this->getAssgType($assignment, 'AD');
        $reTypeST = $this->getAssgType($assignment, 'ST');

        $submission = Submission::where('id', $submission_id)->first();
        $attachments = [];
        $attachmentGroup = null;
        $rearrangement = null;

        if($reTypeAD) {
            $attachmentGroup = AssignmentAttachmentGroup::findOrFail($groupId);
            $rearrangement = $assignment->rearrangementGroup();
            $attachments = $rearrangement->attachments;
        }

        if($reTypeST) {
            $attachments = $submission->attachments;
        }

        \Log::info($request->changeSeq);

        if (count($attachments) > 0) {
            $changeSeq = json_decode($request->changeSeq);

            $seqArray = [];
            foreach ($changeSeq as $key => $val) {
                $seqArray['d' . $key] = $val;
            }

            foreach ($attachments as $item) {
                $new_seq = 0;
                if (!empty($seqArray['d' . $item->id])) {
                    $new_seq = $seqArray['d' . $item->id];
                }

                $new_attach = $this->createSuggestAttachmentClass($reTypeAD? $attachmentGroup->id: 0, $submission->id, $submission->user->id, auth()->user()->id);
                $new_attach->position = $new_seq;
                $new_attach->save();

                if(isset($item->media) && !$item->media->isEmpty()) {
                    $_media = $item->media->first();

                    // copy from rearrangement attachment
                    $media = new Media();
                    $media->model_type = SuggestAttachment::class;
                    $media->model_id = $new_attach->id;
                    if($_media->type == 'upload' || str_contains($_media->type,'youtube') || str_contains($_media->type,'vimeo')){
                        $media->url = $_media->url;
                    } else {
                        $media->name = $_media->name;
                    }

                    $media->type = $_media->type;
                    $media->file_name = $_media->file_name;
                    $media->size = 0;
                    $media->save();

                }

            }
        }

        return redirect()->route('student.submission.show', ['assignment_id'=>$assignment->id, 'submission_id'=>$submission->id]);
    }

    public function createAttachment($assignment_id, $submission_id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();

        $view_path = returnPathByTheme($this->path.'.courses.add-attachment', 5,'-');

        return view($view_path, compact('assignment','submission'));
    }

    public function storeAttachment(StoreAttachmentsRequest $request, $assignment_id, $submission_id)
    {
        ini_set('memory_limit', '-1');
        $request->all();

        $submission = Submission::where('id', $submission_id)->first();
        $position = Attachment::where('submission_id', $submission_id)->max('position') + 1;

        \Log::info($request->all());

        //TODO: Need to separate as attachment

        $attachment = new Attachment();
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

            $attachment->submission_id = $submission->id;
            $attachment->user_id = auth()->user()->id;
            $attachment->position = $position++;
            $attachment->save();


            if ($request->input('vimeoVideo')) {
                $video = $request->vimeoVideo;
                $url = $video;
                $video_id = array_last(explode('/', $request->vimeoVideo));

                $media = new Media();
                $media->model_type = Attachment::class;;
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
                $media->model_type = Attachment::class;;
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
                $model_type = Attachment::class;
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
                    ->where('model_type', '=', 'App\Models\Attachment')
                    ->where('model_id', '=', $submission->id)
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

            $cntFile = 0;

            foreach ($request->file('attachment_file') as $item) {
                \Log::info('attachment_file>>>>'. $item);
                $new_attach = '';
                if($cntFile > 0) {
                    $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
                    $new_attach->position = $position++;
                    $new_attach->save();
                }

                $media = $this->saveOneMedia($item, Attachment::class, ($cntFile == 0? $attachment : $new_attach));

                $cntFile++;

            }

//            $request = $this->saveAllFiles($request, 'attachment_file', Attachment::class, $attachment);

        }

        /**
         *
         * NEW FLOW
         * sequence , separate attachment
         */
//        $attachment_list = new Collection();
//
//        if($request->input('vimeoVideo') && isset($request->vimeoVideo)){
//            $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//            $new_attach->vimeo_id = $request->vimeoVideo;
//            $new_attach->position = $position++;
//
//            $attachment_list->push($new_attach);
//        }
//
//        if($request->input('youtubeVideo') && isset($request->youtubeVideo)){
//            $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//            $new_attach->youtube_id = $request->youtubeVideo;
//            $new_attach->position = $position++;
//
//            $attachment_list->push($new_attach);
//        }
//
//        if($request->hasFile('video_file')){
//            $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//            $new_attach->attach_video = 'attach-video';
//            $new_attach->position = $position++;
//
//            $attachment_list->push($new_attach);
//        }
//
//        if($request->hasFile('attachment_file')){
//            $new_attach = $this->createAttachmentClass($request->title_attach, $request->description_attach, $request->metaData, $submission->id);
//            $new_attach->attach_file = 'attach-file';
//            $new_attach->position = $position++;
//
//            $attachment_list->push($new_attach);
//        }
//
//        foreach ($attachment_list as $item) {
//            \Log::info('$item v '.isset($item->vimeo_id));
//            \Log::info('$item y '.isset($item->youtube_id));
//            \Log::info('$item av '.isset($item->attach_video));
//            \Log::info('$item aF '.isset($item->attach_file));
//
//            // Save vimeo
//            if(isset($item->vimeo_id)){
//                $item->save();
//
//                // save media
//                $video = $request->vimeoVideo;
//                $url = $video;
//                $video_id = array_last(explode('/', $request->vimeoVideo));
//
//                $media = new Media();
//                $media->model_type = Attachment::class;;
//                $media->model_id = $item->id;;
//                $media->name = $video_id;
//                $media->url = $url;
//                $media->type = 'vimeo';
//                $media->file_name = $video_id;
//                $media->size = 0;
//                $media->save();
//            }
//
//            // Save youtube
//            if(isset($item->youtube_id)){
//                $item->save();
//
//                // save media
//                $video = $request->youtubeVideo;
//                $url = $video;
//                $video_id = array_last(explode('/', $request->youtubeVideo));
//
//                $media = new Media();
//                $media->model_type = Attachment::class;;
//                $media->model_id = $item->id;;
//                $media->name = $video_id;
//                $media->url = $url;
//                $media->type = 'youtube';
//                $media->file_name = $video_id;
//                $media->size = 0;
//                $media->save();
//            }
//
//            // Save Video file
//            if(isset($item->attach_video)){
//                $item->save();
//
//                // save media
//                $model_type = Attachment::class;
//                $model_id = $item->id;
//                $name = $item->title . ' - video';
//
//                $file = \Illuminate\Support\Facades\Request::file('video_file');
//                $filename = time() . '-' . $file->getClientOriginalName();
//                $size = $file->getSize() / 1024;
//                \Log::info('SIZE :::: :'.$size);
//                $path = public_path() . '/storage/uploads/';
//                $file->move($path, $filename);
//
//                $video_id = $filename;
//                $url = asset('storage/uploads/' . $filename);
//
//                $media = Media::where('url', $video_id)
//                    ->where('type', '=', 'upload')
//                    ->where('model_type', '=', 'App\Models\Attachment')
//                    ->where('model_id', '=', $submission->id)
//                    ->first();
//
//                if ($media == null) {
//                    $media = new Media();
//                }
//                $media->model_type = $model_type;
//                $media->model_id = $model_id;
//                $media->name = $name;
//                $media->url = $url;
//                $media->type = 'upload';
//                $media->file_name = $video_id;
//                $media->size = 0;
//                $media->save();
//            }
//
//            // Save File
//            if(isset($item->attach_file)){
//                $item->save();
//
//                // save media
//                $request = $this->saveAllFiles($request, 'attachment_file', Attachment::class, $item, true);
//            }
//        }
        /**
         *
         * END NEW FLOW
         */

        return redirect()->route('submission.show', ['id' => $assignment_id])->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function editAttachment($assignment_id, $submission_id, $id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();
        $attachment = Attachment::findOrFail($id);

        $view_path = returnPathByTheme($this->path.'.courses.edit-attachment', 5,'-');

        return view($view_path, compact('assignment','submission', 'attachment'));
    }

    public function updateAttachment(Request $request, $assignment_id, $submission_id, $id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();
        $attachment = Attachment::findOrFail($id);

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
                $media->model_type = Attachment::class;;
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
                $media->model_type = Attachment::class;;
                $media->model_id = $attachment->id;;
                $media->name = $video_id;
                $media->url = $url;
                $media->type = 'youtube';
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

        }


        return redirect()->route('submission.attachment.sequence', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id]);
    }

    public function createAttachmentClass($title, $desc, $metaData, $submission_id){
        $attachment = new Attachment();

        if(isset($title)){
            $attachment->title = $title;
        }
        if(isset($desc)){
            $attachment->full_text = $desc;
        }
        if(isset($metaData)){
            $attachment->meta_title = $metaData;
        }

        $attachment->submission_id = $submission_id;
        $attachment->user_id = auth()->user()->id;

        return $attachment;
    }

    public function createSuggestAttachmentClass($group_id, $submission_id, $user_id, $teacher_id){
        $attachment = new SuggestAttachment();
        $attachment->a_group_id = $group_id;
        $attachment->submission_id = $submission_id;
        $attachment->user_id = $user_id;
        $attachment->teacher_id = $teacher_id;

        return $attachment;
    }

    public function editSubmission($assignment_id, $submission_id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();

        $view_path = returnPathByTheme($this->path.'.courses.edit-submission', 5,'-');

        return view($view_path, compact('assignment','submission'));
    }

    public function updateSubmission(StoreSubmissionsRequest $request, $assignment_id, $submission_id)
    {
        $request->all();

        $submission = Submission::where('id', '=', $submission_id)->first();
        if($submission) {
            $submission->title = $request->title;
            $submission->description = $request->description;
            $submission->save();

            return redirect()->route('submission.show', ['id' => $assignment_id]);
        }
        return abort(404);
    }


    public function attachmentSequence($assignment_id, $submission_id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();
        $attachments = $submission->attachmentsById(auth()->user()->id)->get();

        $reType = $assignment->rearrangement != 0;

        $view_path = returnPathByTheme($this->path.($reType ? '.courses.edit-re-sequence' : '.courses.edit-sequence'), 5,'-');

        return view($view_path, compact('assignment','submission', 'attachments'));
    }

    public function attachmentSuggestSequence($assignment_id, $submission_id, $groupId)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();

        $reTypeAD = $this->getAssgType($assignment, 'AD');
        $reTypeST = $this->getAssgType($assignment, 'ST');

        $submission = Submission::where('id', $submission_id)->first();
        $attachmentGroup = null;
        $attachments = null;

        if($reTypeAD) {
            $attachmentGroup = AssignmentAttachmentGroup::findOrFail($groupId);
            $attachments = $attachmentGroup->attachments;
        }

        if($reTypeST) {
            $attachments = $submission->attachments;
        }

        $view_path = returnPathByTheme($this->path.'.courses.suggest-sequence', 5,'-');

        return view($view_path, compact('assignment','submission', 'attachments','groupId'));
    }

    public function updateSequence(Request $request, $assignment_id, $submission_id)
    {

        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();

        $reType = $this->getAssgType($assignment, 'RE');
        $reTypeAD = $this->getAssgType($assignment, 'AD');
        $reTypeST = $this->getAssgType($assignment, 'ST');

        \Log::info($request->changeSeq);

        if(isset($request->changeSeq)) {
            $changeSeq = json_decode($request->changeSeq);

            $seqArray = [];
            foreach ($changeSeq as $key => $val) {
                $seqArray['d' . $key] = $val;
            }

            $submission = Submission::where('id', $submission_id)->first();
            $attachments = $submission->attachmentsById(auth()->user()->id)->get();

            // cater for rearrangement
            if(!$reType) {
                foreach ($attachments as $item) {
                    if (!empty($seqArray['d' . $item->id])) {
                        $new_seq = $seqArray['d' . $item->id];
                        $item->position = $new_seq;
                        $item->save();
                    }
                }
            }elseif($reTypeAD || $reTypeST){// == 1
                foreach ($attachments as $item) {
                    $new_seq = 0;
                    if (!empty($seqArray['d' . $item->id])) {
                        $new_seq = $seqArray['d' . $item->id];
                    }
                    $item->position = $new_seq;
                    $item->save();
                }
            }
        }
        return redirect()->route('submission.attachment.sequence', ['assignment_id' => $assignment_id, 'submission_id' => $submission_id]);

    }

    public function deleteAttachment($assignment_id, $submission_id, $id)
    {
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $submission = Submission::where('id', $submission_id)->first();
        $attachment = Attachment::findOrFail($id);

        if($attachment != null){
//            foreach ($attachment->media as $delItem) {
//                $this->deleteMedia($delItem);
//            }

//            $attachment->delete();

            /*
            1. for reset purpose
            2. due to have reset feature so no need to delete the media & attachment, also
            can save effort to create a table to store delete records
            */
            $attachment->position = 0;
            $attachment->save();
        }

        return back();
    }

    public function showCritiques($assignment_id, $submission_id, $attachment_id)
    {
        \Log::info($assignment_id);
        \Log::info($submission_id);
        $assignment = Assignment::where('id', $assignment_id)->where('published', '=', 1)->first();
        $attachment = Attachment::findOrFail($attachment_id);

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

//        $completed_assignments = \Auth::user()->submissions()
//            ->where('assignment_id', $assignment->id)
//            ->get();

        $submission = Submission::withoutGlobalScope('filter')
            ->where('assignment_id', $assignment->id)
            ->where('user_id', \Auth::user()->id)->first();

        $view_path = returnPathByTheme($this->path.'.courses.critique', 5,'-');

        return view($view_path, compact('assignment', 'attachment', 'course','lesson', 'submission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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



    public function addComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        \Log::info($request->all());
        $lesson = Assignment::findOrFail($request->id);
        $review = new Comment();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $lesson->id;
        $review->reviewable_type = Assignment::class;
        $review->rating = $request->rating;
        $review->content = $request->comment;
        $review->save();

        $hasAttacment = false;

        if($request->hasFile('video_file') || $request->hasFile('attachment_file')){
            $hasAttacment = true;
        }

        \Log::info('$hasAttacment: '.$hasAttacment);

        dispatch(function () use ($lesson) {
            if (auth()->user()->hasRole('student')) {
                $content['student_name'] = auth()->user()->name;
                $content['title'] = $lesson->lesson->course->title;
                $this->studentPostedInCourseMail($lesson->lesson->course->teachers, $content);

            } else {
                $this->instructorPostedInCourseMultiMail($lesson->lesson->course->students);
            }
        })->afterResponse();

        if($hasAttacment){

            //Saving  videos
            if($request->hasFile('video_file')){
                $model_type = Comment::class;
                $model_id = $review->id;
                $name = ' - video';

                $file = \Illuminate\Support\Facades\Request::file('video_file');
                $filename = time() . '-' . $file->getClientOriginalName();
                $size = $file->getSize() / 1024;
                $path = public_path() . '/storage/uploads/';
                $file->move($path, $filename);

                $video_id = $filename;
                $url = asset('storage/uploads/' . $filename);

                $media = Media::where('url', $video_id)
                    ->where('type', '=', 'upload')
                    ->where('model_type', '=', 'App\Models\Comment')
                    ->where('model_id', '=', $review->id)
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

            $request = $this->saveAllFiles($request, 'attachment_file', Comment::class, $review, true);

        }

        return back();
    }

    public function deleteComment($id){
        $comment = Comment::findOrFail($id);
        if(auth()->user()->id == $comment->user_id){
            $comment->delete();
            return back();
        }
        return abort(419);
    }

    public function addCritique(Request $request)
    {
        $this->validate($request, [
            'critique' => 'required'
        ]);

        // create comment data first
        $review = new Comment();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $request->attachment_id;
        $review->reviewable_type = Attachment::class;
        $review->rating = $request->rating;
        $review->content = $request->critique;
        $review->save();

        $attachment = Attachment::find($request->attachment_id);

        dispatch(function () use ($attachment) {            
            $content['student_name'] = auth()->user()->name;
            $content['title'] = $attachment->submission->assignment->lesson->course->title;
            $this->studentPostedInCourseMail($attachment->submission->assignment->lesson->course->teachers, $content);
        })->afterResponse();

        return back();
    }

    public function addAttachmentCritique(Request $request, $assignment_id, $submission_id)
    {

        $assignment = Assignment::find($assignment_id);
        $submission = Submission::find($submission_id);

        \Log::info($request->all());
        foreach($submission->attachments as $attachment){
            \Log::info('critique_'.$attachment->id.' : '.$request->input('critique_'.$attachment->id));
            \Log::info('file_'.$attachment->id.' : '.$request->hasFile('file_'.$attachment->id));


            if($request->input('critique_'.$attachment->id) || $request->hasFile('file_'.$attachment->id)) {
                $comment = $request->input('critique_'.$attachment->id)? $request->get('critique_'.$attachment->id) : null;

                // create comment data first
                $review = new Comment();
                $review->user_id = auth()->user()->id;
                $review->reviewable_id = $attachment->id;
                $review->reviewable_type = Attachment::class;
                $review->rating = NULL;
                $review->content = $comment;
                $review->save();

                // then insert media

                // media
//                if($request->hasFile('file_'.$attachment->id)){
//                    $model_type = Comment::class;
//                    $model_id = $review->id;
//                    $name = $attachment->title . ' - video';
//
//                    $file = \Illuminate\Support\Facades\Request::file('video_file');
//                    $filename = time() . '-' . $file->getClientOriginalName();
//                    $size = $file->getSize() / 1024;
//                    $path = public_path() . '/storage/uploads/';
//                    $file->move($path, $filename);
//
//                    $video_id = $filename;
//                    $url = asset('storage/uploads/' . $filename);
//
//                    $media = new Media();
//                    $media->model_type = $model_type;
//                    $media->model_id = $model_id;
//                    $media->name = $name;
//                    $media->url = $url;
//                    $media->type = 'upload';
//                    $media->file_name = $video_id;
//                    $media->size = 0;
//                    $media->save();
//
//                }

                $this->saveSingleFiles($request, 'file_'.$attachment->id, Comment::class, $review, false);

            }

        }


//        $this->validate($request, [
//            'critique' => 'required'
//        ]);
//        $lesson = Assignment::findORFail($request->assignment_id);
//        $submission = Submission::findORFail($request->submission_id);
//        $review = new Comment();
//        $review->user_id = auth()->user()->id;
//        $review->reviewable_id = $submission->id;
//        $review->reviewable_type = Submission::class;
//        $review->rating = $request->rating;
//        $review->content = $request->critique;
//        $review->save();

        dispatch(function () use ($submission) {
            $content['receiver_name'] = $submission->user->full_name;
            $this->instructorPostedInCourseMail($submission->user->email, $content);
        })->afterResponse();
        
        return back();
    }

}
