<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response
    {
        if ($request->expectsJson()) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return parent::render($request, $e);
        }

        if (config('app.debug') || ! $e instanceof HttpExceptionInterface) {
            return parent::render($request, $e);
        }

        $status = $this->resolveStatusCode($e);
        $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.general';

        return response()->view($view, [
            'status' => $status,
            'title' => $this->resolveTitle($status),
            'message' => $this->resolveMessage($status),
        ], $status);
    }

    private function resolveStatusCode(Throwable $e): int
    {
        if ($e instanceof HttpExceptionInterface) {
            return $e->getStatusCode();
        }

        return 500;
    }

    private function resolveTitle(int $status): string
    {
        return match ($status) {
            403 => 'Access Denied',
            404 => 'Page Not Found',
            419 => 'Session Expired',
            429 => 'Too Many Requests',
            500 => 'Something Went Wrong',
            503 => 'Service Unavailable',
            default => 'Unexpected Error',
        };
    }

    private function resolveMessage(int $status): string
    {
        return match ($status) {
            403 => 'You do not have permission to open this page.',
            404 => 'The page you requested could not be found.',
            419 => 'Your session has expired. Please go back and try again.',
            429 => 'Too many requests were made in a short time. Please wait and try again.',
            500 => 'An unexpected error occurred while loading this page. Please try again shortly.',
            503 => 'The service is temporarily unavailable. Please try again in a few moments.',
            default => 'An unexpected error occurred. Please try again shortly.',
        };
    }
}
