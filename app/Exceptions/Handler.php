<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Stripe\Exception\InvalidRequestException;


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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
      $this->registerErrorViewPaths();

      if ($exception instanceof MethodNotAllowedHttpException) {
          return response()->view('errors.custom', [
            'message' => ($exception->getMessage())? $exception->getMessage():'Request Method Not Allowed',
            'code' => ($exception->getStatusCode())? $exception->getStatusCode(): '405',
          ]);
      }
      if ($exception instanceof InvalidRequestException) {
          return response()->view('errors.custom', [
            'message' => ($exception->getError()->message)? $exception->getError()->param." : ".$exception->getError()->message:'Invalid input check entries',
            'code' => ($exception->getHttpStatus())? $exception->getHttpStatus(): '405',
          ]);
      }
        return parent::render($request, $exception);
    }
}
