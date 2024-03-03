@extends('layouts.app')
@section('title', trans('crud.edit', ['item' => $stand->name]))
@section('title_header', trans('crud.edit', ['item' => $stand->name]))
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
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('stands-types.update', $stand->id) }}" method="POST">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" name="stand_type_id" value="{{ $stand->stand_type_id }}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.name') }}</strong>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $stand->name }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.size') }} ({{ trans('generals.mq') }})</strong>
                                        <input type="number" name="size" min="1" class="form-control"
                                            value="{{ $stand->size }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.description') }}</strong>
                                        <textarea name="description" class="form-control summernote">{{ $stand->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.price') }} ({{ trans('generals.tax_excl') }})</strong>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">&euro;</span>
                                            </div>
                                            <input type="text" name="price" class="form-control"
                                                value="{{ $stand->price }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.max_n_modules') }}</strong>
                                        <input type="number" name="max_number_modules" min="1" class="form-control"
                                            value="{{ $stand->max_number_modules }}">
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

            $('input[name="price"]').on('keyup change', function(e) {
                if (e.keyCode == 188) {
                    e.preventDefault();
                    this.value = this.value.replace(/,/g, '.');
                }
            });
        });
    </script>
@endsection
