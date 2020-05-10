<?php

namespace app\admin\middleware;

class Param
{
    public function handle($request, \Closure $next)
    {
//        if ($request->param('name') == 'think') {
//            return redirect('index/think');
//        }
//        dump(3);
        return $next($request);
    }
}
