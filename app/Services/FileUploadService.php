<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function upload(
        UploadedFile $file,
        Model $model,
        string $collection,
        string $folder = 'uploads',
        int $sortOrder = 0,
        bool $appendModelDirectory = true,
    ): Document {
        $filename = $this->generateFilename($model, $file);
        $directory = $appendModelDirectory
            ? $folder . '/' . Str::plural(Str::snake(class_basename($model)))
            : $folder;
        $path = $file->storeAs($directory, $filename, 'public');

        return $model->documents()->create([
            'collection'    => $collection,
            'path'          => $path,
            'url'           => $this->relativePublicStorageUrl($path),
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'sort_order'    => $sortOrder,
        ]);
    }

    public function uploadMany(
        array $files,
        Model $model,
        string $collection,
        string $folder = 'uploads',
        bool $appendModelDirectory = true,
    ): array {
        $documents = [];
        foreach ($files as $index => $file) {
            $documents[] = $this->upload($file, $model, $collection, $folder, $index, $appendModelDirectory);
        }
        return $documents;
    }

    public function deleteDocument(Document $document): void
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();
    }

    public function replaceDocument(
        UploadedFile $file,
        Model $model,
        string $collection,
        string $folder = 'uploads',
        bool $appendModelDirectory = true,
    ): Document {
        $existing = $model->document($collection)->first();
        if ($existing) {
            $this->deleteDocument($existing);
        }
        return $this->upload($file, $model, $collection, $folder, 0, $appendModelDirectory);
    }

    private function generateFilename(Model $model, UploadedFile $file): string
    {
        return $model->id . '-' . time() . '-' . rand(10000000, 99999999) . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Root-relative URL for the public disk (no scheme/host), e.g. /storage/uploads/...
     */
    private function relativePublicStorageUrl(string $path): string
    {
        return '/storage/' . str_replace('\\', '/', ltrim($path, '/'));
    }
}
