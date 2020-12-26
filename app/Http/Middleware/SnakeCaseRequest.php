<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SnakeCaseRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $new =[];
        $input= $request->all();
        foreach ($input as $key => $value) {
            $key = Str::snake($key);
            $new += [$key=>$value];
        }
        // dd("snakecase",$new);
        $request->replace($new);
        return $next($request);
    }
}
