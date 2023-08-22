@extends('layouts.app')
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
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="it-tab" data-toggle="pill" href="#it-pages-tab" role="tab" aria-controls="it-pages-tabe" aria-selected="true">IT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="en-tab" data-toggle="pill" href="#en-pages-tab" role="tab" aria-controls="en-pages-tab" aria-selected="false">EN</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0 py-3">
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
                                    @foreach($it as $stand)
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
                                    @foreach($en as $stand)
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
@endsection