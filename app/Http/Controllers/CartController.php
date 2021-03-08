<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Mail\Frontend\AdminOrederMail;
use App\Mail\OfflineOrderMail;
use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tax;
use Carbon\Carbon;
use Cart;
use GuzzleHttp\Client;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class CartController extends Controller
{

    private $path;
    private $currency;
    private $storeItemPrefix = 'SI_';

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
        $this->currency = getCurrency(config('app.currency'));


    }

    public function index(Request $request)
    {
        $ids = Cart::session(auth()->user()->id)->getContent()->keys();
        $course_ids = [];
        $bundle_ids = [];
        $storeItem_ids = [];
        $storeItem_extra = [];
        $isCheckout = false;

        if ($request->has('$isCheckout')) {
            $isCheckout = true;
        }

        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else if ($item->attributes->type == 'store') {
                $id = $this->getActualItemId($item->id, $item->attributes->type);
                $storeItem_ids[] = $id;
                $storeItem_extra[] = [
                    $id => [
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ],
                ];
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $storeItems = Item::find($storeItem_ids);

        if(isset($storeItems)){
            foreach($storeItems as $item){
                if(isset($storeItem_extra)){
                    foreach ($storeItem_extra as $extra){
                        if(isset($extra[$item->id])) {
                            $item->setPriceAttribute($extra[$item->id]['price']);
                            $item->setQuantityAttribute($extra[$item->id]['quantity']);
                        }
                    }
                }
            }
        }

        $courses = $bundles->merge($courses);
        $consolidateItems = $courses->merge($storeItems);
        $useSavedAddressFlag = false;

        $total = $consolidateItems->sum('price');
        //Apply Tax
        $taxData = $this->applyTax('total');
        $savedAddress = '';

        $view_path = returnPathByTheme($this->path.'.cart.checkout', 5,'-');
        if ($isCheckout) {
            $userInfo = Auth::user();
            $useSavedAddressFlag = $userInfo->save_address_flag == 'Y';
            if ($useSavedAddressFlag) {
                $savedAddress = $userInfo-> saved_address;
            }
            $view_path = returnPathByTheme($this->path.'.cart.checkout_confirm', 5,'-');
        }

        return view($view_path, compact('courses', 'bundles', 'storeItems', 'total', 'taxData', 'useSavedAddressFlag'))->with('savedAddress', json_decode($savedAddress, true));
    }

    public function addToCart(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        $quantity = 1;
        $price = 0;
        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';
            $price = $product->price;

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
            $price = $product->price;
        } else if ($request->has('storeItem_id')) {
            $product = Item::findOrFail($request->get('storeItem_id'));
            $type = 'store';
            $quantity = isset($request->quantity) ? $request->quantity : 1;
            $price = $quantity * $product->price;
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();

        if(isset($request->quantity) && in_array($this->getShoppingCartItemId($product->id, $type), $cart_items)){
            $exist = Cart::session(auth()->user()->id)->get($this->getShoppingCartItemId($product->id, $type));

            $newPrice = $exist->price + $price;

            Cart::session(auth()->user()->id)
                ->update($this->getShoppingCartItemId($product->id, $type),[
                    'quantity' => [
                        'value'=>$quantity,
                        'relative' => true
                    ],
                    'price' => $newPrice
                ]);

        }

        if (!in_array($this->getShoppingCartItemId($product->id, $type), $cart_items)) {
            Cart::session(auth()->user()->id)
                ->add($this->getShoppingCartItemId($product->id, $type), $product->title, $price, $quantity,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers
                    ]);
        }

        Session::flash('success', trans('labels.frontend.cart.product_added'));
        return back();
    }

    private function getShoppingCartItemId($itemId, $type) {
        if ($type == 'store') {
            return $this->storeItemPrefix.$itemId;
        } else {
            return $itemId;
        }
    }

    private function getActualItemId($itemId, $type) {
        if ($type == 'store') {
            $prefix = $this->storeItemPrefix;
            if (substr($itemId, 0, strlen($prefix)) == $prefix) { // remove prefix
                return substr($itemId, strlen($prefix));
            }
            return $itemId;
        } else {
            return $itemId;
        }
    }

    public function checkout(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        $bundle_ids = [];
        $course_ids = [];

        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        if ($request->has('isConfirm')) {
            $isConfirm = true;
        }

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $product->price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers
                    ]);
        }
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = $courses->sum('price');

        //Apply Tax
        $taxData = $this->applyTax('total');

        $view_path = returnPathByTheme($this->path.'.cart.checkout', 5,'-');

        // return view($this->path . '.cart.checkout', compact('courses', 'total', 'taxData'));
        return view( $view_path, compact('courses', 'total', 'taxData'));
    }

    public function singleCheckout(Request $request)
    {
        $product = "";
        $teachers = "";
        $type = "";
        $bundle_ids = [];
        $course_ids = [];
        $gift = false;

        if($request->has('gift_course') && $request->get('gift_course') == 'true'){
            $gift = true;
        }

        if ($request->has('course_id')) {
            $product = Course::findOrFail($request->get('course_id'));
            $teachers = $product->teachers->pluck('id', 'name');
            $type = 'course';

        } elseif ($request->has('bundle_id')) {
            $product = Bundle::findOrFail($request->get('bundle_id'));
            $teachers = $product->user->name;
            $type = 'bundle';
        }

        if ($request->has('isConfirm')) {
            $isConfirm = true;
        }

        //clear for only single
        Cart::session(auth()->user()->id)->clear();

        $cart_items = Cart::session(auth()->user()->id)->getContent()->keys()->toArray();
        if (!in_array($product->id, $cart_items)) {

            Cart::session(auth()->user()->id)
                ->add($product->id, $product->title, $product->price, 1,
                    [
                        'user_id' => auth()->user()->id,
                        'description' => $product->description,
                        'image' => $product->course_image,
                        'type' => $type,
                        'teachers' => $teachers,
                        'gift' => $gift
                    ]);

        }else{
            Cart::session(auth()->user()->id)
                ->update($this->getShoppingCartItemId($product->id, $type),[
                    'gift' => $gift
                ]);
        }

        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = Course::find($course_ids);
//        $bundle = Bundle::find($bundle_ids);
//        $courses = $bundles->merge($courses);

//        $total = $courses->sum('price');

        //Apply Tax
        $taxData = $this->applyTax('total');

        $view_path = returnPathByTheme($this->path.'.cart.single-checkout', 5,'-');

        // return view($this->path . '.cart.checkout', compact('courses', 'total', 'taxData'));
        return view( $view_path, compact('courses', 'taxData', 'gift'));
    }

    public function singleCheckoutSubmit(Request $request){
        $this->validate($request, [
            'paymentMethod' => 'required'
        ]);

        \Log::info($request->all());

        $coupon = $request->coupon;
        $couponEnter = $request->coupon;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();

        $course = Course::find($request->productId);

        $withSkype = $request->coursePrice == 'withoutSkype' ? false : true;

        $total = $withSkype ? $course->price_skype : $course->price;

        $subtotal = $total;

        Cart::session(auth()->user()->id)
            ->update($this->getShoppingCartItemId($course->id, 'course'),[
                'price' => $total
            ]);

        if($request->has('gift_course')){
            $rec_name = $request->giftName;
            $rec_email = $request->giftEmail;

            \Log::info($rec_name);
            \Log::info($rec_email);

            $user = User::query()
                ->where('email', '=', $rec_email)->get();

            if($user->count() == 0 || strcasecmp(auth()->user()->email, $rec_email) == 0){
                return redirect()->route('cart.singleCheckout',['course_id' => $course->id, 'gift_course' => true])->withdanger('Invalid Recipient !');
            }else{

                $purchased_course = $course->students()->where('email', $rec_email)->count() > 0;

                if($purchased_course){
                    return redirect()->route('cart.singleCheckout',['course_id' => $course->id, 'gift_course' => true])->withdanger('Recipient already own this course!');
                }

                Cart::session(auth()->user()->id)->removeConditionsByType('gift');

                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => 'Gift',
                    'type' => 'gift',
                    'value' => $user->first()->id,
                ));

                Cart::session(auth()->user()->id)->condition($condition);

                \Log::info(Cart::session(auth()->user()->id)->getConditions());
            }
        }

        if ($coupon != null) {
            $isCouponValid = false;
            if ($coupon->useByUser() < $coupon->per_user_limit) {
                $isCouponValid = true;
                if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
                    if ($total >= $coupon->min_price) {
                        $isCouponValid = true;
                    }
                } else {
                    $isCouponValid = true;
                }
                if ($coupon->expires_at != null) {
                    if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
                        $isCouponValid = true;
                    } else {
                        $isCouponValid = false;
                    }
                }

            }

            if ($isCouponValid == true) {
                $type = null;
                if ($coupon->type == 1) {
                    $subtotal = $subtotal * (1-($coupon->amount / 100));
                    $type = '-' . $coupon->amount . '%';
                } else {
                    $subtotal = $subtotal - $coupon->amount;
                    $type = '-' . $coupon->amount;
                }

                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                    'value' => $type,
                    'order' => 1
                ));

                Cart::session(auth()->user()->id)->condition($condition);

            }else{
                return redirect()->route('cart.singleCheckout',['course_id' => $course->id])->withdanger('Invalid Coupon used !');
            }

        }


        if($couponEnter != null && $coupon == null){
            return redirect()->route('cart.singleCheckout',['course_id' => $course->id])->withdanger('Invalid Coupon used !');

        }

        if($request->paymentMethod == 'stripe'){

            if ($this->checkDuplicateWithProduct($course, 'course')) {
                return $this->checkDuplicateWithProduct($course, 'course');
            }
            //Making Order
            $order = $this->makeCourseOrder($course, $total,$subtotal, $coupon, $withSkype);

            $gateway = Omnipay::create('Stripe');
            $gateway->setApiKey(config('services.stripe.secret'));
            $token = $request->reservation['stripe_token'];

            $amount = $subtotal;
            $currency = $this->currency['short_code'];
            $response = $gateway->purchase([
                'amount' => $amount,
                'currency' => $currency,
                'token' => $token,
                'confirm' => true,
                'description' => auth()->user()->name
            ])->send();

            if ($response->isSuccessful()) {
                $order->status = 1;
                $order->payment_type = 1;
                $order->save();
                (new EarningHelper)->insert($order);
                foreach ($order->items as $orderItem) {
                    //Bundle Entries
                    if ($orderItem->item_type == Bundle::class) {
                        foreach ($orderItem->item->courses as $course) {
                            $course->students()->attach($order->user_id);
                        }
                    }
                    $orderItem->item->students()->attach($order->user_id);
                }

                //Generating Invoice
                generateInvoice($order);
                $this->adminOrderMail($order);

                $this->populatePaymentDisplayInfo();
                Cart::session(auth()->user()->id)->clear();
                Session::flash('success', trans('labels.frontend.cart.payment_done'));
                return redirect()->route('status');

            } else {
                $order->status = 2;
                $order->save();
                \Log::info($response->getMessage() . ' for id = ' . auth()->user()->id);
                Session::flash('failure', trans('labels.frontend.cart.try_again'));
                return redirect()->route('status');
            }

        }elseif($request->paymentMethod == 'paypal'){

            Cart::session(auth()->user()->id)->removeConditionsByType('skypePrice');

            $skypecondition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'SkypePrice',
                'type' => 'skypePrice',
                'value' => $withSkype,
            ));

            Cart::session(auth()->user()->id)->condition($skypecondition);


            \Log::info('paypalPayment 2');
            if ($this->checkDuplicateWithProduct($course, 'bundle')) {
                return $this->checkDuplicateWithProduct($course, 'bundle');
            }

            $gateway = Omnipay::create('PayPal_Rest');
            $gateway->setClientId(config('paypal.client_id'));
            $gateway->setSecret(config('paypal.secret'));
            $mode = config('paypal.settings.mode') == 'sandbox' ? true : false;
            $gateway->setTestMode($mode);

            $currency = $this->currency['short_code'];
            try {
                $response = $gateway->purchase([
                    'amount' => $subtotal,
                    'currency' => $currency,
                    'description' => auth()->user()->name,
                    'cancelUrl' => route('cart.paypal.status', ['status' => 0]),
                    'returnUrl' => route('cart.paypal.status', ['status' => 1]),

                ])->send();

                if ($response->isRedirect()) {
                    return Redirect::away($response->getRedirectUrl());
                }
            } catch (\Exception $e) {
                \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
                return Redirect::route('cart.paypal.status');
            }

            \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
            return Redirect::route('cart.paypal.status');

        }
    }

    public function clear(Request $request)
    {
        Cart::session(auth()->user()->id)->clear();
        return back();
    }

    public function remove(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');


        if (Cart::session(auth()->user()->id)->getContent()->count() < 2) {
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->removeConditionsByType('skypePrice');
            Cart::session(auth()->user()->id)->clear();
        }
        if ($request->has('course')) {
            Cart::session(auth()->user()->id)->remove($request->course);
        }
        if ($request->has('storeItem')) {
            $id = $this->getShoppingCartItemId($request->course, 'store');
            Cart::session(auth()->user()->id)->remove($id);
        }
        return redirect(route('cart.index'));
    }

    public function stripePayment(Request $request)
    {
        $saveInfo = $request->input('saveInfo');
        $addressInfo = $this->constructAddressObj($request);
        $this->updateUserAddress($addressInfo, $saveInfo != null ? 'Y' : 'N');
        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }
        //Making Order
        $order = $this->makeOrder();

        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey(config('services.stripe.secret'));
        $token = $request->reservation['stripe_token'];

        $amount = Cart::session(auth()->user()->id)->getTotal();
        $currency = $this->currency['short_code'];
        $response = $gateway->purchase([
            'amount' => $amount,
            'currency' => $currency,
            'token' => $token,
            'confirm' => true,
            'description' => auth()->user()->name
        ])->send();

        if ($response->isSuccessful()) {
            $order->status = 1;
            $order->payment_type = 1;
            $order->save();
            (new EarningHelper)->insert($order);
            foreach ($order->items as $orderItem) {
                //Bundle Entries
                if ($orderItem->item_type == Bundle::class) {
                    foreach ($orderItem->item->courses as $course) {
                        $course->students()->attach($order->user_id);
                    }
                }
                $orderItem->item->students()->attach($order->user_id);
            }

            //Generating Invoice
            generateInvoice($order);
            $this->adminOrderMail($order);

            $this->populatePaymentDisplayInfo();
            Cart::session(auth()->user()->id)->clear();
            Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return redirect()->route('status');

        } else {
            $order->status = 2;
            $order->save();
            \Log::info($response->getMessage() . ' for id = ' . auth()->user()->id);
            Session::flash('failure', trans('labels.frontend.cart.try_again'));
            return redirect()->route('cart.index');
        }
    }

    public function constructAddressObj(Request $request) {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $address2 = $request->input('address2');
        $country = $request->input('country');
        $state = $request->input('state');
        $zip = $request->input('zip');
        $info = array(
            'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email,
            'phone' => $phone, 'address' => $address, 'address2' => $address2,
            'country' => $country, 'state' => $state, 'zip' => $zip
        );
        return $info;
    }

    public function paypalPayment(Request $request)
    {
        $saveInfo = $request->input('saveInfo');
        $addressInfo = $this->constructAddressObj($request);
        $this->updateUserAddress($addressInfo, $saveInfo != null ? 'Y' : 'N');
        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }

        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId(config('paypal.client_id'));
        $gateway->setSecret(config('paypal.secret'));
        $mode = config('paypal.settings.mode') == 'sandbox' ? true : false;
        $gateway->setTestMode($mode);

        $cartTotal = number_format(Cart::session(auth()->user()->id)->getTotal());
        $currency = $this->currency['short_code'];
        try {
            $response = $gateway->purchase([
                'amount' => $cartTotal,
                'currency' => $currency,
                'description' => auth()->user()->name,
                'cancelUrl' => route('cart.paypal.status', ['status' => 0]),
                'returnUrl' => route('cart.paypal.status', ['status' => 1]),

            ])->send();
            if ($response->isRedirect()) {
                return Redirect::away($response->getRedirectUrl());
            }
        } catch (\Exception $e) {
            \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
            return Redirect::route('cart.paypal.status');
        }

        \Session::put('failure', trans('labels.frontend.cart.unknown_error'));
        return Redirect::route('cart.paypal.status');
    }

    public function offlinePayment(Request $request)
    {
        $saveInfo = $request->input('saveInfo');
        $addressInfo = $this->constructAddressObj($request);
        $this->updateUserAddress($addressInfo, $saveInfo != null ? 'Y' : 'N');
        if ($this->checkDuplicate()) {
            return $this->checkDuplicate();
        }

        //Making Order
        $order = $this->makeOrder();
        $order->payment_type = 3;
        $order->status = 0;
        $order->shipping_address = $addressInfo;
        $order->save();
        $content = [];
        $items = [];
        $counter = 0;
        foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
            $counter++;
            array_push($items, ['number' => $counter, 'name' => $cartItem->name, 'price' => $cartItem->price]);
        }

        $content['items'] = $items;
        $content['total'] =  number_format(Cart::session(auth()->user()->id)->getTotal(),2);
        $content['reference_no'] = $order->reference_no;

        try {
            \Mail::to(auth()->user()->email)->send(new OfflineOrderMail($content));
            $this->adminOrderMail($order);
        } catch (\Exception $e) {
            \Log::info($e->getMessage() . ' for order ' . $order->id);
        }

        Cart::session(auth()->user()->id)->clear();
        \Session::flash('success', trans('labels.frontend.cart.offline_request'));
        return redirect()->route('courses.all');
    }


    public function status()
    {
        \Log::info(\Session::get('success'));
        \Log::info(\Session::get('failure'));

        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));
                return Redirect::route('status');
            }else{
                \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            }
        }
        return view('frontend.cart.status');
    }

    public function getPaymentStatus()
    {
        \Log::info('getPaymentStatus');
        \Log::info(request()->all());
        \Session::forget('failure');
        if (request()->get('status')) {
            if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
                \Session::put('failure', trans('labels.frontend.cart.payment_failed'));

                Cart::session(auth()->user()->id)->clear();
                Cart::session(auth()->user()->id)->clearCartConditions();
                Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
                Cart::session(auth()->user()->id)->removeConditionsByType('tax');
                Cart::session(auth()->user()->id)->removeConditionsByType('skypePrice');

                return Redirect::route('status');
            }
            $order = $this->makeOrder();
            $order->payment_type = 2;
            $order->transaction_id = request()->get('paymentId');
            $order->save();
            \Session::flash('success', trans('labels.frontend.cart.payment_done'));
            $order->status = 1;
            $order->save();
            (new EarningHelper)->insert($order);
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type != Item::class) {
                    //Bundle Entries
                    if ($orderItem->item_type == Bundle::class) {
                        foreach ($orderItem->item->courses as $course) {
                            $course->students()->attach($order->user_id);
                        }
                    }
                    $orderItem->item->students()->attach($order->user_id);
                }
            }

            //Generating Invoice
            generateInvoice($order);
            $this->adminOrderMail($order);
            $this->populatePaymentDisplayInfo();
            Cart::session(auth()->user()->id)->clear();
            Session::flash('success', trans('labels.frontend.cart.payment_done'));
            return Redirect::route('status');
        }
        else {
            \Session::flash('failure', trans('labels.frontend.cart.payment_failed'));
            $this->populatePaymentDisplayInfo();
            Cart::session(auth()->user()->id)->clear();
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');
            Cart::session(auth()->user()->id)->removeConditionsByType('skypePrice');
            return Redirect::route('status');
        }

    }

    public function getNow(Request $request)
    {
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = 0;
        $order->status = 1;
        $order->payment_type = 0;
        $order->save();
        //Getting and Adding items
        if ($request->course_id) {
            $type = Course::class;
            $id = $request->course_id;
        } else {
            $type = Bundle::class;
            $id = $request->bundle_id;

        }
        $order->items()->create([
            'item_id' => $id,
            'item_type' => $type,
            'price' => 0
        ]);

        foreach ($order->items as $orderItem) {
            //Bundle Entries
            if ($orderItem->item_type == Bundle::class) {
                foreach ($orderItem->item->courses as $course) {
                    $course->students()->attach($order->user_id);
                }
            }
            $orderItem->item->students()->attach($order->user_id);
        }
        Session::flash('success', trans('labels.frontend.cart.purchase_successful'));
        return back();

    }

    public function getOffers()
    {
        $coupons = Coupon::where('status', '=', 1)->get();
        return view('frontend.cart.offers', compact('coupons'));
    }

    public function applyCoupon(Request $request)
    {
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');

        $coupon = $request->coupon;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();

        if ($coupon != null) {
            Cart::session(auth()->user()->id)->clearCartConditions();
            Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
            Cart::session(auth()->user()->id)->removeConditionsByType('tax');

            $ids = Cart::session(auth()->user()->id)->getContent()->keys();
            $course_ids = [];
            $bundle_ids = [];
            foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
                if ($item->attributes->type == 'bundle') {
                    $bundle_ids[] = $item->id;
                } else {
                    $course_ids[] = $item->id;
                }
            }
            $courses = new Collection(Course::find($course_ids));
            $bundles = Bundle::find($bundle_ids);
            $courses = $bundles->merge($courses);

            $total = $courses->sum('price');
            $isCouponValid = false;
            if ($coupon->useByUser() < $coupon->per_user_limit) {
                $isCouponValid = true;
                if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
                    if ($total >= $coupon->min_price) {
                        $isCouponValid = true;
                    }
                } else {
                    $isCouponValid = true;
                }
                if ($coupon->expires_at != null) {
                    if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
                        $isCouponValid = true;
                    } else {
                        $isCouponValid = false;
                    }
                }

            }

            if ($isCouponValid == true) {
                $type = null;
                if ($coupon->type == 1) {
                    $type = '-' . $coupon->amount . '%';
                } else {
                    $type = '-' . $coupon->amount;
                }

                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                    'value' => $type,
                    'order' => 1
                ));

                Cart::session(auth()->user()->id)->condition($condition);
                //Apply Tax
                $taxData = $this->applyTax('subtotal');

                $html = view('frontend.cart.partials.order-stats'.(getThemeLayout()==5?'-5':''), compact('total', 'taxData'))->render();
                return ['status' => 'success', 'html' => $html];
            }


        }
        return ['status' => 'fail', 'message' => trans('labels.frontend.cart.invalid_coupon')];
    }

    public function removeCoupon(Request $request)
    {

        Cart::session(auth()->user()->id)->clearCartConditions();
        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');

        $course_ids = [];
        $bundle_ids = [];
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $courses = $bundles->merge($courses);

        $total = $courses->sum('price');

        //Apply Tax
        $taxData = $this->applyTax('subtotal');

        $html = view('frontend.cart.partials.order-stats'.(getThemeLayout()==5?'-5':''), compact('total', 'taxData'))->render();
        return ['status' => 'success', 'html' => $html];

    }

    private function updateUserAddress($address, $savedAddressFlag) {
        $user = Auth::user();
        $user->save_address_flag = $savedAddressFlag;
        $user->saved_address = $address;
        $user->save();
    }

    private function makeOrder()
    {
        $coupon = Cart::session(auth()->user()->id)->getConditionsByType('coupon')->first();
        if ($coupon != null) {
            $coupon = Coupon::where('code', '=', $coupon->getName())->first();
        }

        $savedAddress = null;
        try {
            $userInfo = Auth::user();
            $savedAddress = $userInfo->saved_address;
        }catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

        $course = '';

        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'course') {
                $course = $cartItem->id;

            }
//            if ($cartItem->attributes->type == 'bundle') {
//                foreach ($order_items->where('item_type', 'App\Models\Bundle') as $item) {
//                    if ($item->item_id == $cartItem->id) {
//                        $is_duplicate = true;
//                        $message .= $cartItem->name . '' . __('alerts.frontend.duplicate_bundle') . '</br>';
//                    }
//                }
//            }
        }

        $gift = Cart::session(auth()->user()->id)->get($course)->attributes->gift;
        $gift_user = $gift? Cart::session(auth()->user()->id)->getConditionsByType('gift')->first()->getValue() : auth()->user()->id;

        $skype = Cart::session(auth()->user()->id)->getConditionsByType('skypePrice')->first();
        $withSkype = false;
        if ($skype != null) {
            $withSkype = Cart::session(auth()->user()->id)->getConditionsByType('skypePrice')->first()->getValue();
        }

        $order = new Order();
        $order->user_id = $gift? $gift_user : auth()->user()->id;
        $order->payer_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = Cart::session(auth()->user()->id)->getTotal();
        $order->is_skype = $withSkype;
        $order->status = 1;
        $order->coupon_id = ($coupon == null) ? 0 : $coupon->id;
        $order->payment_type = 3;
//        $order->shipping_address = $savedAddress;
        \Log::info(json_encode($order));
        $order->save();
        //Getting and Adding items
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'bundle') {
                $type = Bundle::class;
            } else if ($cartItem->attributes->type == 'store') {
                $type = Item::class;
            } else {
                $type = Course::class;
            }
            if ($type == Item::class) {
                $order->items()->create([
                    'item_id' =>$this->getActualItemId($cartItem->id, 'store'),
                    'item_type' => $type,
                    'price' => $cartItem->price
                ]);
            } else {
                $order->items()->create([
                    'item_id' => $cartItem->id,
                    'item_type' => $type,
                    'price' => $cartItem->price
                ]);
            }

        }
//        Cart::session(auth()->user()->id)->removeConditionsByType('coupon');
        return $order;
    }

    private function makeCourseOrder($course, $total, $subtotal, $coupon, $withSkype)
    {

        $gift = Cart::session(auth()->user()->id)->get($course->id)->attributes->gift;
        $gift_user = $gift? Cart::session(auth()->user()->id)->getConditionsByType('gift')->first()->getValue() : auth()->user()->id;

        $order = new Order();
        $order->user_id = $gift? $gift_user : auth()->user()->id;
        $order->payer_id = auth()->user()->id;
        $order->reference_no = str_random(8);
        $order->amount = $subtotal;
        $order->is_skype = $withSkype;
        $order->status = 1;
        $order->coupon_id = ($coupon == null) ? 0 : $coupon->id;
        $order->payment_type = 3;
        $order->save();
        //Getting and Adding items

        $type = Course::class;
        $order->items()->create([
            'item_id' => $course->id,
            'item_type' => $type,
            'price' => $total
        ]);
        return $order;
    }

    private function checkDuplicate()
    {
        $is_duplicate = false;
        $message = '';
        $orders = Order::where('user_id', '=', auth()->user()->id)->pluck('id');
        $order_items = OrderItem::whereIn('order_id', $orders)->get(['item_id', 'item_type']);
        foreach (Cart::session(auth()->user()->id)->getContent() as $cartItem) {
            if ($cartItem->attributes->type == 'course') {
                foreach ($order_items->where('item_type', 'App\Models\Course') as $item) {
                    if ($item->item_id == $cartItem->id) {
                        $is_duplicate = true;
                        $message .= $cartItem->name . ' ' . __('alerts.frontend.duplicate_course') . '</br>';
                    }
                }
            }
            if ($cartItem->attributes->type == 'bundle') {
                foreach ($order_items->where('item_type', 'App\Models\Bundle') as $item) {
                    if ($item->item_id == $cartItem->id) {
                        $is_duplicate = true;
                        $message .= $cartItem->name . '' . __('alerts.frontend.duplicate_bundle') . '</br>';
                    }
                }
            }
        }

        if ($is_duplicate) {
            return redirect()->back()->withdanger($message);
        }
        return false;

    }

    private function checkDuplicateWithProduct($product, $product_type)
    {
        $is_duplicate = false;
        $message = '';
        $orders = Order::where('status', '=', 1)->where('user_id', '=', auth()->user()->id)->pluck('id');
        $order_items = OrderItem::whereIn('order_id', $orders)->get(['item_id', 'item_type']);
        if ($product_type == 'course') {
            foreach ($order_items->where('item_type', 'App\Models\Course') as $item) {
                if ($item->item_id == $product->id) {
                    $is_duplicate = true;
                    $message .= $product->title . ' ' . __('alerts.frontend.duplicate_course') . '</br>';
                }
            }
        }
        if ($product_type == 'bundle') {
            foreach ($order_items->where('item_type', 'App\Models\Bundle') as $item) {
                if ($item->item_id == $product->id) {
                    $is_duplicate = true;
                    $message .= $product->title . '' . __('alerts.frontend.duplicate_bundle') . '</br>';
                }
            }
        }

        if ($is_duplicate) {
            return redirect()->route('cart.singleCheckout',['course_id' => $product->id])->withdanger($message);
        }
        return false;

    }

    private function applyTax($target)
    {
        //Apply Conditions on Cart
        $taxes = Tax::where('status', '=', 1)->get();
        Cart::session(auth()->user()->id)->removeConditionsByType('tax');
        if ($taxes != null) {
            $taxData = [];
            foreach ($taxes as $tax) {
                $total = Cart::session(auth()->user()->id)->getTotal();
                $taxData[] = ['name' => '+' . $tax->rate . '% ' . $tax->name, 'amount' =>  number_format(($total * $tax->rate / 100),2)];
            }

            $condition = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'Tax',
                'type' => 'tax',
                'target' => 'total', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $taxes->sum('rate') . '%',
                'order' => 2
            ));
            Cart::session(auth()->user()->id)->condition($condition);
            return $taxData;
        }
    }

    private function adminOrderMail($order)
    {
        if(config('access.users.order_mail')) {
            $content = [];
            $items = [];
            $counter = 0;
            foreach (Cart::session(auth()->user()->id)->getContent() as $key => $cartItem) {
                $counter++;
                array_push($items, ['number' => $counter, 'name' => $cartItem->name, 'price' => $cartItem->price]);
            }

            $content['items'] = $items;
            $content['total'] =  number_format(Cart::session(auth()->user()->id)->getTotal(),2);
            $content['reference_no'] = $order->reference_no;

            $admins = User::role('administrator')->get();
            foreach ($admins as $admin) {
                \Mail::to($admin->email)->send(new AdminOrederMail($content, $admin));
            }
        }
    }

    private function populatePaymentDisplayInfo() {
        $course_ids = [];
        $bundle_ids = [];
        $storeItem_ids = [];
        $storeItem_extra = [];
        foreach (Cart::session(auth()->user()->id)->getContent() as $item) {
            if ($item->attributes->type == 'bundle') {
                $bundle_ids[] = $item->id;
            } else if ($item->attributes->type == 'store') {
                $id = $this->getActualItemId($item->id, $item->attributes->type);
                $storeItem_ids[] = $id;
                $storeItem_extra[] = [
                    $id => [
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ],
                ];
            } else {
                $course_ids[] = $item->id;
            }
        }
        $courses = new Collection(Course::find($course_ids));
        $bundles = Bundle::find($bundle_ids);
        $storeItems = Item::find($storeItem_ids);

        if(isset($storeItems)){
            foreach($storeItems as $item){
                if(isset($storeItem_extra)){
                    foreach ($storeItem_extra as $extra){
                        if(isset($extra[$item->id])) {
                            $item->setPriceAttribute($extra[$item->id]['price']);
                            $item->setQuantityAttribute($extra[$item->id]['quantity']);
                        }
                    }
                }
            }
        }

        $courses = $bundles->merge($courses);
        $consolidateItems = $courses->merge($storeItems);
        $total = $consolidateItems->sum('price');
        $savedAddress = Auth::user() -> saved_address;

        \Session::flash('courses', $courses);
        \Session::flash('saved_address', $savedAddress);
        \Session::flash('storeItems', $storeItems);
        \Session::flash('total', $total);
    }
}
