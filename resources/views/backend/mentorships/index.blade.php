@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', 'Mentorship | '.app_name())


@section('content')


    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline mb-0">Mentorship</h3>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="text-align:center;">
                            <input type="checkbox" class="mass" id="select-all"/>
                        </th>
                        <th>@lang('labels.general.sr_no')</th>
                        <th>@lang('labels.general.id')</th>
{{--                        <th>@lang('labels.backend.orders.fields.reference_no')</th>--}}
                        <th>@lang('labels.backend.orders.fields.items')</th>
                        <th>@lang('labels.backend.orders.fields.user_email')</th>
                        <th>Mentor</th>
                        <th>@lang('labels.backend.orders.fields.date')</th>
{{--                        <th>&nbsp; @lang('strings.backend.general.actions')</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@push('after-scripts')
    <script>
        $(document).ready(function () {
            var route = '{{route('admin.mentorships.get_data')}}';

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                    {
                        data: function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                    {data: "DT_RowIndex", name: 'DT_RowIndex', searchable: false, orderable: false},
                    {data: "id", name: 'id'},
                    // {data: "reference_no", name: 'reference_no'},
                    {data: "items", name: 'items'},
                    {data: "user_email", name: 'user_email'},
                    {data: "teacher", name: 'teacher'},
                    {data: "date", name: "date"},
                    // {data: "actions", name: "actions"}
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });
{{--            @can('course_delete')--}}
{{--            @if(request('show_deleted') != 1)--}}
{{--            $('.actions').html('<a href="' + '{{ route('admin.mentorships.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');--}}
{{--            @endif--}}
{{--            @endcan--}}
        });
    </script>
@endpush