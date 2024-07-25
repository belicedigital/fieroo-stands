@extends('layouts/layoutMaster')
@section('title', trans('crud.new', ['obj' => trans('entities.stand_type')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.stand_type')]))

@section('button')
    <a href="{{ url('admin/stands-types') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection

@section('path', trans('entities.stands_types'))
@section('current', trans('crud.new', ['obj' => trans('entities.stand_type')]))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-tabs">
                <div class="card-body pb-0">
                    <ul class="nav nav-pills card-header-tabs mb-2" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#it-pages-tab"
                                role="tab" aria-selected="true">IT</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#en-pages-tab" role="tab"
                                aria-selected="false">EN</button>
                        </li>
                    </ul>
                    <form id="myForm" action="{{ route('stands-types.store') }}" method="POST">
                        @csrf
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="it-pages-tab" role="tabpanel"
                                aria-labelledby="it-tab">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fs-6 fw-bolder">{{ trans('tables.name') }}</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fs-6 fw-bolder">{{ trans('tables.size') }}
                                                ({{ trans('generals.mq') }})</label>
                                            <input type="number" min="1" name="size" class="form-control"
                                                value="{{ old('size') }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label
                                                class="form-label fs-6 fw-bolder">{{ trans('forms.description') }}</label>
                                            <div id="description" name="description" class="quillEditor"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label class="form-label fs-6 fw-bolder">{{ trans('tables.price') }}
                                                    ({{ trans('generals.tax_excl') }})</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">&euro;</span>
                                                    <input type="text" name="price" class="form-control"
                                                        value="{{ old('price') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label
                                                    class="form-label fs-6 fw-bolder">{{ trans('tables.max_n_modules') }}</label>
                                                <input type="number" name="max_number_modules" min="1"
                                                    class="form-control" value="{{ old('max_number_modules') }}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label
                                                    class="form-label fs-6 fw-bolder">{{ trans('entities.categories') }}</label>
                                                <select id="category_id" name="category_id[]" class="form-control" multiple>
                                                    <option value="">{{ trans('forms.select_choice') }}</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="en-pages-tab" role="tabpanel" aria-labelledby="en-tab">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fs-6 fw-bolder">{{ trans('tables.name') }}</label>
                                            <input type="text" name="name_en" class="form-control"
                                                value="{{ old('name_en') }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label fs-6 fw-bolder">{{ trans('tables.size') }}
                                                ({{ trans('generals.mq') }})</label>
                                            <input type="number" min="1" name="size_en" class="form-control"
                                                value="{{ old('size_en') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <label
                                                class="form-label fs-6 fw-bolder">{{ trans('forms.description') }}</label>
                                            <div id="description_en" name="description_en" class="quillEditor"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label class="form-label fs-6 fw-bolder">{{ trans('tables.price') }}
                                                    ({{ trans('generals.tax_excl') }})</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">&euro;</span>
                                                    <input type="text" name="price_en" class="form-control"
                                                        value="{{ old('price_en') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label
                                                    class="form-label fs-6 fw-bolder">{{ trans('tables.max_n_modules') }}</label>
                                                <input type="number" name="max_number_modules_en" min="1"
                                                    class="form-control" value="{{ old('max_number_modules_en') }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
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
        initEditors(editors, 'myForm', false, {
            input: 'title',
            textarea: ['description'],
        })

        // document.addEventListener("DOMContentLoaded", function() {
        // Set editors
        // const editDesc = createFullEditor('#description-editor');
        // const editDescEn = createFullEditor('#description_en-editor');

        //Sincronizzazione del contenuto degli editor di Quill
        // editDesc.on('text-change', () => {
        //     const descriptionContent = editDesc.root.innerHTML
        //     editDescEn.root.innerHTML = descriptionContent.replace(/(<\/[\w\s="':;]+>)$/, '_EN$1');
        // });

        // const form = document.getElementById('myForm');
        // form.addEventListener('submit', () => {
        //     const desc = editDesc.getContents();
        //     document.getElementById('description').value = JSON.stringify(desc);
        //     const descEn = editDescEn.getContents();
        //     document.getElementById('description_en').value = JSON.stringify(descEn);;
        // })
        // });

        const noteBtnGroupElements = document.querySelectorAll('.note-btn-group.btn-group.note-insert');
        noteBtnGroupElements.forEach(element => {
            element.style.display = 'none';
        });

        const inputElements = document.querySelectorAll('input');
        inputElements.forEach(element => {
            element.addEventListener('keyup', handleInputEvent);
            element.addEventListener('change', handleInputEvent);
        });

        // const textareaElements = document.querySelectorAll('textarea');
        // textareaElements.forEach(element => {
        //     element.addEventListener('keyup', handleTextareaEvent);
        //     element.addEventListener('change', handleTextareaEvent);
        // });

        const selectElements = document.querySelectorAll('select');
        selectElements.forEach(element => {
            element.addEventListener('change', handleSelectEvent);
        });

        function handleInputEvent(event) {
            const type = this.getAttribute('type');
            const name = this.getAttribute('name');
            if (type === 'number' || name === 'price') {
                if (name === 'price' && event.keyCode === 188) {
                    event.preventDefault();
                    this.value = this.value.replace(/,/g, '.');
                }
                const correspondingInput = document.querySelector(`input[name="${name}_en"]`);
                correspondingInput.value = this.value;
            } else {
                const correspondingInput = document.querySelector(`input[name="${name}_en"]`);
                correspondingInput.value = this.value + '_EN';
            }
        }

        // function handleTextareaEvent(event) {
        //     const name = this.getAttribute('name');
        //     const correspondingTextarea = document.querySelector(`textarea[name="${name}_en"]`);
        //     correspondingTextarea.value = this.value + '_EN';
        // }

        function handleSelectEvent(event) {
            const name = this.getAttribute('name');
            const correspondingSelect = document.querySelector(`select[name="${name}_en"]`);
            correspondingSelect.value = this.value;
        }
    </script>
@endsection
