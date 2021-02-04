<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreSubmissionsRequest;
use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUploadTrait;
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

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

        $completed_assignments = \Auth::user()->submissions()
            ->where('assignment_id', $assignment->id)
            ->get()
            ->toArray();

        $view_path = returnPathByTheme($this->path.'.courses.assignment', 5,'-');

        return view($view_path, compact('assignment', 'course','lesson', 'completed_assignments'));
    }

    /**
     * Display the attachment
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubmission($id)
    {
        $completed_assignments = "";
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();

        $course = $assignment->lesson->course;
        $lesson = $assignment->lesson;

        $completed_assignments = \Auth::user()->submissions()
            ->where('assignment_id', $assignment->id)
            ->get()
            ->toArray();

        $view_path = returnPathByTheme($this->path.'.courses.submission', 5,'-');

        return view($view_path, compact('assignment', 'course','lesson', 'completed_assignments'));
    }

    /**
     * Display the submission
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createSubmission($id)
    {
        $assignment = Assignment::where('id', $id)->where('published', '=', 1)->first();

        $view_path = returnPathByTheme($this->path.'.courses.add-submission', 5,'-');

        return view($view_path, compact('assignment'));
    }

    public function storeSubmission(StoreSubmissionsRequest $request, $assignment_id)
    {
        $request->all();
        \Log::info($request->all());

        $submission = new Submission();
        $submission->user_id = auth()->user()->id;
        $submission->title = $request->title;
        $submission->description = $request->description;
//\Log::info(json_encode($submission));
        $submission->save();

        //TODO: attachment

        $attachment = new Attachment();
        $hasAttacment = false;

        if(isset($request->title_attach)){
            \Log::info($request->title_attach);
            $attachment->title = $request->title_attach;
            $hasAttacment = true;
        }
        if(isset($request->description_attach)){
            \Log::info($request->description_attach);
            $attachment->full_text = $request->description_attach;
            $hasAttacment = true;
        }
        if(isset($request->vimeoVideo)){
            \Log::info($request->vimeoVideo);
            $attachment->vimeo_id = $request->vimeoVideo;
            $hasAttacment = true;
        }
        if(isset($request->youtubeVideo)){
            \Log::info($request->youtubeVideo);
            $attachment->youtube_id = $request->youtubeVideo;
            $hasAttacment = true;
        }
        if(isset($request->position)){
            \Log::info($request->position);
            $attachment->position = $request->position;
            $hasAttacment = true;
        }
        if(isset($request->metaData)){
            \Log::info($request->metaData);
            $attachment->meta_title = $request->metaData;
            $hasAttacment = true;
        }

        //Saving  videos
        if($request->hasFile('video_file')){
            $model_type = Attachment::class;
            $model_id = $submission->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $submission->title . ' - video';

            $video_id = $request->video_file;
            $url = asset('storage/uploads/' . $video_id);
            $media = Media::where('url', $video_id)
                ->where('type', '=', 'upload')
                ->where('model_type', '=', 'App\Models\Attachment')
                ->where('model_id', '=', $submission->id)
                ->first();

            $hasAttacment = true;

        }

        return redirect()->route('submission.show', ['id' => $assignment_id])->withFlashSuccess(trans('alerts.backend.general.created'));
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

    public function addComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $lesson = Assignment::findORFail($request->id);
        $review = new Comment();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $lesson->id;
        $review->reviewable_type = Assignment::class;
        $review->rating = $request->rating;
        $review->content = $request->comment;
        $review->save();

        return back();
    }
}
