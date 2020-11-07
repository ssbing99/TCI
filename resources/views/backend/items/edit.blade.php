@extends('backend.layouts.app')
@section('title', __('labels.backend.items.title').' | '.app_name())

@section('content')

    {!! Form::model($item, ['method' => 'PUT', 'route' => ['admin.items.update', $item->id], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.items.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.items.index') }}"
                   class="btn btn-success">@lang('labels.backend.items.view')</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.items.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => true]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug', trans('labels.backend.items.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.items.slug_placeholder')]) !!}
                </div>

            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('description',trans('labels.backend.items.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.items.fields.description')]) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('price', trans('labels.backend.items.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.price') ,'step' => 'any', 'pattern' => "[0-9]", 'required' => true]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('stock_count',  trans('labels.backend.items.fields.stock_count'), ['class' => 'control-label']) !!}
                    {!! Form::number('stock_count', old('stock_count'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.stock_count'),'step' => 'any', 'pattern' => "[0-9]", 'required' => true]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">

                    {!! Form::label('item_image', trans('labels.backend.items.fields.item_image'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::file('item_image', ['class' => 'form-control']) !!}
                    {!! Form::hidden('item_image_max_size', 8) !!}
                    {!! Form::hidden('item_image_max_width', 4000) !!}
                    {!! Form::hidden('item_image_max_height', 4000) !!}
                    @if ($item->item_image)
                        <a href="{{ asset('storage/uploads/'.$item->item_image) }}" target="_blank"><img
                                    height="50px" src="{{ asset('storage/uploads/'.$item->item_image) }}"
                                    class="mt-1"></a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('discount',  trans('labels.backend.items.fields.discount'), ['class' => 'control-label']) !!}
                    {!! Form::number('discount', old('discount'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.discount'),'step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('discount_type',  trans('labels.backend.items.fields.discount_type'), ['class' => 'control-label']) !!}
                    {!! Form::select('discount_type', ['perc' => 'Percentage','fix' => 'Fixed Price'],($item->discount_type) ? $item->discount_type : null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'discount_type' ]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    <div class="checkbox d-inline mr-4">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, old('published'), []) !!}
                        {!! Form::label('published', trans('labels.backend.items.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

{{--                    @if (Auth::user()->isAdmin())--}}

{{--                    <div class="checkbox d-inline mr-4">--}}
{{--                        {!! Form::hidden('featured', 0) !!}--}}
{{--                        {!! Form::checkbox('featured', 1, old('featured'), []) !!}--}}
{{--                        {!! Form::label('featured',  trans('labels.backend.items.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}

{{--                    <div class="checkbox d-inline mr-4">--}}
{{--                        {!! Form::hidden('trending', 0) !!}--}}
{{--                        {!! Form::checkbox('trending', 1, old('trending'), []) !!}--}}
{{--                        {!! Form::label('trending',  trans('labels.backend.items.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}

{{--                    <div class="checkbox d-inline mr-4">--}}
{{--                        {!! Form::hidden('popular', 0) !!}--}}
{{--                        {!! Form::checkbox('popular', 1, old('popular'), []) !!}--}}
{{--                        {!! Form::label('popular',  trans('labels.backend.items.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}
{{--                    @endif--}}
{{--                    <div class="checkbox d-inline mr-4">--}}
{{--                        {!! Form::hidden('free', 0) !!}--}}
{{--                        {!! Form::checkbox('free', 1, old('free'), []) !!}--}}
{{--                        {!! Form::label('free',  trans('labels.backend.items.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}

                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('meta_title',trans('labels.backend.items.fields.meta_title'), ['class' => 'control-label']) !!}
                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.meta_title')]) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_description',trans('labels.backend.items.fields.meta_description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.meta_description')]) !!}
                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_keywords',trans('labels.backend.items.fields.meta_keywords'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.items.fields.meta_keywords')]) !!}
                </div>

            </div>

            <div class="row">
                <div class="col-12  text-center form-group">
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

        });
        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 50000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

    </script>

@endpush