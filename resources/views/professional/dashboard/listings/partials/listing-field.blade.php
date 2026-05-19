@php
    $fieldKey = $field ?? '';
    $fieldHelp = config('listing_field_help.' . $fieldKey, []);
    $description = trim((string) ($fieldHelp['description'] ?? ''));
    $example = trim((string) ($fieldHelp['example'] ?? ''));
    $tip = trim((string) ($fieldHelp['tip'] ?? ''));
    $placeholder = $placeholder ?? ($example !== '' ? $example : '');
    $inputType = $inputType ?? 'text';
    $rows = (int) ($rows ?? 4);
    $colClass = $colClass ?? 'col-md-6';
    $required = (bool) ($required ?? false);
    $inputName = $name ?? $fieldKey;
    $inputValue = $value ?? '';
    $inputAttrs = $inputAttributes ?? [];
    $fieldTitle = $label ?? ucfirst(str_replace('_', ' ', $fieldKey));
    $hasGuide = $description !== '' || $example !== '' || $tip !== '';
@endphp

<div class="{{ $colClass }}">
    <article class="listing-field-card h-100">
        <header class="listing-field-card__header">
            <h3 class="listing-field-card__title">
                {{ $fieldTitle }}
                @if ($required)
                    <span class="listing-required-mark" aria-hidden="true">*</span>
                @endif
            </h3>
        </header>

        @if ($hasGuide)
            <div class="listing-field-card__guide">
                @if ($description !== '')
                    <p class="listing-field-guide__desc">{{ $description }}</p>
                @endif
                @if ($example !== '')
                    <div class="listing-callout listing-callout--example" role="note">
                        <span class="listing-callout__icon" aria-hidden="true"><i class="feather-edit-3"></i></span>
                        <p class="listing-callout__text">
                            <em><strong>Example:</strong> {{ $example }}</em>
                        </p>
                    </div>
                @endif
                @if ($tip !== '')
                    <div class="listing-callout listing-callout--tip" role="note">
                        <span class="listing-callout__icon" aria-hidden="true"><i class="feather-zap"></i></span>
                        <p class="listing-callout__text">
                            <strong class="listing-field-guide__tip-label">Tip:</strong> {{ $tip }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <div class="listing-field-card__input-area">
            @if ($inputType === 'textarea')
                <textarea
                    class="form-control listing-field-card__input"
                    id="{{ $inputName }}"
                    name="{{ $inputName }}"
                    rows="{{ $rows }}"
                    placeholder="{{ $placeholder }}"
                    @if ($required) required @endif
                >{{ $inputValue }}</textarea>
            @else
                <input
                    type="{{ $inputType }}"
                    class="form-control listing-field-card__input"
                    id="{{ $inputName }}"
                    name="{{ $inputName }}"
                    value="{{ $inputValue }}"
                    placeholder="{{ $placeholder }}"
                    @if ($required) required @endif
                    @foreach ($inputAttrs as $attr => $attrValue)
                        {{ $attr }}="{{ $attrValue }}"
                    @endforeach
                >
            @endif
        </div>
    </article>
</div>
