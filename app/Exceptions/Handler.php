<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $e = $this->prepareException($exception);

        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->newUnauthenticated($request, $exception);
        } elseif ($e instanceof ValidationException) {

            parent::render($request, $exception);
        }

        return parent::render($request, $exception);
    }

    public function newUnauthenticated($request, Exception $exception)
    {
        $output['data'] = [];
        $output['message'] = $exception->getMessage();
        $output['code'] = 401;
        return $request->expectsJson()
            ? response()->json($output, $output['code'])
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
