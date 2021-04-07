<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Gift;
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

class GiftsController extends Controller
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
        $gifts = Gift::query()->orderBy('created_at', 'asc')->get();

        $view_path = returnPathByTheme($this->path.'.gifts.index', 5,'-');

        return view( $view_path, compact('gifts'));
    }

    public function status()
    {
        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
                return Redirect::route('gifts.status');
            }else{
                \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            }
        }
        return view('frontend.gifts.status');
    }

}
