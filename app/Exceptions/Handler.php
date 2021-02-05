<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Exception $exception)
    // {
    //     if ($exception instanceof CustomException) {
    //         switch (intval($e->getStatusCode())) {
    //             case 404:
    //                 return redirect()->route('404');
    //                 break;
    //             case 500:
    //                 // return redirect()->route('500');
    //                 return response()->view('500');
    //                 break;
    //             default:
    //                 return $this->renderHttpException($e);
    //                 break;
    //         }
    //     }else{
    //         return parent::render($request, $exception);
    //     }

    // }
}
