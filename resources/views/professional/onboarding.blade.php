@extends('professional.dashboard.layouts.app')

@php
    $displayName = $user->full_name ?: $user->name ?: 'Professional';
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
    $roleLabel = str_replace('_', ' ', ucwords($user->role, '_'));
@endphp

@section('title', 'MagnateHub || Complete Your Profile')
@section('page_title', 'Complete Your Profile')
@section('page_summary', 'Please answer a few quick questions so we can tailor your experience.')

@section('styles')
<style>
    .onboarding-card {
        margin: 0 auto;
    }
    .question-block {
        padding: 20px 0;
        border-bottom: 1px solid #eef1f6;
    }
    .question-block:last-child {
        border-bottom: none;
    }
    .question-label {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 10px;
        display: block;
    }
    .required-star {
        color: #ef4444;
        margin-left: 3px;
    }
    .onboarding-progress {
        height: 6px;
        border-radius: 99px;
        background: #e5e7eb;
        margin-bottom: 28px;
        overflow: hidden;
    }
    .onboarding-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(90deg, #560ce3, #6366f1);
        width: 0;
        transition: width 0.8s ease;
    }
</style>
@endsection

@section('content')
<main class="nxl-container">
    <div class="nxl-content">

        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Welcome, {{ $displayName }}!</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">Onboarding</li>
                    <li class="breadcrumb-item">Profile Questions</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="onboarding-card">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="fw-semibold mb-1">Please fix the following:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <div>
                            <h5 class="card-title mb-1">{{ $roleLabel }} Profile Questions</h5>
                            <div class="text-muted fs-12">These questions help us personalise your experience. Required fields are marked <span class="text-danger">*</span></div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-soft-primary text-primary text-uppercase">{{ $roleLabel }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="onboarding-progress">
                            <div class="onboarding-progress-fill" data-total="{{ $questions->count() }}"></div>
                        </div>

                        <form id="onboarding-form" method="POST" action="{{ route('professional.onboarding.submit') }}">
                            @csrf

                            @foreach ($questions as $question)
                                @php
                                    $existingAnswer = $existing->get($question->id);
                                    $existingText   = old('answers.' . $question->id, $existingAnswer?->answer_text);
                                    $existingOpts   = old('answers.' . $question->id, $existingAnswer?->selected_option_ids ?? []);
                                @endphp

                                <div class="question-block">
                                    <label class="question-label">
                                        {{ $question->question }}
                                        @if ($question->is_required)<span class="required-star">*</span>@endif
                                    </label>

                                    @if ($errors->has('answers.' . $question->id))
                                        <div class="text-danger fs-12 mb-2">{{ $errors->first('answers.' . $question->id) }}</div>
                                    @endif

                                    @if ($question->type === 'text')
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="answers[{{ $question->id }}]"
                                            value="{{ $existingText }}"
                                            {{ $question->is_required ? 'data-required="1" required' : '' }}
                                            placeholder="Your answer..."
                                        >

                                    @elseif ($question->type === 'textarea')
                                        <textarea
                                            class="form-control"
                                            name="answers[{{ $question->id }}]"
                                            rows="3"
                                            {{ $question->is_required ? 'data-required="1" required' : '' }}
                                            placeholder="Your answer..."
                                        >{{ $existingText }}</textarea>

                                    @elseif ($question->type === 'single_choice')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($question->options as $option)
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        name="answers[{{ $question->id }}]"
                                                        id="opt_{{ $option->id }}"
                                                        value="{{ $option->id }}"
                                                        {{ in_array($option->id, (array) $existingOpts) ? 'checked' : '' }}
                                                        {{ $question->is_required ? 'data-required="1" required' : '' }}
                                                    >
                                                    <label class="form-check-label" for="opt_{{ $option->id }}">
                                                        {{ $option->label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif ($question->type === 'multi_choice')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($question->options as $option)
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="answers[{{ $question->id }}][]"
                                                        id="opt_{{ $option->id }}"
                                                        value="{{ $option->id }}"
                                                        {{ in_array($option->id, (array) $existingOpts) ? 'checked' : '' }}
                                                        {{ $question->is_required ? 'data-required="1"' : '' }}
                                                    >
                                                    <label class="form-check-label" for="opt_{{ $option->id }}">
                                                        {{ $option->label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end pt-4">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="feather-check-circle me-2"></i>
                                    Save & Continue to Dashboard
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(function () {
    var $form      = $('#onboarding-form');
    var $fill      = $('.onboarding-progress-fill');
    var total      = parseInt($fill.data('total'), 10) || 1;

    /* ── Progress recalculation ── */
    function countAnswered() {
        var answered = 0;

        // text / textarea
        $form.find('input[type="text"], textarea').each(function () {
            if ($.trim($(this).val()) !== '') answered++;
        });

        // radio groups — count each named group separately
        var radioGroups = {};
        $form.find('input[type="radio"]').each(function () {
            radioGroups[this.name] = radioGroups[this.name] || false;
            if (this.checked) radioGroups[this.name] = true;
        });
        $.each(radioGroups, function (_, answered_) { if (answered_) answered++; });

        // checkbox groups
        var checkGroups = {};
        $form.find('input[type="checkbox"]').each(function () {
            var base = this.name.replace(/\[\]$/, '');
            checkGroups[base] = checkGroups[base] || false;
            if (this.checked) checkGroups[base] = true;
        });
        $.each(checkGroups, function (_, answered_) { if (answered_) answered++; });

        return answered;
    }

    function updateProgress() {
        var answered = countAnswered();
        var pct = Math.min(Math.round((answered / total) * 100), 100);
        $fill.css('width', pct + '%');
    }

    // Trigger on every input event
    $form.on('change input', 'input, textarea, select', function () {
        updateProgress();
    });

    // Initial fill on load (pre-filled answers)
    requestAnimationFrame(updateProgress);

    /* ── jQuery Validate ── */
    $form.validate({
        errorClass: 'text-danger fs-12 d-block mt-1',
        errorElement: 'span',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function (error, element) {
            if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                error.insertAfter(element.closest('.d-flex.flex-wrap.gap-2'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            form.submit();
        }
    });

    // Add rules dynamically for required fields
    $form.find('[data-required="1"]').each(function () {
        $(this).rules('add', { required: true, messages: { required: 'This field is required.' } });
    });
});
</script>
@endsection
