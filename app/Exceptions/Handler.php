<?php

namespace App\Exceptions;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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

        });
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ValidationException && $request->has(['username','password'])){
                return redirect()->route('auth')->with('loginToast', ['loginSuccess' => false, 'error' => 'credential failed!']);
            }
            // if ($e instanceof QueryException){
            //     return redirect()->route('error',['error'=>'query']);
            // }
        });
    }

}
