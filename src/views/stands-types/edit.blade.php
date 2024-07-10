{{-- @extends('layouts.app')
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
 --}}

@extends('layouts/layoutMaster')
@section('title', trans('crud.edit', ['item' => $stand->name]))
@section('title_header', trans('crud.edit', ['item' => $stand->name]))

@section('buttons')
    <a href="{{ url('admin/stands-types') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection

@section('path', trans('entities.stands_types'))
@section('current', trans('crud.edit', ['item' => $stand->name]))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="myForm" action="{{ route('stands-types.update', $stand->id) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="stand_type_id" value="{{ $stand->stand_type_id }}">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.name') }}</label>
                                    <input type="text" name="name" class="form-control" value="{{ $stand->name }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.size') }}
                                        ({{ trans('generals.mq') }})</label>
                                    <input type="number" name="size" min="1" class="form-control"
                                        value="{{ $stand->size }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.description') }}</label>
                                    <div id="description" name="description" class="quillEditor"></div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.price') }}
                                        ({{ trans('generals.tax_excl') }})</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">&euro;</span>
                                        <input type="text" name="price" class="form-control"
                                            value="{{ $stand->price }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.max_n_modules') }}</label>
                                    <input type="number" name="max_number_modules" min="1" class="form-control"
                                        value="{{ $stand->max_number_modules }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
                                <a href="{{ url('admin/stands-types') }}"
                                    class="btn btn-label-secondary">{{ trans('generals.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/text-editor.js') }}"></script>
    <script>
        const editors = document.querySelectorAll('.quillEditor');
        initEditors(editors, 'myForm', {
            description: @json($stand->description),
        })
        const priceInput = document.querySelector('input[name="price"]');
        priceInput.addEventListener('keyup', handleInputEvent);
        priceInput.addEventListener('change', handleInputEvent);

        const handleInputEvent = (e) => {
            if (e.keyCode === 188) {
                e.preventDefault();
                this.value = this.value.replace(/,/g, '.');
            }
        }
        // $(document).ready(function() {
        //     // $('.summernote').summernote({
        //     //     disableDragAndDrop: true,
        //     // });
        //     // $('.note-btn-group.btn-group.note-insert').hide()

        //     $('input[name="price"]').on('keyup change', function(e) {
        //         if (e.keyCode == 188) {
        //             e.preventDefault();
        //             this.value = this.value.replace(/,/g, '.');
        //         }
        //     });

        //     // const fullEditorDesc = createFullEditor('#description-editor');

        //     // // Inizializza i campi
        //     // var initDesc = {!! json_encode(old('description', $stand->description)) !!};
        //     // var desc = JSON.parse(initDesc);
        //     // fullEditorDesc.setContents(desc);

        //     // // Aggiorna i campi nascosti con il contenuto degli editor
        //     // const form = document.getElementById('myForm');
        //     // form.addEventListener('submit', () => {
        //     //     desc = fullEditorDesc.getContents();
        //     //     document.getElementById('description').value = JSON.stringify(desc);
        //     // });
        // });
    </script>
@endsection
