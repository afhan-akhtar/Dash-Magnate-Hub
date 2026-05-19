<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = (int) ($this->input('type') ?: session()->get('type', 0));
        $isSaleListing = in_array($type, [2, 4], true);
        $isCapitalRaiseListing = $type === 3;
        $isGuidedListing = $isSaleListing || $isCapitalRaiseListing;

        return [
            'type' => ['required', 'integer', Rule::in([1, 2, 3, 4])],
            'name' => [$isSaleListing ? 'nullable' : 'required', 'string', 'max:255'],
            'category_id' => [$isGuidedListing ? 'required' : 'nullable', 'integer', 'exists:categories,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'description' => ['nullable', 'string'],
            'price' => [$isSaleListing ? 'required' : 'nullable', 'numeric', 'min:0'],
            'trading' => [$isSaleListing ? 'required' : 'nullable', 'string', 'max:255'],
            'earning_type' => ['nullable', 'string', 'max:255'],
            'stock_level' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'location_information' => ['nullable', 'string'],
            'skills' => ['nullable', 'string'],
            'potential' => ['nullable', 'string'],
            'hours' => ['nullable', 'string'],
            'staff' => ['nullable', 'string'],
            'lease' => ['nullable', 'string'],
            'business_established' => ['nullable', 'string', 'max:255'],
            'training' => ['nullable', 'string'],
            'awards' => ['nullable', 'string'],
            'reason_for_sale' => ['nullable', 'string'],
            'seeking_investment' => [$isSaleListing ? 'nullable' : 'required', 'string', 'max:255'],
            'reported_sales' => ['nullable', 'string', 'max:255'],
            'run_rate_sales' => ['nullable', 'string', 'max:255'],
            'ebitda_margin' => ['nullable', 'string', 'max:255'],
            'industry' => [$isSaleListing ? 'nullable' : 'required', 'string', 'max:255'],
            'assets_or_collateral' => ['nullable', 'string', 'max:255'],
            'interested_to_connect_with_advisors' => ['nullable', 'string', 'max:255'],
            'business_overview' => [$isSaleListing ? 'nullable' : 'required', 'string'],
            'products_and_services_overview' => ['nullable', 'string'],
            'assets_overview' => ['nullable', 'string'],
            'facilities_overview' => ['nullable', 'string'],
            'capitalization_overview' => ['nullable', 'string'],
            'franchise' => ['boolean'],
            'multiple_locations' => ['boolean'],
            'sold' => ['boolean'],
            'under_offer' => ['boolean'],
            'urgent_sale' => ['boolean'],
            'card' => ['nullable', 'image', 'max:5120'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:5120'],
            'remove_card' => ['nullable', 'boolean'],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['integer', 'exists:documents,id'],
        ];
    }
}
