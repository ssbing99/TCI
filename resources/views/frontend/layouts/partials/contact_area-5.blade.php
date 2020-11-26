<section class="bg-primary clearfix">
    <div class="container-fluid">
        <div class="row clearfix">
            @if(config('contact_data') != "")
                @php
                    $contact_data = contact_data(config('contact_data'));
                @endphp
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
                    <div class="contact-area clearfix">
                        <div class="contactsubtitle">@lang('labels.frontend.layouts.partials.contact_us')</div>
                        <div class="contacttitle clearfix">@lang('labels.frontend.layouts.partials.get_in_touch')</div>
                        <p class="contacttext clearfix">{{ $contact_data["short_text"]["value"] }}</p>
                        <ul class="contactlist clearfix">
                            @if(($contact_data["primary_address"]["status"] == 1) || ($contact_data["secondary_address"]["status"] == 1))
                                @if($contact_data["primary_address"]["status"] == 1)
                                    <li>
                                        <div class="contactitem clearfix">
                                            <div class="icon"><i class="fa fa-map-marker"></i></div>
                                            <div class="txtright"><span>@lang('labels.frontend.contact.address')</span><br />{!! nl2br(e($contact_data["primary_address"]["value"])) !!}</div>
                                        </div>
                                    </li>
                                @endif
{{--                                @if($contact_data["secondary_phone"]["status"] == 1)--}}
{{--                                    <li>--}}
{{--                                        <div class="contactitem clearfix">--}}
{{--                                            <div class="icon"><i class="fa fa-map-marker"></i></div>--}}
{{--                                            <div class="txtright"><span>Address</span><br />{{$contact_data["secondary_address"]["value"]}}</div>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                @endif--}}
                            @endif

                                @if(($contact_data["primary_phone"]["status"] == 1) || ($contact_data["secondary_phone"]["status"] == 1))
                                    @if($contact_data["primary_phone"]["status"] == 1)
                                        <li>
                                            <div class="contactitem clearfix">
                                                <div class="icon"><i class="fa fa-phone"></i></div>
                                                <div class="txtright"><span>@lang('labels.frontend.contact.phone')</span><br />{{$contact_data["primary_phone"]["value"]}}</div>
                                            </div>
                                        </li>
                                    @endif
{{--                                    @if($contact_data["secondary_phone"]["status"] == 1)--}}
{{--                                        <li>--}}
{{--                                            <div class="contactitem clearfix">--}}
{{--                                                <div class="icon"><i class="fa fa-phone"></i></div>--}}
{{--                                                <div class="txtright"><span>Phone</span><br />{{$contact_data["secondary_phone"]["value"]}}</div>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                    @endif--}}
                                @endif

                                @if(($contact_data["primary_email"]["status"] == 1) || ($contact_data["secondary_email"]["status"] == 1))
                                    @if($contact_data["primary_email"]["status"] == 1)
                                        <li>
                                            <div class="contactitem clearfix">
                                                <div class="icon"><i class="fa fa-envelope"></i></div>
                                                <div class="txtright"><span>@lang('labels.frontend.contact.email')</span><br />{{$contact_data["primary_email"]["value"]}}</div>
                                            </div>
                                        </li>
                                    @endif

{{--                                        @if($contact_data["secondary_email"]["status"] == 1)--}}
{{--                                            <li>--}}
{{--                                                <div class="contactitem clearfix">--}}
{{--                                                    <div class="icon"><i class="fa fa-envelope"></i></div>--}}
{{--                                                    <div class="txtright"><span>Email</span><br />{{$contact_data["secondary_email"]["value"]}}</div>--}}
{{--                                                </div>--}}
{{--                                            </li>--}}
{{--                                        @endif--}}

                                @endif
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3344.275801757153!2d-96.70378628547405!3d33.04920507736546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c19b3dc02ccf3%3A0xdce1e7c649ea28d1!2s3928%20Dickens%20Dr%2C%20Plano%2C%20TX%2075023%2C%20USA!5e0!3m2!1sen!2sin!4v1603719892272!5m2!1sen!2sin" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
{{--                @if($contact_data["location_on_map"]["status"] == 1)--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div id="contact-map" class="contact-map-section">--}}
{{--                            {!! $contact_data["location_on_map"]["value"] !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            @else
                <h4>@lang('labels.general.no_data_available')</h4>
            @endif
        </div>
    </div>
</section>