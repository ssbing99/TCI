<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Workshop;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;

class WorkshopsController extends Controller
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
                $workshops = Workshop::withoutGlobalScope('filter')->where('published', 1)
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
//                    ->orderBy('popular', 'asc')
//                    ->orderBy('trending', 'asc')
//                    ->orderBy('featured', 'asc')
                    ->paginate($paginateCnt);

        } else if (request('type') == 'price') {
            $workshops = Workshop::withoutGlobalScope('filter')->where('published', 1)
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
            $workshops = Workshop::withoutGlobalScope('filter')->where('published', 1)
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
            $workshops = Workshop::withoutGlobalScope('filter')->where('published', 1)
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

        $purchased_workshops = NULL;
        $purchased_bundles = NULL;

        if (\Auth::check()) {
            $purchased_workshops = Workshop::withoutGlobalScope('filter')->whereHas('students', function ($query) {
                $query->where('id', \Auth::id());
            })
                ->with('lessons')
                ->orderBy('id', 'desc')
                ->get();
        }
//        $featured_workshops = Workshop::withoutGlobalScope('filter')->where('published', '=', 1)
//            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $view_path = returnPathByTheme($this->path.'.workshops.index', 5,'-');

        return view( $view_path, compact('workshops','purchased_workshops', 'recent_news'));
    }

    public function show($workshop_slug)
    {
        $continue_workshop=NULL;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $workshop = Workshop::withoutGlobalScope('filter')->where('slug', $workshop_slug)->with('publishedLessons')->firstOrFail();
        $purchased_workshop = \Auth::check() && $workshop->students()->where('user_id', \Auth::id())->count() > 0;
        if(($workshop->published == 0) && ($purchased_workshop == false)){
            abort(404);
        }
        $workshop_rating = 0;
        $workshop_progress_perc = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        if(auth()->check() && $workshop->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        if ($workshop->reviews->count() > 0) {
            $workshop_rating = $workshop->reviews->avg('rating');
            $total_ratings = $workshop->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $workshop->workshopTimeline()->orderby('sequence','asc')->get();

        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('workshop_id', $workshop->id)->get()->pluck('model_id')->toArray();
            $workshop_lessons = $workshop->lessons->pluck('id')->toArray();
            $continue_workshop  = $workshop->workshopTimeline()
                ->whereIn('model_id',$workshop_lessons)
                ->orderby('sequence','asc')
                ->whereNotIn('model_id',$completed_lessons)

                ->first();
            if($continue_workshop == null){
                $continue_workshop = $workshop->workshopTimeline()
                    ->whereIn('model_id',$workshop_lessons)
                    ->orderby('sequence','asc')->first();
            }

            if(count($lessons) > 0)
                $workshop_progress_perc = (count($completed_lessons) / count($lessons)) * 100;
        }

        $view_path = returnPathByTheme($this->path.'.workshops.workshop', 5,'-');

        return view( $view_path, compact('workshop', 'purchased_workshop', 'recent_news', 'workshop_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_workshop', 'workshop_progress_perc'));
    }


    public function rating($workshop_id, Request $request)
    {
        $workshop = Workshop::findOrFail($workshop_id);
        $workshop->students()->updateExistingPivot(\Auth::id(), ['rating' => $request->get('rating')]);

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
            $featured_workshops = Workshop::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            if (request('type') == 'popular') {
                $workshops = $category->workshops()->withoutGlobalScope('filter')->where('published', 1)
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
                $workshops = $category->workshops()->withoutGlobalScope('filter')->where('published', 1)
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
                $workshops = $category->workshops()->withoutGlobalScope('filter')->where('published', 1)
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
                $workshops = $category->workshops()->withoutGlobalScope('filter')->where('published', 1)
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

            $view_path = returnPathByTheme($this->path.'.workshops.index', 5,'-');

            return view( $view_path, compact('workshops', 'category', 'recent_news','featured_workshops','categories'));
        }
        return abort(404);
    }

    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $workshop = Workshop::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $workshop->id;
        $review->reviewable_type = Workshop::class;
        $review->rating = $request->rating;
        $review->content = $request->review;
        $review->save();

        return back();
    }

    public function editReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $workshop = $review->reviewable;
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $purchased_workshop = \Auth::check() && $workshop->students()->where('user_id', \Auth::id())->count() > 0;
            $workshop_rating = 0;
            $workshop_progress_perc = 0;
            $total_ratings = 0;
            $lessons = $workshop->workshopTimeline()->orderby('sequence','asc')->get();

            if ($workshop->reviews->count() > 0) {
                $workshop_rating = $workshop->reviews->avg('rating');
                $total_ratings = $workshop->reviews()->where('rating', '!=', "")->get()->count();
            }
            if (\Auth::check()) {

                $completed_lessons = \Auth::user()->chapters()->where('workshop_id', $workshop->id)->get()->pluck('model_id')->toArray();
                $continue_workshop  = $workshop->workshopTimeline()->orderby('sequence','asc')->whereNotIn('model_id',$completed_lessons)->first();
                if($continue_workshop == ""){
                    $continue_workshop = $workshop->workshopTimeline()->orderby('sequence','asc')->first();
                }

                if(count($lessons) > 0)
                    $workshop_progress_perc = (count($completed_lessons) / count($lessons)) * 100;

            }

            $view_path = returnPathByTheme($this->path.'.workshops.workshop', 5,'-');

            return view( $view_path, compact('workshop', 'purchased_workshop', 'recent_news','completed_lessons','continue_workshop', 'workshop_rating', 'total_ratings','lessons', 'review','workshop_progress_perc'));
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

            return redirect()->route('workshops.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('workshops.show', ['slug' => $slug]);
        }
        return abort(404);
    }
}
