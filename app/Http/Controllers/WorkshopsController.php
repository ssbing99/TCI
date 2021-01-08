<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Workshop;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;

class WorkshopsController extends Controller
{

    private $path;
    private $currency;

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
        $this->currency = getCurrency(config('app.currency'));
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
        $workshop = Workshop::withoutGlobalScope('filter')->where('slug', $workshop_slug)->firstOrFail();
        $purchased_workshop = \Auth::check() && $workshop->students()->where('user_id', \Auth::id())->count() > 0;
        if(($workshop->published == 0) && ($purchased_workshop == false)){
            abort(404);
        }
        $workshop_rating = 0;
        $workshop_progress_perc = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
//        if(auth()->check() && $workshop->reviews()->where('user_id','=',auth()->user()->id)->first()){
//            $is_reviewed = true;
//        }
//        if ($workshop->reviews->count() > 0) {
//            $workshop_rating = $workshop->reviews->avg('rating');
//            $total_ratings = $workshop->reviews()->where('rating', '!=', "")->get()->count();
//        }
//        $lessons = $workshop->workshopTimeline()->orderby('sequence','asc')->get();

        if (\Auth::check()) {

//            $completed_lessons = \Auth::user()->chapters()->where('workshop_id', $workshop->id)->get()->pluck('model_id')->toArray();
//            $workshop_lessons = $workshop->lessons->pluck('id')->toArray();
//            $continue_workshop  = $workshop->workshopTimeline()
//                ->whereIn('model_id',$workshop_lessons)
//                ->orderby('sequence','asc')
//                ->whereNotIn('model_id',$completed_lessons)
//
//                ->first();
//            if($continue_workshop == null){
//                $continue_workshop = $workshop->workshopTimeline()
//                    ->whereIn('model_id',$workshop_lessons)
//                    ->orderby('sequence','asc')->first();
//            }
//
//            if(count($lessons) > 0)
//                $workshop_progress_perc = (count($completed_lessons) / count($lessons)) * 100;
        }

        $view_path = returnPathByTheme($this->path.'.workshops.show', 5,'-');

        return view( $view_path, compact('workshop', 'purchased_workshop', 'recent_news'));
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

    public function paypalPayment(Request $request)
    {
//        if ($this->checkDuplicate()) {
//            return $this->checkDuplicate();
//        }

        $id = $request->id;
        $type = $request->type;

        \Log::info($id);
        \Log::info($type);

        $workshop = Workshop::findOrFail($id);

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(config('paypal.client_id'));
        $gateway->setSecret(config('paypal.secret'));
        $mode = config('paypal.settings.mode') == 'sandbox' ? true : false;
        $gateway->setTestMode($mode);

        $cartTotal = number_format($workshop->getAmountByType($type));
        $currency = $this->currency['short_code'];
        try {
            $response = $gateway->purchase([
                'amount' => $cartTotal,
                'currency' => $currency,
                'description' => auth()->user()->name,
                'cancelUrl' => route('workshops.paypal.status', ['status' => 0, 'workshop_id' => $id, 'paytype' => $type]),
                'returnUrl' => route('workshops.paypal.status', ['status' => 1, 'workshop_id' => $id, 'paytype' => $type]),

            ])->send();
            if ($response->isRedirect()) {
                return Redirect::away($response->getRedirectUrl());
            }
        } catch (\Exception $e) {
            \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
            return Redirect::route('workshops.paypal.status');
        }

        \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
        return Redirect::route('workshops.paypal.status');
    }

    public function getPaymentStatus()
    {
        \Log::info('getPaymentStatus');
        \Log::info(request()->all());
        \Session::forget('failure');
        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
                return Redirect::route('workshops.status');
            }

            $workshop = Workshop::findOrFail(request()->get('workshop_id'));

            $order = $this->makeWorkshopOrder($workshop, request()->get('paytype'));
            $order->payment_type = 2;
            $order->transaction_id = request()->get('paymentId');
            $order->save();
            \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            $order->status = 1;
            $order->save();
//            foreach ($order->items as $orderItem) {
//                if ($orderItem->item_type != Item::class) {
//                    $orderItem->item->students()->attach($order->user_id);
//                }
//            }

            $workshop->students()->attach($order->user_id);

            //Generating Invoice
            generateInvoice($order);
//            $this->adminOrderMail($order);
//            $this->populatePaymentDisplayInfo();
            return Redirect::route('workshops.status');
        }
        else {
            \Session::flash('failure', trans('labels.frontend.cart.payment_failed'));
            $this->populatePaymentDisplayInfo();
            return Redirect::route('workshops.status');
        }

    }

    public function status()
    {
        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
                return Redirect::route('workshops.status');
            }else{
                \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            }
        }
        return view('frontend.workshops.status');
    }

    private function makeWorkshopOrder($workshop, $type)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->payer_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = $workshop->getAmountByType($type);
        $order->status = 1;
        $order->coupon_id = 0;
        $order->payment_type = 3;
        $order->save();
        //Getting and Adding items

        $order->items()->create([
            'item_id' =>$workshop->id,
            'item_type' => Workshop::class,
            'price' => $workshop->price
        ]);

        return $order;
    }
}
