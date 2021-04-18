<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\Backend\LiveLesson\TeacherMeetingSlotMail;
use App\Mail\Frontend\StudentMeetingMail;
use App\Models\Course;
use App\Models\CourseStudentZoom;
use App\Models\CourseStudentZoomBooking;
use App\Models\Lesson;
use App\Models\LiveLesson;
use App\Models\LiveLessonSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use MacsiDigital\Zoom\Facades\Zoom;
use Yajra\DataTables\Facades\DataTables;

class CourseStudentZoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 3);
        })->get()->pluck('name', 'id');
        return view('backend.course-user-zoom.index', compact('students'));
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
        $courseStudentZooms = "";
        $courseStudentZooms = CourseStudentZoom::all();

        if ($request->show_deleted == 1) {
            $courseStudentZooms = CourseStudentZoom::query()->with('course')->orderBy('created_at', 'desc')->onlyTrashed();
        }


//        if (auth()->user()->can('live_lesson_slot_view')) {
            $has_view = true;
//        }
//        if (auth()->user()->can('live_lesson_slot_edit')) {
            $has_edit = true;
//        }
//        if (auth()->user()->can('live_lesson_slot_delete')) {
            $has_delete = true;
//        }

        return DataTables::of($courseStudentZooms)
            ->addIndexColumn()
            ->addColumn('actions', function ($courseStudentZoom) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.course-user-zoom', 'label' => 'id', 'value' => $courseStudentZoom->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.course-user-zoom.show', $courseStudentZoom)])->render();
                }
                if ($courseStudentZoom->start_at->timezone(config('zoom.timezone'))->gt(Carbon::now(new \DateTimeZone(config('zoom.timezone'))))) {
                    if ($has_edit) {
                        $edit = view('backend.datatable.action-edit')
                            ->with(['route' => route('admin.course-user-zoom.edit', $courseStudentZoom)])
                            ->render();
                        $view .= $edit;
                    }
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.course-user-zoom.destroy', [$courseStudentZoom, 'course_user_zoom_id'=> $courseStudentZoom->id])])
                        ->render();
                    $view .= $delete;
                }

                return $view;
            })
            ->editColumn('start_at', function ($courseStudentZoom) {
                return $courseStudentZoom->start_at->format('d-m-Y h:i:s A');
            })
            ->editColumn('start_url', function ($courseStudentZoom) {
                if ($courseStudentZoom->start_at->timezone(config('zoom.timezone'))->lt(Carbon::now(new \DateTimeZone(config('zoom.timezone'))))) {
                    return '<a href="#" class="btn btn-warning btn-block mb-1 text-white">'.trans('labels.backend.live_lesson_slots.closed').'</a>';
                } else {
                    return '<a href="' . $courseStudentZoom->start_url . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.live_lesson_slots.start_url') . '</a>';
                }
            })
            ->addColumn('course', function ($courseStudentZoom){
                return ($courseStudentZoom->course) ? $courseStudentZoom->course->title : 'N/A';
            })
            ->rawColumns(['actions','start_url','course'])
            ->make();
    }

    public function precreate(Request $request)
    {
        $courses = Course::has('category')->ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');
        return view('backend.course-user-zoom.pre-create', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $course = '';
        $students = [];
        $courseStudentZoom = '';
        if($request->input('course')){
            $course = Course::findOrFail($request->course);
            $students = $course->students()->get()->pluck('name', 'id');
        }
        return view('backend.course-user-zoom.create', compact('students','course', 'courseStudentZoom'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'topic' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'duration' => 'required',
            'password' => 'required',
            'students' => 'required',
        ]);

        $meeting = $this->meetingCreateOrUpdate($request);

        $saveField = [
            'course_id' => $request->course_id,
            'meeting_id' => $meeting->id,
            'topic' => $request->topic,
            'description' => $request->description,
            'start_at' => $request->start_at,
            'duration' => $request->duration,
            'password' => $request->password,
            'start_url' => $meeting->start_url,
            'join_url'=> $meeting->join_url,
//            'student_limit' => $request->student_limit,
        ];

        $courseZoom = CourseStudentZoom::create($saveField);

        $students = array_filter((array)$request->input('students'));

        if ($courseZoom != null) {
            foreach($students as $stud) {
                if (CourseStudentZoomBooking::where('course_id', $request->course_id)->where('course_student_zoom_id', $courseZoom->id)->where('user_id', $stud)->count() == 0) {

                    \Log::info('stud: ' . $stud);
                    CourseStudentZoomBooking::create(
                        ['course_id' => $request->course_id, 'course_student_zoom_id' => $courseZoom->id, 'user_id' => $stud]
                    );
                }
            }
        }
        dispatch(function () use ($courseZoom) {
            \Log::info('dispatch '. json_encode($courseZoom));
//            Mail::to('taylor@example.com')->send(new WelcomeMessage);
            $this->meetingMail($courseZoom);
        })->afterResponse();


        return redirect()->route('admin.course-user-zoom.index', ['course_id' => $request->course_id])->withFlashSuccess(__('alerts.backend.general.created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LiveLessonSlot  $courseStudentZoom
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        if(!Gate::allows('live_lesson_slot_view')){
//            return abort(401);
//        }
        $courseStudentZoom = CourseStudentZoom::findOrFail($id);

        return view('backend.course-user-zoom.show', compact('courseStudentZoom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseStudentZoom  $courseStudentZoom
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        if(!Gate::allows('live_lesson_slot_edit')){
//            return abort(401);
//        }
        $lessons = Lesson::ofTeacher()->get()->pluck('title', 'id')->prepend('Please select', '');
        $courseStudentZoom = CourseStudentZoom::findOrFail($id);

        $course = '';
        $students = [];
        $selected = [];
        if($courseStudentZoom != null){
            $course = Course::findOrFail($courseStudentZoom->course->id);
            $students = $course->students()->get()->pluck('name', 'id');

            $selected = $courseStudentZoom->zoomSlotBookings->pluck('user_id');
        }

        return view('backend.course-user-zoom.edit', compact('students', 'course','courseStudentZoom', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseStudentZoom  $courseStudentZoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseStudentZoom $courseStudentZoom)
    {
//        if(!Gate::allows('live_lesson_slot_edit')){
//            return abort(401);
//        }

        \Log::info($request->all());
        $request->validate([
            'course_id' => 'required',
            'topic' => 'required',
            'description' => 'required',
            'start_at' => 'required',
            'duration' => 'required',
            'password' => 'required',
            'students' => 'required',
        ]);

        $courseStudentZoom = CourseStudentZoom::findOrFail($request->course_user_zoom_id);

        $selected = $courseStudentZoom->zoomSlotBookings->pluck('user_id');

        $meeting = $this->meetingCreateOrUpdate($request, true, $courseStudentZoom->meeting_id);

        $saveField = [
            'course_id' => $request->course_id,
            'meeting_id' => $meeting->id,
            'topic' => $request->topic,
            'description' => $request->description,
            'start_at' => $request->start_at,
            'duration' => $request->duration,
            'password' => $request->password,
            'start_url' => $meeting->start_url,
            'join_url'=> $meeting->join_url,
//            'student_limit' => $request->student_limit,
        ];

        $courseStudentZoom->update($saveField);

        //store

        $students = array_filter((array)$request->input('students'));

        $courseStudentBook = CourseStudentZoomBooking::where('course_id', $request->course_id)->where('course_student_zoom_id', $courseStudentZoom->id)->whereIn('user_id', $selected);

        \Log::info(json_encode($courseStudentBook));
        // DELETE REMOVE
        foreach ($selected as $prev) {
            $exist = false;
            foreach($students as $stud) {
                \Log::info($prev. ' : ' . $stud);
                if($prev == $stud){
                    $exist = true;
                }
            }

            if(!$exist) {
                $existBooking = CourseStudentZoomBooking::where('course_id', $request->course_id)->where('course_student_zoom_id', $courseStudentZoom->id)->where('user_id', $prev);
                $existBooking->forceDelete();
            }
        }

        //INSERT NEW

        foreach($students as $stud) {
            $exist = false;
            foreach ($selected as $prev) {
                \Log::info($prev. ' : ' . $stud);
                if($prev == $stud){
                    $exist = true;
                }
            }

            if(!$exist) {
                if (CourseStudentZoomBooking::where('course_id', $request->course_id)->where('course_student_zoom_id', $courseStudentZoom->id)->where('user_id', $stud)->count() == 0) {

                    \Log::info('stud: ' . $stud);
                    CourseStudentZoomBooking::create(
                        ['course_id' => $request->course_id, 'course_student_zoom_id' => $courseStudentZoom->id, 'user_id' => $stud]
                    );
                }
            }

        }

        dispatch(function () use ($courseStudentZoom) {
            $this->meetingMail($courseStudentZoom);
        })->afterResponse();

        return redirect()->route('admin.course-user-zoom.index')->withFlashSuccess(__('alerts.backend.general.updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseStudentZoom  $courseStudentZoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CourseStudentZoom $courseStudentZoom)
    {
//        if(!Gate::allows('live_lesson_slot_delete')){
//            return abort(401);
//        }
        $courseStudentZoom = CourseStudentZoom::findOrFail($request->course_user_zoom_id);

        $meeting = Zoom::meeting()->find($courseStudentZoom->meeting_id);
        if($meeting != null) {
            $meeting->delete();
        }
        $courseStudentZoom->zoomSlotBookings()->forceDelete();
        $courseStudentZoom->forceDelete();
        return redirect()->route('admin.course-user-zoom.index')->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    private function meetingCreateOrUpdate(Request $request, $update = false, $meetingId = null)
    {
        $user = Zoom::user()->get()->first();
        $meetingData = [
            'topic' => $request->topic,
            'type' => 2,
            'agenda' => $request->description,
            'duration' => $request->duration,
            'password' => $request->password,
            'start_time' => $request->start_at,
            'timezone' => config('zoom.timezone')
        ];

        if($update){
            $meeting = Zoom::meeting()->find($meetingId);
            $meeting->update($meetingData);
        }else {
            $meeting = Zoom::meeting()->make($meetingData);
        }

        $meeting->settings()->make([
            'join_before_host' => $request->change_default_setting ? $request->join_before_host ? true: false : config('zoom.join_before_host')? true: false,
            'host_video' => $request->change_default_setting ? $request->host_video ? true: false : config('zoom.host_video') ? true : false,
            'participant_video' => $request->change_default_setting ? $request->participant_video ? true: false : config('zoom.participant_video') ? true : false,
            'mute_upon_entry' => $request->change_default_setting ? $request->participant_mic_mute ? true: false : config('zoom.mute_upon_entry') ? true : false,
            'waiting_room' => $request->change_default_setting ? $request->waiting_room ? true: false : config('zoom.waiting_room') ? true : false,
            'approval_type' => $request->change_default_setting ? $request->approval_type : config('zoom.approval_type'),
            'audio' => $request->change_default_setting ? $request->audio_option : config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording')
        ]);

        return $user->meetings()->save($meeting);
    }

    private function makeRegistrant($meeting) {
        $registrant = Zoom::meetingRegistrant()->make([
            'email' => 'ssbing99@gmail.com',
            'first_name' => 'Test',
            'last_name' => 'Registrant'
        ]);

        return $meeting->registrants()->save($registrant);
    }

    private function meetingMail(CourseStudentZoom $courseStudentZoom)
    {
        foreach ($courseStudentZoom->zoomSlotBookings as $booking){
            \Log::info('sendMail: '.$booking->user->email);
            $content = [
                'name' => $booking->user->full_name,
                'course' => $courseStudentZoom->course->title,
                'meeting_id' => $courseStudentZoom->meeting_id,
                'password' => $courseStudentZoom->password,
                'start_at' => $courseStudentZoom->start_at,
                'start_url' => $courseStudentZoom->start_url

            ];
            \Mail::to($booking->user->email)->send(new StudentMeetingMail($content));
        }
    }
}
