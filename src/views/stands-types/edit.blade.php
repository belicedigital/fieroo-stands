@extends('layouts/layoutMaster')
@section('title', trans('crud.edit', ['item' => $stand->name]))
@section('title_header', trans('crud.edit', ['item' => $stand->name]))

@section('button')
    <a href="{{ url('admin/stands-types') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
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
                        <input type="hidden" id="selected_categories_ids" name="selected_categories_ids"
                            value={{ $selected_categories_ids }}>
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
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('entities.categories') }}</label>
                                    <select id="category_id" name="category_id[]" class="form-control" multiple>
                                        <option value="">{{ trans('forms.select_choice') }}</option>
                                        {{-- @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
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
            description: {!! json_encode($stand->description) !!},
        })

        const handleInputEvent = (e) => {
            if (e.keyCode === 188) {
                e.preventDefault();
                e.target.value = e.target.value.replace(/,/g, '.');
            }
        }

        const priceInput = document.querySelector('input[name="price"]');
        priceInput.addEventListener('keyup', handleInputEvent);
        priceInput.addEventListener('change', handleInputEvent);

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

        const initCategories = () => {
            common_request.post('/admin/categories/getSelectList')
                .then(response => {
                    let data = response.data
                    if (data.status) {
                        $.each(data.data, function(index, value) {
                            let opt = document.createElement('option')
                            opt.text = value.name
                            opt.value = value.id
                            if ($('#selected_categories_ids').val().includes(value.id)) {
                                opt.selected = true
                            }
                            $('#category_id').append(opt)
                        })
                        $('#category_id').select2();
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
            initCategories()
        })
    </script>
@endsection
