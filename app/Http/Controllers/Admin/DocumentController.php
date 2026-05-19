<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {
    }

    public function index(): View
    {
        $documents = Document::with('documentable')->latest()->get();

        return view('admin.documents.index', compact('documents'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $document = Document::findOrFail($id);
        $this->fileUpload->deleteDocument($document);

        return redirect()->back()->with('success', 'Document deleted.');
    }
}
