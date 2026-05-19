@extends('admin.layout')

@section('title', 'Edit email template')
@section('page_title', 'Edit email template')

@push('styles')
    <style>
        .et-editor-card .card-header {
            border-bottom: 0;
            background: transparent;
        }

        .et-wysiwyg-textarea {
            min-height: 68vh;
            width: 100%;
            font-family: Arial, sans-serif;
        }
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-semibold mb-2">Please review the form errors.</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.email-templates.index') }}">Email templates</a></li>
                        <li class="breadcrumb-item">{{ $viewName }}</li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                <form method="POST" action="{{ route('admin.email-templates.update', $templateKey) }}" id="emailTemplateEditForm">
                    @csrf
                    @method('PUT')

                    <div class="card stretch stretch-full et-editor-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-1">
                                <i class="feather-code me-2 text-primary"></i>{{ $viewName }}
                            </h5>
                            <p class="text-muted mb-0">
                                Editing <code>{{ $filename }}</code>. This updates the Blade email view directly.
                            </p>
                        </div>
                        <div class="card-body pt-0">
                            <label for="content" class="form-label fw-semibold">Template editor</label>
                            <div class="form-text mb-2">
                                Visual editor mode enabled. Blade placeholders are preserved on save.
                            </div>
                            <textarea id="content" name="content" class="form-control et-wysiwyg-textarea @error('content') is-invalid @enderror" spellcheck="false" required>{{ old('content', $content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-light-brand px-4">
                            <i class="feather-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="feather-save me-1"></i> Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('user/assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script>
        (function () {
            var textarea = document.getElementById('content');
            var form = document.getElementById('emailTemplateEditForm');
            if (!textarea || !form || typeof CKEDITOR === 'undefined') {
                return;
            }

            var editor = CKEDITOR.replace('content', {
                height: 640,
                valid_elements: '*[*]',
                allowedContent: true,
                extraAllowedContent: '*(*);*{*}',
                entities: false,
                basicEntities: false,
                protectedSource: [
                    /\{\{[\s\S]*?\}\}/g,
                    /\{!![\s\S]*?!!\}/g,
                    /@\w+(?:\s*\(.*?\))?/g
                ]
            });

            form.addEventListener('submit', function () {
                if (editor && typeof editor.updateElement === 'function') {
                    editor.updateElement();
                }
            });
        })();
    </script>
@endpush

