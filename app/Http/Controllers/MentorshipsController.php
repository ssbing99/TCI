<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Course;
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

class MentorshipsController extends Controller
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
//            $this->populatePaymentDisplayInfo();
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
