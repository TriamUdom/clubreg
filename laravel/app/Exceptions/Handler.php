<?php

namespace App\Exceptions;

use Config;
use Session;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //HttpException::class,
        //ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
      if(Config::get('applicationConfig.release') == 'release'){
        \Log::warning($e, array(
          'session' => Session::all(),
          'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ));
      }else{
        \Log::info($e, array(
          'session' => Session::all(),
          'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ));
      }
      return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($e instanceof ModelNotFoundException) {
          abort(404);
        }

        if($e instanceof \Illuminate\Session\TokenMismatchException){
          return redirect()->back();
        }

        if($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
          abort(405);
        }

        return parent::render($request, $e);
    }
}
