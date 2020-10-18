    <div class="bg-f4f6f6 clearfix">
        <div class="carttitle clearfix">@lang('labels.frontend.cart.order_detail')</div>
        <ul class="cart-items clearfix">
            <li><div class="carts-text clearfix">@lang('labels.frontend.cart.sub_total')
                    <span>
                        @if(isset($total))
                            {{$appCurrency['symbol'].' '.$total}}
                        @endif
                    </span></div></li>
            <li>
                @if(Cart::getConditionsByType('coupon') != null)
                    @foreach(Cart::getConditionsByType('coupon') as $condition)
                    <div class="carts-text clearfix">{{ $condition->getValue().' '.$condition->getName()}}<i style="cursor: pointer" id="removeCoupon" class="fa text-danger fa-times-circle"></i>
                            <span>
                        {{ $appCurrency['symbol'].' '.number_format($condition->getCalculatedValue($total),2)}}
                    </span></div>
                    @endforeach


                @endif
                <div class="input-group">
                    <input type="text" class="form-control"id="coupon" pattern="[^\s]+" name="coupon" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-theme" id="applyCoupon" type="button">@lang('labels.frontend.cart.apply')</button>
                    </div>
                </div>
                <div class="input-group">
                    <p class="d-none" id="coupon-error">123</p>
                </div>
            </li>
            @if($taxData != null)
                @foreach($taxData as $tax)
                    <li class="border-0"><div class="carts-text clearfix">{{ $tax['name']}}
                            <span>
                        {{ $appCurrency['symbol'].' '.number_format($tax['amount'],2)}}
                    </span></div></li>
                    <li>
                @endforeach
            @endif
            <li><div class="carts-text font-bold clearfix">@lang('labels.backend.coupons.fields.total')
                    <span>{{$appCurrency['symbol'].' '.number_format(Cart::session(auth()->user()->id)->getTotal(),2)}}</span></div></li>
        </ul>
        <form method="post" action="{{ route('cart.cartCheckOut') }}">
            @csrf
            <input type="hidden" name="$isCheckout" value="true">
            <button type="submit" class="btn btn-theme btn-lg btn-block">@lang('labels.frontend.cart.checkout')</button>
        </form>
    </div>