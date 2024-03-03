@extends('layouts.app')
@section('title', trans('crud.new', ['obj' => trans('entities.stand_type')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.stand_type')]))
@section('buttons')
    <a href="{{ url('admin/stands-types') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection
@section('content')
    <div class="container">
        @if ($errors->any())
            @include('admin.partials.errors', ['errors' => $errors])
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-tabs">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="it-tab" data-toggle="pill" href="#it-pages-tab"
                                    role="tab" aria-controls="it-pages-tabe" aria-selected="true">IT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="en-tab" data-toggle="pill" href="#en-pages-tab" role="tab"
                                    aria-controls="en-pages-tab" aria-selected="false">EN</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stands-types.store') }}" method="POST">
                            @csrf
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane fade show active" id="it-pages-tab" role="tabpanel"
                                    aria-labelledby="it-tab">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.name') }}</strong>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.size') }} ({{ trans('generals.mq') }})</strong>
                                                <input type="number" min="1" name="size" class="form-control"
                                                    value="{{ old('size') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('forms.description') }}</strong>
                                                <textarea name="description" class="form-control summernote" value="{{ old('description') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.price') }}
                                                    ({{ trans('generals.tax_excl') }})</strong>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">&euro;</span>
                                                    </div>
                                                    <input type="text" name="price" class="form-control"
                                                        value="{{ old('price') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.max_n_modules') }}</strong>
                                                <input type="number" name="max_number_modules" min="1"
                                                    class="form-control" value="{{ old('max_number_modules') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="en-pages-tab" role="tabpanel" aria-labelledby="en-tab">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.name') }}</strong>
                                                <input type="text" name="name_en" class="form-control"
                                                    value="{{ old('name_en') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.size') }} ({{ trans('generals.mq') }})</strong>
                                                <input type="number" min="1" name="size_en" class="form-control"
                                                    value="{{ old('size_en') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('forms.description') }}</strong>
                                                <textarea name="description_en" class="form-control summernote" value="{{ old('description_en') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.price') }}
                                                    ({{ trans('generals.tax_excl') }})</strong>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">&euro;</span>
                                                    </div>
                                                    <input type="text" name="price_en" class="form-control"
                                                        value="{{ old('price_en') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>{{ trans('tables.max_n_modules') }}</strong>
                                                <input type="number" name="max_number_modules_en" min="1"
                                                    class="form-control" value="{{ old('max_number_modules_en') }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                disableDragAndDrop: true,
            });
            $('.note-btn-group.btn-group.note-insert').hide()

            $('input').on('keyup change', function(e) {
                let $type = $(this).attr('type');
                let $name = $(this).attr('name');
                if ($type == 'number' || $name == 'price') {
                    if ($name == 'price' && e.keyCode == 188) {
                        e.preventDefault();
                        this.value = this.value.replace(/,/g, '.');
                    }
                    $('input[name="' + $name + '_en"]').val($(this).val());
                } else {
                    $('input[name="' + $name + '_en"]').val($(this).val() + '_EN');
                }
            });
            $('textarea').on('keyup change', function(e) {
                let $name = $(this).attr('name');
                $('textarea[name="' + $name + '_en"]').val($(this).val() + '_EN');
            });
            $('select').on('change', function() {
                let $name = $(this).attr('name');
                $('select[name="' + $name + '_en"]').val($(this).val());
            });
        });
    </script>
@endsection
