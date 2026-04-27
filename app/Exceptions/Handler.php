<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */

    public function render($request, Throwable $e)
    {
        Log::debug($e);

        $message = null;
        $code = null;

        switch ($e) {
            case ($e instanceof ValidationException): {
                    $message = $e->errors();
                    $code = Response::HTTP_UNPROCESSABLE_ENTITY;
                    break;
                }
            case ($e instanceof AuthorizationException): {
                    $message = $e->getMessage();
                    $code = Response::HTTP_FORBIDDEN;
                    break;
                }
            case ($e instanceof UnauthorizedException): {
                    $message = $e->getMessage();
                    $code = Response::HTTP_UNAUTHORIZED;
                    break;
                }
            case ($e instanceof QueryException): {
                    if (env('APP_DEBUG') == true)
                        $message = $e->getMessage();
                    else
                        // $message = __('common.query-error');
                        $message = $e->getMessage();

                    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                    break;
                }
            default:
                $message = $e->getMessage();
                $code = Response::HTTP_BAD_REQUEST;
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->errorResponse($message, $code);
        } else {
            if (env('APP_ENV') != 'Production') {
                return parent::render($request, $e);
            }

            return response()->view('errors.exception');
        }
    }
}
