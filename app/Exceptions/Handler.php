<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'ok' => false,
            'message' => 'Unauthenticated.'
        ], 401);
    }

    public function render($request, Throwable $e)
    {
        // JWT token yo'q yoki noto'g'ri bo'lsa UnauthorizedHttpException tushadi
        if ($e instanceof UnauthorizedHttpException) {
            return response()->json([
                'ok' => false,
                'message' => 'Token is missing or invalid.'
            ], 401);
        }

        // Boshqa xatolarni odatdagidek Laravel hal qiladi
        return parent::render($request, $e);
    }

}
