@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Enrollments</h3>
            @if (Auth::user()->isAdmin())
                <div class="float-right">
                    <a href="{{ route('admin.courses.enrollment.create', $course->id) }}"
                       class="btn btn-success">Enroll Student</a>

                </div>
                <div class="float-right mr-2">
                    <a href="{{ route('admin.course-user-zoom.create', ['course'=> $course->id]) }}"
                       class="btn btn-success">Schedule Zoom</a>

                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-block">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="{{ route('admin.courses.index') }}"
                               style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                        </li>
{{--                        |--}}
{{--                        <li class="list-inline-item">--}}
{{--                            <a href="{{ route('admin.courses.index') }}?show_deleted=1"--}}
{{--                               style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>--}}
{{--                        </li>--}}
                    </ul>
                </div>


                <table id="myTable" class="table table-bordered table-striped @can('course_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                    <thead>
                    <tr>
                            <th>@lang('labels.general.sr_no')</th>

                        <th>@lang('labels.backend.access.users.table.name')</th>
                        <th>@lang('labels.backend.access.users.table.email')</th>
                        @if( request('show_deleted') == 1 )
                            <th>&nbsp; @lang('strings.backend.general.actions')</th>
                        @else
                            <th>&nbsp; @lang('strings.backend.general.actions')</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>
                    @if($course->students()->count() > 0)
                        @foreach($course->students as $key=>$student)
                            @php $key++; @endphp
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$student->full_name}}</td>
                                <td>{{$student->email}}</td>
                                <td>
                                    <a href="{{route('admin.courses.enrollment.edit',['course_id' => $course->id, 'student_id' => $student->id]) }}"
                                       class="btn btn-xs btn-info mb-1"><i class="icon-pencil"></i></a>

                                    <a data-method="delete" data-trans-button-cancel="{{__('buttons.general.cancel')}}"
                                       data-trans-button-confirm="{{__('buttons.general.crud.delete')}}" data-trans-title="{{__('strings.backend.general.are_you_sure')}}"
                                       class="btn btn-xs btn-danger text-white mb-1" style="cursor:pointer;"
                                       onclick="$(this).find('form').submit();">
                                        <i class="fa fa-trash"
                                           data-toggle="tooltip"
                                           data-placement="top" title=""
                                           data-original-title="{{__('buttons.general.crud.delete')}}"></i>
                                        <form action="{{route('admin.courses.enrollment.delete',['course_id' => $course->id, 'student_id' => $student->id]) }}"
                                              method="POST" name="delete_item" style="display:none">
                                            @csrf
                                            {{method_field('DELETE')}}
                                        </form>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            var route = '{{route('admin.courses.get_data')}}';

            @if(request('show_deleted') == 1)
                route = '{{route('admin.courses.get_data',['show_deleted' => 1])}}';
            @endif

            @if(request('teacher_id') != "")
                route = '{{route('admin.courses.get_data',['teacher_id' => request('teacher_id')])}}';
            @endif

            @if(request('cat_id') != "")
                route = '{{route('admin.courses.get_data',['cat_id' => request('cat_id')])}}';
            @endif

            $('#myTable').DataTable({
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    'colvis'
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }

            });
            {{--@can('course_delete')--}}
            {{--@if(request('show_deleted') != 1)--}}
            {{--$('.actions').html('<a href="' + '{{ route('admin.courses.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');--}}
            {{--@endif--}}
            {{--@endcan--}}
        });

    </script>

@endpush