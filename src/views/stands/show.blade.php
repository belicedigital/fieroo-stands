@extends('layouts.app')
@section('title', trans('entities.furnishings') . ' ' . $code_module->code)
@section('title_header', trans('entities.furnishings') . ' ' . $code_module->code)
@section('buttons')
<a href="{{ url('admin/stands') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            @foreach ($list as $l)
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <a href="javascript:void(0);" onclick="assignImg(this)" role="button" data-toggle="modal" data-target="#modalImg"><img src="{{asset('upload/furnishings/'.$l->file_path)}}" class="w-100" style="height:250px;object-fit:contain;"></a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <p class="card-text"><strong>{{trans('tables.description')}}</strong>: {{$l->description}}</p>
                            <p class="card-text"><strong>{{trans('tables.size')}}</strong>: {{$l->size}}</p>
                            <p class="card-text"><strong>{{trans('tables.qty')}}</strong>: {{$l->qty}}</p>
                            <p class="card-text"><strong>{{trans('tables.is_supplied')}}</strong>: {{$l->is_supplied ? ($l->extra_price ? trans('generals.no') : trans('generals.yes')) : trans('generals.no')}}</p>
                            <p class="card-text"><strong>{{trans('tables.max_supplied')}}</strong>: {{$l->max}}</p>
                            <p class="card-text"><strong class="text-muted">{{trans('tables.price')}}</strong>: {{$l->price}} €</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-4">
            <div class="callout callout-info d-flex flex-column align-items-center justify-content-center">
                <h3>{{trans('generals.total')}}: {{$total}} €</h3>
                @if($total <= 0)
                <p class="bg-info p-3 mt-3 text-center">{{trans('messages.free_furniture')}}</p>
                @endif
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
    const assignImg = (el) => {
        let src = $(el).find('img').attr('src')
        console.log(src)
        $('#modalImg').find('img').attr('src', src)

    }
</script>
@endsection