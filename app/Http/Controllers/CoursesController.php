<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;

class CoursesController extends Controller
{

    private $path;

    public function __construct()
    {
        $path = 'frontend';
        if(session()->has('display_type')){
            if(session('display_type') == 'rtl'){
                $path = 'frontend-rtl';
            }else{
                $path = 'frontend';
            }
        }else if(config('app.display_type') == 'rtl'){
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }

    public function all(Request $request)
    {
        $paginateCnt = 100; // so far not yet have paginate , original 9
        if (request('type') == 'popularity') {
                $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('mentorship', 0)->where('portfolio_review', 0)
//                    ->where('popular', '=', 1)
//                    ->where(function ($query) use ($request) {
//                        $isPast = request('filter') == 'past';
//                        $isUpcoming = request('filter') == 'upcoming';
//                        if($isPast)
//                            $query->where('start_date', '<', Carbon::today());
//                        if($isUpcoming)
//                            $query->where('start_date', '>', Carbon::today());
//
//                    })
                    ->orderBy('popular', 'asc')
                    ->orderBy('trending', 'asc')
                    ->orderBy('featured', 'asc')
                    ->paginate($paginateCnt);

        } else if (request('type') == 'price') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('mentorship', 0)->where('portfolio_review', 0)
//                ->where('trending', '=', 1)
//                ->where(function ($query) use ($request) {
//                    $isPast = request('filter') == 'past';
//                    $isUpcoming = request('filter') == 'upcoming';
//                    if($isPast)
//                        $query->where('start_date', '<', Carbon::today());
//                    if($isUpcoming)
//                        $query->where('start_date', '>', Carbon::today());
//
//                })
                ->orderBy('price', 'asc')->paginate($paginateCnt);

        } else if (request('type') == 'duration') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('mentorship', 0)->where('portfolio_review', 0)
//                ->where('featured', '=', 1)
//                ->where(function ($query) use ($request) {
//                    $isPast = request('filter') == 'past';
//                    $isUpcoming = request('filter') == 'upcoming';
//                    if($isPast)
//                        $query->where('start_date', '<', Carbon::today());
//                    if($isUpcoming)
//                        $query->where('start_date', '>', Carbon::today());
//
//                })
                ->orderBy('duration', 'asc')->paginate($paginateCnt);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('mentorship', 0)->where('portfolio_review', 0)
//                ->where(function ($query) use ($request) {
//                    $isPast = request('filter') == 'past';
//                    $isUpcoming = request('filter') == 'upcoming';
//                    if($isPast)
//                        $query->where('start_date', '<', Carbon::today());
//                    if($isUpcoming)
//                        $query->where('start_date', '>', Carbon::today());
//
//                })
                ->orderBy('id', 'desc')->paginate($paginateCnt);

        }

        $courses_json = new Collection();
        foreach ($courses as $course){
            $courses_json->push($course);
        }
        $courses_json = json_encode($courses_json);

        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        $categories = Category::where('status','=',1)->get();

        if (\Auth::check()) {
            $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }
        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $view_path = returnPathByTheme($this->path.'.courses.index', 5,'-');

        return view( $view_path, compact('courses','courses_json', 'purchased_courses', 'recent_news','featured_courses','categories'));
    }

    public function allReviews(Request $request)
    {
        $paginateCnt = 100; // so far not yet have paginate , original 9
        if (request('type') == 'popularity') {
                $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                    ->where('portfolio_review', '=', 1)
                    ->paginate($paginateCnt);

        } else if (request('type') == 'price') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('portfolio_review', '=', 1)
                ->orderBy('price', 'asc')->paginate($paginateCnt);

        } else if (request('type') == 'duration') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('portfolio_review', '=', 1)
                ->orderBy('duration', 'asc')->paginate($paginateCnt);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('portfolio_review', '=', 1)
                ->orderBy('id', 'desc')->paginate($paginateCnt);

        }

        $courses_json = new Collection();
        foreach ($courses as $course){
            $courses_json->push($course);
        }
        $courses_json = json_encode($courses_json);

        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        $categories = Category::where('status','=',1)->get();

        if (\Auth::check()) {
            $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }
        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $view_path = returnPathByTheme($this->path.'.courses.review', 5,'-');

        return view( $view_path, compact('courses','courses_json', 'purchased_courses', 'recent_news','featured_courses','categories'));
    }

    public function show($course_slug)
    {
        if(auth()->check()) {
            Cart::session(auth()->user()->id)->clear();
        }
        $continue_course=NULL;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        if(($course->published == 0) && ($purchased_course == false)){
            abort(404);
        }
        $course_rating = 0;
        $course_progress_perc = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $course_lessons = $course->lessons->pluck('id')->toArray();
            $continue_course  = $course->courseTimeline()
                ->whereIn('model_id',$course_lessons)
                ->orderby('sequence','asc')
                ->whereNotIn('model_id',$completed_lessons)

                ->first();
            if($continue_course == null){
                $continue_course = $course->courseTimeline()
                    ->whereIn('model_id',$course_lessons)
                    ->orderby('sequence','asc')->first();
            }

            if(count($lessons) > 0)
                $course_progress_perc = (count($completed_lessons) / count($lessons)) * 100;
        }

        $view_path = returnPathByTheme($this->path.'.courses.course', 5,'-');

        return view( $view_path, compact('course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course', 'course_progress_perc'));
    }

    public function teacherShow($id)
    {
        $course = Course::withoutGlobalScope('filter')->withTrashed()->where('id', $id)->firstOrFail();

        $view_path = returnPathByTheme($this->path.'.courses.course-view', 5,'-');

        return view( $view_path, compact('course'));
    }

    public function userCourse($id)
    {
        $student = User::find($id);
        $courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();

        $view_path = returnPathByTheme($this->path.'.student.course-detail', 5,'-');

        return view( $view_path, compact('student','courses'));
    }

    public function reviewShow($course_slug)
    {
        $continue_course=NULL;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $course = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        if(($course->published == 0) && ($purchased_course == false)){
            abort(404);
        }
        $course_rating = 0;
        $course_progress_perc = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $course_lessons = $course->lessons->pluck('id')->toArray();
            $continue_course  = $course->courseTimeline()
                ->whereIn('model_id',$course_lessons)
                ->orderby('sequence','asc')
                ->whereNotIn('model_id',$completed_lessons)

                ->first();
            if($continue_course == null){
                $continue_course = $course->courseTimeline()
                    ->whereIn('model_id',$course_lessons)
                    ->orderby('sequence','asc')->first();
            }

            if(count($lessons) > 0)
                $course_progress_perc = (count($completed_lessons) / count($lessons)) * 100;
        }

        $view_path = returnPathByTheme($this->path.'.courses.add-review','','');

        return view( $view_path, compact('course', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course', 'course_progress_perc'));
    }


    public function rating($course_id, Request $request)
    {
        $course = Course::findOrFail($course_id);
        $course->students()->updateExistingPivot(\Auth::id(), ['rating' => $request->get('rating')]);

        return redirect()->back()->with('success', 'Thank you for rating.');
    }

    public function getByCategory(Request $request)
    {
        $category = Category::where('slug', '=', $request->category)
            ->where('status','=',1)
            ->first();
        $categories = Category::where('status','=',1)->get();

        if ($category != "") {
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            if (request('type') == 'popular') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)
                    ->where('popular', '=', 1)
                    ->where(function ($query) use ($request) {
                        $isPast = request('filter') == 'past';
                        $isUpcoming = request('filter') == 'upcoming';
                        if($isPast)
                            $query->where('start_date', '<', Carbon::today());
                        if($isUpcoming)
                            $query->where('start_date', '>', Carbon::today());

                    })
                    ->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'trending') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)
                    ->where('trending', '=', 1)
                    ->where(function ($query) use ($request) {
                        $isPast = request('filter') == 'past';
                        $isUpcoming = request('filter') == 'upcoming';
                        if($isPast)
                            $query->where('start_date', '<', Carbon::today());
                        if($isUpcoming)
                            $query->where('start_date', '>', Carbon::today());

                    })
                    ->orderBy('id', 'desc')->paginate(9);

            } else if (request('type') == 'featured') {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)
                    ->where('featured', '=', 1)
                    ->where(function ($query) use ($request) {
                        $isPast = request('filter') == 'past';
                        $isUpcoming = request('filter') == 'upcoming';
                        if($isPast)
                            $query->where('start_date', '<', Carbon::today());
                        if($isUpcoming)
                            $query->where('start_date', '>', Carbon::today());

                    })
                    ->orderBy('id', 'desc')->paginate(9);

            } else {
                $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)
                    ->where(function ($query) use ($request) {
                        $isPast = request('filter') == 'past';
                        $isUpcoming = request('filter') == 'upcoming';
                        if($isPast)
                            $query->where('start_date', '<', Carbon::today());
                        if($isUpcoming)
                            $query->where('start_date', '>', Carbon::today());

                    })
                    ->orderBy('id', 'desc')->paginate(9);

            }

            $view_path = returnPathByTheme($this->path.'.courses.index', 5,'-');

            return view( $view_path, compact('courses', 'category', 'recent_news','featured_courses','categories'));
        }
        return abort(404);
    }

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = Course::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = Course::class;
        $review->rating = $request->rating;
        $review->content = $request->review;
        $review->save();

        Session::flash('success', 'Thanks for your review.');
        return back();
    }

    public function editReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $course = $review->reviewable;
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            $course_rating = 0;
            $course_progress_perc = 0;
            $total_ratings = 0;
            $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
            if (\Auth::check()) {

                $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
                $continue_course  = $course->courseTimeline()->orderby('sequence','asc')->whereNotIn('model_id',$completed_lessons)->first();
                if($continue_course == ""){
                    $continue_course = $course->courseTimeline()->orderby('sequence','asc')->first();
                }

                if(count($lessons) > 0)
                    $course_progress_perc = (count($completed_lessons) / count($lessons)) * 100;

            }

            $view_path = returnPathByTheme($this->path.'.courses.course', 5,'-');

            return view( $view_path, compact('course', 'purchased_course', 'recent_news','completed_lessons','continue_course', 'course_rating', 'total_ratings','lessons', 'review','course_progress_perc'));
        }
        return abort(404);

    }


    public function updateReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();

            return redirect()->route('courses.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('courses.show', ['slug' => $slug]);
        }
        return abort(404);
    }
}
