{{-- @extends('layouts.app')
@section('title', trans('entities.stands_types'))
@section('title_header', trans('entities.stands_types'))
@section('buttons')
<a href="{{url('admin/stands-types/create')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.add')}}"><i class="fas fa-plus"></i></a>
@endsection
@section('content')
<div class="container-fluid">
    @if (Session::has('success'))
    @include('admin.partials.success')
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card card-tabs">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="it-tab" data-toggle="pill" href="#it-pages-tab" role="tab" aria-controls="it-pages-tabe" aria-selected="true">IT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="en-tab" data-toggle="pill" href="#en-pages-tab" role="tab" aria-controls="en-pages-tab" aria-selected="false">EN</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-3">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="it-pages-tab" role="tabpanel" aria-labelledby="it-tab">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{trans('tables.name')}}</th>
                                        <th>{{trans('tables.size')}} ({{trans('generals.mq')}})</th>
                                        <th>{{trans('tables.price')}} €</th>
                                        <th>{{trans('tables.max_n_modules')}}</th>
                                        <th class="no-sort">{{trans('tables.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($it as $stand)
                                    <tr>
                                        <td>{{$stand->name}}</td>
                                        <td>{{$stand->size}}</td>
                                        <td>{{$stand->price}}</td>
                                        <td>{{$stand->max_number_modules}}</td>
                                        <td>
                                            <div class="btn-group btn-group" role="group">
                                                <a data-toggle="tooltip" data-placement="top" title="{{trans('generals.edit')}}" class="btn btn-default" href="{{route('stands-types.edit', $stand->id)}}"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('stands-types.destroy', $stand->stand_type_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button data-toggle="tooltip" data-placement="top" title="{{trans('generals.delete')}}" class="btn btn-default"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="en-pages-tab" role="tabpanel" aria-labelledby="en-tab">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{trans('tables.name')}}</th>
                                        <th>{{trans('tables.size')}} ({{trans('generals.mq')}})</th>
                                        <th>{{trans('tables.price')}} €</th>
                                        <th>{{trans('tables.max_n_modules')}}</th>
                                        <th class="no-sort">{{trans('tables.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($en as $stand)
                                    <tr>
                                        <td>{{$stand->name}}</td>
                                        <td>{{$stand->size}}</td>
                                        <td>{{$stand->price}}</td>
                                        <td>{{$stand->max_number_modules}}</td>
                                        <td>
                                            <div class="btn-group btn-group" role="group">
                                                <a data-toggle="tooltip" data-placement="top" title="{{trans('generals.edit')}}" class="btn btn-default" href="{{route('stands-types.edit', $stand->id)}}"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('stands-types.destroy', $stand->stand_type_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button data-toggle="tooltip" data-placement="top" title="{{trans('generals.delete')}}" class="btn btn-default"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $('form button').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        Swal.fire({
            title: "{!! trans('generals.confirm_remove') !!}",
            html: "{!! trans('generals.confirm_remove_both_sub') !!}",
            showCancelButton: true,
            confirmButtonText: "{{ trans('generals.confirm') }}",
            cancelButtonText: "{{ trans('generals.cancel') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                $this.closest('form').submit();
            }
        })
    });
    $(document).ready(function() {
        $('table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false,
            "responsive": false,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            "oLanguage": {
                "sSearch": "{{trans('generals.search')}}",
                "oPaginate": {
                    "sFirst": "{{trans('generals.start')}}", // This is the link to the first page
                    "sPrevious": "«", // This is the link to the previous page
                    "sNext": "»", // This is the link to the next page
                    "sLast": "{{trans('generals.end')}}" // This is the link to the last page
                }
            }
        });
    });
</script>
@endsection --}}

@extends('layouts/layoutMaster')
@section('title', trans('entities.stands_types'))
@section('title_header', trans('entities.stands_types'))

@section('button')
    <a href="{{ url('admin/stands-types/create') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.add') }}"><i class="fas fa-plus"></i></a>
@endsection

@section('path', trans('entities.stands_types'))

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body pb-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <ul class="nav nav-pills card-header-tabs mb-2" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#it-pages-tab" aria-controls="it-pages-tab"
                                    aria-selected="true">IT</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#en-pages-tab" aria-controls="en-pages-tab"
                                    aria-selected="false">EN</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="it-pages-tab" role="tabpanel" aria-labelledby="it-tab">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ trans('tables.name') }}</th>
                                        <th>{{ trans('tables.size') }} ({{ trans('generals.mq') }})</th>
                                        <th>{{ trans('tables.price') }} €</th>
                                        <th>{{ trans('tables.max_n_modules') }}</th>
                                        <th class="no-sort">{{ trans('tables.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($it as $stand)
                                        <tr>
                                            <td>{{ $stand->name }}</td>
                                            <td>{{ $stand->size }}</td>
                                            <td>{{ $stand->price }}</td>
                                            <td>{{ $stand->max_number_modules }}</td>
                                            <td>
                                                <div class="btn-group btn-group" role="group">
                                                    <a data-toggle="tooltip" data-placement="top"
                                                        title="{{ trans('generals.edit') }}" class="btn btn-default"
                                                        href="{{ route('stands-types.edit', $stand->id) }}"><i
                                                            class="fa fa-edit"></i></a>
                                                    <form
                                                        action="{{ route('stands-types.destroy', $stand->stand_type_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button data-toggle="tooltip" data-placement="top"
                                                            title="{{ trans('generals.delete') }}"
                                                            class="btn btn-default"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="en-pages-tab" role="tabpanel" aria-labelledby="en-tab">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ trans('tables.name') }}</th>
                                        <th>{{ trans('tables.size') }} ({{ trans('generals.mq') }})</th>
                                        <th>{{ trans('tables.price') }} €</th>
                                        <th>{{ trans('tables.max_n_modules') }}</th>
                                        <th class="no-sort">{{ trans('tables.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($en as $stand)
                                        <tr>
                                            <td>{{ $stand->name }}</td>
                                            <td>{{ $stand->size }}</td>
                                            <td>{{ $stand->price }}</td>
                                            <td>{{ $stand->max_number_modules }}</td>
                                            <td>
                                                <div class="btn-group btn-group" role="group">
                                                    <a data-toggle="tooltip" data-placement="top"
                                                        title="{{ trans('generals.edit') }}" class="btn btn-default"
                                                        href="{{ route('stands-types.edit', $stand->id) }}"><i
                                                            class="fa fa-edit"></i></a>
                                                    <form
                                                        action="{{ route('stands-types.destroy', $stand->stand_type_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button data-toggle="tooltip" data-placement="top"
                                                            title="{{ trans('generals.delete') }}"
                                                            class="btn btn-default"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <!-- Table -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        $('form button').on('click', function(e) {
            var $this = $(this);
            e.preventDefault();
            Swal.fire({
                title: "{!! trans('generals.confirm_remove') !!}",
                html: "{!! trans('generals.confirm_remove_both_sub') !!}",
                showCancelButton: true,
                confirmButtonText: "{{ trans('generals.confirm') }}",
                cancelButtonText: "{{ trans('generals.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $this.closest('form').submit();
                }
            })
        });
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                columnDefs: [{
                    orderable: false,
                    targets: "no-sort"
                }],
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10,
                "language": {
                    "search": "{{ trans('generals.search') }}",
                    "paginate": {
                        "first": "{{ trans('generals.start') }}",
                        "previous": "«",
                        "next": "»",
                        "last": "{{ trans('generals.end') }}"
                    }
                }
            });
        });
    </script>
@endsection
