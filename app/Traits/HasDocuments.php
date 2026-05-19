<?php

namespace App\Traits;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasDocuments
{
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * The primary thumbnail / card image for this model.
     * Stored in the documents table with collection = 'card'.
     */
    public function thumbnail(): MorphOne
    {
        return $this->document('card');
    }

    public function document(string $collection): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('collection', $collection)
            ->orderBy('sort_order');
    }

    public function documentCollection(string $collection): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable')
            ->where('collection', $collection)
            ->orderBy('sort_order');
    }
}
