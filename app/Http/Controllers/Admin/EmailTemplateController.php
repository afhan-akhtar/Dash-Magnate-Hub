<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        $mailViewDirectory = $this->mailViewDirectory();
        $templates = collect(File::isDirectory($mailViewDirectory) ? File::files($mailViewDirectory) : [])
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.blade.php'))
            ->map(function ($file) {
                $filename = $file->getFilename();
                $name = str_replace('.blade.php', '', $filename);
                $content = File::get($file->getPathname());
                $lineCount = substr_count($content, "\n") + 1;

                return [
                    'key' => $name,
                    'view' => 'mail.'.$name,
                    'filename' => $filename,
                    'updated_at' => date('M d, Y h:i A', $file->getMTime()),
                    'line_count' => $lineCount,
                    'size_kb' => number_format($file->getSize() / 1024, 2),
                ];
            })
            ->sortBy('view')
            ->values();

        return view('admin.email-templates.index', [
            'templates' => $templates,
        ]);
    }

    public function edit(string $template): View
    {
        $path = $this->resolveTemplatePath($template);
        abort_unless($path !== null, 404);

        $content = File::get($path);
        $filename = basename($path);

        return view('admin.email-templates.edit', [
            'templateKey' => $template,
            'viewName' => 'mail.'.$template,
            'filename' => $filename,
            'content' => $content,
        ]);
    }

    public function update(Request $request, string $template): RedirectResponse
    {
        $path = $this->resolveTemplatePath($template);
        abort_unless($path !== null, 404);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        File::put($path, $this->sanitizeTemplateContent($validated['content']));

        return redirect()
            ->route('admin.email-templates.edit', $template)
            ->with('success', 'Email template updated successfully.');
    }

    private function mailViewDirectory(): string
    {
        return resource_path('views/mail');
    }

    private function resolveTemplatePath(string $template): ?string
    {
        $normalized = trim($template);
        if ($normalized === '' || ! preg_match('/^[a-zA-Z0-9_-]+$/', $normalized)) {
            return null;
        }

        $path = $this->mailViewDirectory().DIRECTORY_SEPARATOR.$normalized.'.blade.php';
        if (! File::exists($path)) {
            return null;
        }

        return $path;
    }

    private function sanitizeTemplateContent(string $content): string
    {
        $clean = $content;

        // Convert TinyMCE protected placeholders back to original Blade/text.
        $clean = preg_replace_callback('/&lt;!--mce:protected\s+(.+?)--&gt;/i', function ($matches) {
            return rawurldecode(trim($matches[1]));
        }, $clean) ?? $clean;
        $clean = preg_replace_callback('/<!--mce:protected\s+(.+?)-->/i', function ($matches) {
            return rawurldecode(trim($matches[1]));
        }, $clean) ?? $clean;

        // Remove browser/editor-injected attributes that pollute saved templates.
        $clean = preg_replace('/\s+bis_[a-zA-Z0-9_-]+="[^"]*"/', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+bis_size="[^"]*"/', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+contenteditable="[^"]*"/i', '', $clean) ?? $clean;
        $clean = preg_replace('/\s+data-mce-[a-zA-Z0-9_-]+="[^"]*"/', '', $clean) ?? $clean;

        // Remove executable content from user-edited HTML templates.
        $clean = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $clean) ?? $clean;

        // Decode entities inside Blade expressions only, so Blade syntax remains valid.
        $clean = preg_replace_callback('/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/', function ($matches) {
            return html_entity_decode($matches[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $clean) ?? $clean;

        return rtrim($clean).PHP_EOL;
    }
}

