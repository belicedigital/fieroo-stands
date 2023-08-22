@extends('layouts.app')
@section('title', trans('entities.furnishings'). ' ' . $code)
@section('title_header', trans('entities.furnishings'). ' ' . $code)
@section('buttons')
<a href="{{url('admin/stands')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
@endsection
@section('content')
<input type="hidden" name="code_module" value="{{$code_module}}">
<input type="hidden" name="stand_type_id" value="{{$stand_type_id}}">

<div class="container-fluid">
    @if (Session::has('success'))
    @include('admin.partials.success')
    @endif
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <p class="m-0">{!! trans('generals.furnishings_info_alert_list', ['code' => $code]) !!}</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive p-0 py-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>{{trans('tables.description')}}</th>
                                <th>{{trans('tables.is_supplied')}}</th>
                                <th>{{trans('tables.price')}}</th>
                                <th>{{trans('tables.size')}}</th>
                                <th class="no-sort">{{trans('tables.color')}}</th>
                                <th class="no-sort">{{trans('tables.min_supplied')}}</th>
                                <th class="no-sort">{{trans('tables.max_supplied')}}</th>
                                <th class="no-sort">{{trans('tables.image')}}</th>
                                <th class="no-sort">{{trans('tables.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $l)
                            <tr data-id="{{$l->id}}" class="{{$l->is_supplied && $l->min > 0 ? 'bg-info' : ''}}">
                                <td>{{$l->description}}</td>
                                <td name="is_supplied">{{$l->is_supplied ? trans('generals.yes') : trans('generals.no')}}</td>
                                <td name="price"><span>{{$l->price}}</span> &euro;</td>
                                <td name="size">{{$l->size}}</td>
                                <td>
                                    @if(count($l->variants) > 0)
                                    <select name="variant" class="form-control" {{ checkItemInCart($code_module, $l->id) ? 'readonly disabled' : '' }}>
                                        <option data-is-variant="false" data-opt-id="{{$l->id}}" value="{{$l->color}}">{{$l->color}}</option>
                                        @foreach($l->variants as $variant)
                                        <option data-is-variant="true" data-opt-id="{{$variant->id}}" value="{{$variant->color}}">{{$variant->color}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    {{$l->color}}
                                    @endif
                                </td>
                                <td>
                                    <input type="number" name="qty" class="form-control" min="{{$l->min}}" value="{{$l->min}}" {{ checkItemInCart($code_module, $l->id) ? 'readonly' : '' }}>
                                </td>
                                <td name="qty_max_supplied">
                                    {{$l->is_supplied ? ($l->extra_price ? 'N/A' : $l->max) : 'N/A'}}
                                </td>
                                <td>
                                    <a href="javascript:void(0);" onclick="assignImg(this)" role="button" data-toggle="modal" data-target="#modalImg"><img src="{{asset('upload/furnishings/'.$l->file_path)}}" class="table-img"></a>
                                </td>
                                <td>
                                    <div class="btn-group btn-group" role="group">
                                        <a add-to-cart class="btn btn-default {{ checkItemInCart($code_module, $l->id) ? 'disabled' : '' }}" href="javascript:void(0);" onclick="addToCart(this)"><i class="fas fa-cart-plus"></i></a>
                                        <a remove-to-cart class="btn btn-default {{ !checkItemInCart($code_module, $l->id) ? 'disabled' : '' }}" href="javascript:void(0);" onclick="removeToCart(this)"><i class="fas fa-trash-alt"></i></a>
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
<div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="modalImgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="w-100">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const removeToCart = (el) => {
        let $this = $(el)
        let row = $this.closest('tr')
        let id = row.data('id')
        let is_variant = row.find('select[name="variant"] option:selected').data('is-variant')
        common_request.post('/admin/cart/remove-to-cart', {
            id: id,
            code_module: $('input[name="code_module"]').val(),
            is_variant: is_variant
        })
        .then(response => {
            let data = response.data
            if(data.status) {
                $('[cart]').find('span').text(data.count);
                $('tr[data-id="'+id+'"]').find('[add-to-cart]').removeClass('disabled')
                $('tr[data-id="'+id+'"]').find('[remove-to-cart]').addClass('disabled')
                $('tr[data-id="'+id+'"]').find('[name="variant"]').removeAttr('readonly')
                $('tr[data-id="'+id+'"]').find('[name="variant"]').removeAttr('disabled')
                $('tr[data-id="'+id+'"]').find('[name="qty"]').removeAttr('readonly')
                toastr.success(data.message)
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    }
    const addToCart = (el) => {
        let $this = $(el)
        let row = $this.closest('tr')
        let id = row.data('id')
        let qty = $('tr[data-id="'+id+'"]').find('input[name="qty"]').val()
        let is_variant = row.find('select[name="variant"] option:selected').data('is-variant')
        if(qty > 0) {
            common_request.post('/admin/cart/add-to-cart', {
                id: id,
                code_module: $('input[name="code_module"]').val(),
                qty: qty,
                color: row.find('select[name="variant"] option:selected').val(),
                is_variant: is_variant
            })
            .then(response => {
                let data = response.data
                if(data.status) {
                    $('[cart]').find('span').text(data.count);
                    $('tr[data-id="'+id+'"]').find('[add-to-cart]').addClass('disabled')
                    $('tr[data-id="'+id+'"]').find('[remove-to-cart]').removeClass('disabled')
                    $('tr[data-id="'+id+'"]').find('[name="variant"]').attr('readonly', true)
                    $('tr[data-id="'+id+'"]').find('[name="qty"]').attr('readonly', true)
                    toastr.success(data.message)
                } else {
                    toastr.error(data.message)
                }
            })
            .catch(error => {
                toastr.error(error)
                console.log(error)
            })
        } else {
            toastr.error("{{trans('messages.min_qty_to_cart')}}")
        }
    }
    const assignImg = (el) => {
        let src = $(el).find('img').attr('src')
        console.log(src)
        $('#modalImg').find('img').attr('src', src)

    }
    const updateData = (row, id, is_variant) => {
        common_request.post('/admin/stands/getData', {
            id: id,
            stand_type_id: $('input[name="stand_type_id"]').val(),
            is_variant: is_variant
        })
        .then(response => {
            let data = response.data
            if(data.status) {
                console.log(data.data)
                let extra_price = data.data.extra_price ? "{{trans('generals.no')}}" : "{{trans('generals.yes')}}"
                if(data.data.extra_price) {
                    row.removeClass('bg-info')
                    row.find('input[name="qty"]').attr('min', '0')
                    row.find('input[name="qty"]').val('0')
                    row.find('td[name="qty_max_supplied"]').text('N/A')
                } else {
                    if(data.data.min > 0) {
                        row.addClass('bg-info')
                    }
                    row.find('input[name="qty"]').attr('min', data.data.min)
                    row.find('input[name="qty"]').val(data.data.min)
                    row.find('td[name="qty_max_supplied"]').text(data.data.max)
                }
                row.find('td[name="is_supplied"]').text(extra_price)
                row.find('td[name="price"] span').text(data.data.price)
                row.find('td[name="size"]').text(data.data.size)
                let path = "{{asset('upload/furnishings')}}" + '/' + data.data.file_path
                row.find('td a > img').attr('src', path)
                row.attr('data-id', data.data.id)
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    }

    const checkItemInCart = (id) => {
        common_request.post('/admin/cart/check-item-in-cart', {
            id: id,
            code_module: $('input[name="code_module"]').val()
        })
        .then(response => {
            let data = response.data
            if(data.status) {
                let row = $('tr[data-id="'+id+'"]')
                row.find('[add-to-cart]').removeClass('disabled')
                row.find('[remove-to-cart]').addClass('disabled')
                if(data.check) {
                    row.find('[add-to-cart]').addClass('disabled')
                    row.find('[remove-to-cart]').removeClass('disabled')
                    row.find('[name="variant"]').attr({
                        'readonly': true,
                        'disabled': true
                    })
                    row.find('[name="qty"]').attr('readonly', true)
                }
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    } 

    $(document).ready(function() {

        $('select[name="variant"]').on('change', function() {
            let $this = $(this)
            let row = $this.closest('tr')
            let id = $this.find('option:selected').data('opt-id')
            let is_variant = $this.find('option:selected').data('is-variant')
            updateData(row, id, is_variant)
            checkItemInCart(id)
        });

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