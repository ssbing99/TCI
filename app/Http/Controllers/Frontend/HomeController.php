<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Config;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Faq;
use App\Models\Item;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\Reason;
use App\Models\Sponsor;
use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Newsletter;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

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

    public function index()
    {
        if (request('page')) {
            $page = Page::where('slug', '=', request('page'))
                ->where('published', '=', 1)->first();
            if ($page != "") {
                return view($this->path . '.pages.index', compact('page'));
            }
            abort(404);
        }
        $type = config('theme_layout');
        $sections = Config::where('key', '=', 'layout_' . $type)->first();
        $sections = json_decode($sections->value);

        $beginner = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
            ->where('beginner', '=', 1)
            ->take(2)->get();
        $intermediate = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
            ->where('intermediate', '=', 1)
            ->take(2)->get();
        $advance = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
            ->where('advance', '=', 1)
            ->take(2)->get();

        $totalCourse = 2;

        if($beginner == null && $intermediate == null && $advance == null)
            $totalCourse = 8;

        $popular_courses = new Collection(Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
//            ->where('popular', '=', 1)
            ->take($totalCourse)->get());

        if($beginner != null) {
            foreach ($beginner as $b){
                $canMerge = true;
                foreach ($popular_courses as $p){
                    if($p->id == $b->id)
                        $canMerge = false;
                }

                if($canMerge)
                    $popular_courses->push($b);
            }
        }

        if($intermediate != null) {
            foreach ($intermediate as $b){
                $canMerge = true;
                foreach ($popular_courses as $p){
                    if($p->id == $b->id)
                        $canMerge = false;
                }

                if($canMerge)
                    $popular_courses->push($b);
            }
        }
        if($advance != null) {
            foreach ($advance as $b){
                $canMerge = true;
                foreach ($popular_courses as $p){
                    if($p->id == $b->id)
                        $canMerge = false;
                }

                if($canMerge)
                    $popular_courses->push($b);
            }
        }

        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->whereHas('category')
            ->where('featured', '=', 1)->take(8)->get();

        $course_categories = Category::with('courses')->where('icon', '!=', "")->take(12)->get();

        $trending_courses = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
            ->where('trending', '=', 1)->take(2)->get();

        $teachers = User::role('teacher')->with('courses')->where('active', '=', 1)->take(4)->get();

        $sponsors = Sponsor::where('status', '=', 1)->get();

        $news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $faqs = Category::with('faqs')->get()->take(6);

        $testimonials = Testimonial::where('status', '=', 1)->orderBy('created_at', 'desc')->get();

        $reasons = Reason::where('status', '=', 1)->orderBy('created_at', 'desc')->get();

        if ((int)config('counter') == 1) {
            $total_students = config('total_students');
            $total_courses = config('total_courses');
            $total_teachers = config('total_teachers');
        } else {
            $total_course = Course::where('published', '=', 1)->get()->count();
            $total_bundle = Bundle::where('published', '=', 1)->get()->count();
            $total_students = User::role('student')->get()->count();
            $total_courses = $total_course + $total_bundle;
            $total_teachers = User::role('teacher')->get()->count();
        }

        $categories = Category::get();
        return view($this->path . '.index-' . config('theme_layout'), compact('popular_courses', 'featured_courses', 'sponsors', 'total_students', 'total_courses', 'total_teachers', 'testimonials', 'news', 'trending_courses', 'teachers', 'faqs', 'course_categories', 'reasons', 'sections','categories'));
    }

    public function getFaqs()
    {
        $faq_categories = Category::has('faqs', '>', 0)->get();
        return view($this->path . '.faq', compact('faq_categories'));
    }

    public function howItWork()
    {
        $faq_categories = Category::has('faqs', '>', 0)->get();
        return view($this->path . '.how-it-work', compact('faq_categories'));
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'subs_email' => 'required'
        ]);

        if (config('mail_provider') != "" && config('mail_provider') == "mailchimp") {
            try {
                if (!Newsletter::isSubscribed($request->subs_email)) {
                    if (config('mailchimp_double_opt_in')) {
                        Newsletter::subscribePending($request->subs_email);
                        session()->flash('alert', "We've sent you an email, Check your mailbox for further procedure.");
                    } else {
                        Newsletter::subscribe($request->subs_email);
                        session()->flash('alert', "You've subscribed successfully");
                    }
                    return back();
                } else {
                    session()->flash('alert', "Email already exist in subscription list");
                    return back();

                }
            } catch (Exception $e) {
                \Log::info($e->getMessage());
                session()->flash('alert', "Something went wrong, Please try again Later");
                return back();
            }

        } elseif (config('mail_provider') != "" && config('mail_provider') == "sendgrid") {
            try {
                $apiKey = config('sendgrid_api_key');
                $sg = new \SendGrid($apiKey);
                $query_params = json_decode('{"page": 1, "page_size": 1}');
                $response = $sg->client->contactdb()->recipients()->get(null, $query_params);
                if ($response->statusCode() == 200) {
                    $users = json_decode($response->body());
                    $emails = [];
                    foreach ($users->recipients as $user) {
                        array_push($emails, $user->email);
                    }
                    if (in_array($request->subs_email, $emails)) {
                        session()->flash('alert', "Email already exist in subscription list");
                        return back();
                    } else {
                        $request_body = json_decode(
                            '[{
                             "email": "' . $request->subs_email . '",
                             "first_name": "",
                             "last_name": ""
                              }]'
                        );
                        $response = $sg->client->contactdb()->recipients()->post($request_body);
                        if ($response->statusCode() != 201 || (json_decode($response->body())->new_count == 0)) {

                            session()->flash('alert', "Email already exist in subscription list");
                            return back();
                        } else {
                            $recipient_id = json_decode($response->body())->persisted_recipients[0];
                            $list_id = config('sendgrid_list');
                            $response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->_($recipient_id)->post();
                            if ($response->statusCode() == 201) {
                                session()->flash('alert', "You've subscribed successfully");
                            } else {
                                session()->flash('alert', "Check your email and try again");
                                return back();
                            }

                        }
                    }
                }
            } catch (Exception $e) {
                \Log::info($e->getMessage());
                session()->flash('alert', "Something went wrong, Please try again Later");
                return back();
            }
        }else{
            session()->flash('alert', "Please configure Newsletter from Admin");
            return back();
        }


    }

    public function getTeachers()
    {
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $teachers = User::role('teacher')->paginate(12);
        return view($this->path . '.teachers.index', compact('teachers', 'recent_news'));
    }

    public function showTeacher(Request $request)
    {
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $teacher = User::role('teacher')->where('id', '=', $request->id)->first();
        $teachers = User::role('teacher')->with('courses')->where('active', '=', 1)->take(4)->get();
        $courses = $teacher->courses;
        if (count($teacher->courses) > 0) {
            $courses = $teacher->courses()->paginate(12);
        }

        $view_path = returnPathByTheme($this->path . '.teachers.show', 5,'-');

        return view($view_path, compact('teacher', 'recent_news', 'courses', 'teachers'));
    }

    public function getDownload(Request $request)
    {
        if (auth()->check()) {
            $lesson = Lesson::findOrfail($request->lesson);
            $course_id = $lesson->course_id;
            $course = Course::findOrfail($course_id);
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            if ($purchased_course) {
                $file = public_path() . "/storage/uploads/" . $request->filename;

                return Response::download($file);
            }
            return abort(404);

        }
        return abort(404);

    }

    public function searchAll(Request $request)
    {

        $courses = $this->searchCourses($request);

        $storeItems = $this->searchItems($request);

        $blogs = $this->searchBlogs($request);


//        $consolidateItems = new Collection();
//        $consolidateItems = $consolidateItems->concat($courses);
//        $consolidateItems = $consolidateItems->concat($storeItems);
//        $consolidateItems = $consolidateItems->concat($blogs);
//
//        $consolidate = $consolidateItems->paginate(12);

        $q = $request->q;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $categories = Category::where('status', '=', 1)->get();

        $view_path = returnPathByTheme($this->path.'.search-result.search-all', 5,'-');

        return view($view_path, compact('courses', 'storeItems', 'blogs', 'q', 'recent_news','categories'));

    }

    public function searchCourse(Request $request)
    {

        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
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
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
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
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('featured', '=', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->paginate(12);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->paginate(12);

        }

        if ($request->category != null) {
            $category = Category::find((int)$request->category);
            if($category){
                $ids = $category->courses->pluck('id')->toArray();
                $types = ['popular', 'trending', 'featured'];
                if ($category) {
                    if (in_array(request('type'), $types)) {
                        $type = request('type');
                        $courses = $category->courses()->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                            $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                        })
                            ->where(function ($query) use ($request) {

                                $isPast = request('filter') == 'past';
                                $isUpcoming = request('filter') == 'upcoming';
                                if($isPast)
                                    $query->where('start_date', '<', Carbon::today());
                                if($isUpcoming)
                                    $query->where('start_date', '>', Carbon::today());
                            })
                            ->whereIn('id', $ids)
                            ->where('published', '=', 1)
                            ->where($type, '=', 1)
                            ->paginate(12);
                    } else {
                        $courses = $category->courses()
                            ->where(function ($query) use ($request) {
                                $query->where('title', 'LIKE', '%' . $request->q . '%');
                                $query->orWhere('description', 'LIKE', '%' . $request->q . '%');

                            })
                            ->where(function ($query) use ($request) {

                                $isPast = request('filter') == 'past';
                                $isUpcoming = request('filter') == 'upcoming';
                                if($isPast)
                                    $query->where('start_date', '<', Carbon::today());
                                if($isUpcoming)
                                    $query->where('start_date', '>', Carbon::today());
                            })
                            ->where('published', '=', 1)
                            ->whereIn('id', $ids)
                            ->paginate(12);
                    }

                }

            }


        } else {
            $courses = Course::withoutGlobalScope('filter')
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->q . '%');
                    $query->orWhere('description', 'LIKE', '%' . $request->q . '%');

                })
                ->where('published', '=', 1)
                ->paginate(12);


        }

        $categories = Category::where('status', '=', 1)->get();


        $q = $request->q;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $view_path = $this->path.'.search-result.courses';

        if(config('theme_layout') == 5)
            $view_path = $this->path.'.search-result.courses'.config('theme_layout');

        return view($view_path, compact('courses', 'q', 'recent_news', 'categories'));
    }


    public function searchBundle(Request $request)
    {

        if (request('type') == 'popular') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'trending') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'featured') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(12);
        }


        if ($request->category != null) {
            $category = Category::find((int)$request->category);
            $ids = $category->bundles->pluck('id')->toArray();
            $types = ['popular', 'trending', 'featured'];
            if ($category) {

                if (in_array(request('type'), $types)) {
                    $type = request('type');
                    $bundles = $category->bundles()->where(function ($query) use ($request) {
                        $query->where('title', 'LIKE', '%' . $request->q . '%');
                        $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                    })
                        ->whereIn('id', $ids)
                        ->where('published', '=', 1)
                        ->where($type, '=', 1)
                        ->paginate(12);
                } else {
                    $bundles = $category->bundles()
                        ->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                            $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                        })
                        ->where('published', '=', 1)
                        ->whereIn('id', $ids)
                        ->paginate(12);
                }

            }

        } else {
            $bundles = Bundle::where('title', 'LIKE', '%' . $request->q . '%')
                ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                ->where('published', '=', 1)
                ->paginate(12);

        }

        $categories = Category::where('status', '=', 1)->get();


        $q = $request->q;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        return view($this->path . '.search-result.bundles', compact('bundles', 'q', 'recent_news', 'categories'));
    }

    public function searchBlog(Request $request)
    {
        $blogs = Blog::where('title', 'LIKE', '%' . $request->q . '%')
            ->paginate(12);
        $categories = Category::has('blogs')->where('status', '=', 1)->paginate(10);
        $popular_tags = Tag::has('blogs', '>', 4)->get();
        $category = null;
        if ($request->category != null) {

            $category = Category::where('slug', '=', str_slug($request->category))->first();

            if($category){
                $ids = $category->blogs->pluck('id')->toArray();
                if ($category) {

                    $blogs = $category->blogs()
                        ->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                        })
                        ->whereIn('id', $ids)
                        ->paginate(12);

                }

            }


        } else {
            $blogs = Blog::where('title', 'LIKE', '%' . $request->q . '%')
                ->paginate(12);

        }

        $q = $request->q;

        $view_path = returnPathByTheme($this->path.'.search-result.blogs', 5,'');

        return view($view_path, compact('blogs', 'q', 'categories', 'popular_tags','category'));
    }

    public function searchCourses(Request $request){
        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('popular', '=', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->get();
//            ->paginate(9);

        } else if (request('type') == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('trending', '=', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->get();
//            ->paginate(9);

        } else if (request('type') == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where('featured', '=', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->get();
//            ->paginate(12);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->orderBy('id', 'desc')->get();
//            ->paginate(12);

        }

        if ($request->category != null) {
            $category = Category::find((int)$request->category);
            if($category){
                $ids = $category->courses->pluck('id')->toArray();
                $types = ['popular', 'trending', 'featured'];
                if ($category) {
                    if (in_array(request('type'), $types)) {
                        $type = request('type');
                        $courses = $category->courses()->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                            $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                        })
                            ->where(function ($query) use ($request) {

                                $isPast = request('filter') == 'past';
                                $isUpcoming = request('filter') == 'upcoming';
                                if($isPast)
                                    $query->where('start_date', '<', Carbon::today());
                                if($isUpcoming)
                                    $query->where('start_date', '>', Carbon::today());
                            })
                            ->whereIn('id', $ids)
                            ->where('published', '=', 1)
                            ->where($type, '=', 1)->get();
//                            ->paginate(12);
                    } else {
                        $courses = $category->courses()
                            ->where(function ($query) use ($request) {
                                $query->where('title', 'LIKE', '%' . $request->q . '%');
                                $query->orWhere('description', 'LIKE', '%' . $request->q . '%');

                            })
                            ->where(function ($query) use ($request) {

                                $isPast = request('filter') == 'past';
                                $isUpcoming = request('filter') == 'upcoming';
                                if($isPast)
                                    $query->where('start_date', '<', Carbon::today());
                                if($isUpcoming)
                                    $query->where('start_date', '>', Carbon::today());
                            })
                            ->where('published', '=', 1)
                            ->whereIn('id', $ids)->get();
//                            ->paginate(12);
                    }

                }

            }


        } else {
            $courses = Course::withoutGlobalScope('filter')
                ->where(function ($query) use ($request) {
                    $isPast = request('filter') == 'past';
                    $isUpcoming = request('filter') == 'upcoming';
                    if($isPast)
                        $query->where('start_date', '<', Carbon::today());
                    if($isUpcoming)
                        $query->where('start_date', '>', Carbon::today());

                })
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->q . '%');
                    $query->orWhere('description', 'LIKE', '%' . $request->q . '%');

                })
                ->where('published', '=', 1)->get();
//                ->paginate(12);


        }

        return $courses;
    }

    public function searchItems(Request $request){

        $items = Item::where('title', 'LIKE', '%' . $request->q . '%')
            ->orWhere('description', 'LIKE', '%' . $request->q . '%')
            ->where('published', '=', 1)->get();
//            ->paginate(12);

        return $items;
    }

    public function searchBlogs(Request $request){

        $blogs = Blog::where('title', 'LIKE', '%' . $request->q . '%')->get();
//            ->paginate(12);
        $category = null;
        if ($request->category != null) {

            $category = Category::where('slug', '=', str_slug($request->category))->first();

            if($category){
                $ids = $category->blogs->pluck('id')->toArray();
                if ($category) {

                    $blogs = $category->blogs()
                        ->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                        })
                        ->whereIn('id', $ids)->get();
//                        ->paginate(12);

                }

            }


        } else {
            $blogs = Blog::where('title', 'LIKE', '%' . $request->q . '%')->get();
//                ->paginate(12);

        }

        return $blogs;
    }
}

