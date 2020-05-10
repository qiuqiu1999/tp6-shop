<?php
declare (strict_types = 1);
namespace app\admin\middleware;

class  Auth
{
    public function handle($request, \Closure $next)
    {
//        dump(session(config("admin.session_admin")));
//        dump(empty(session(config("admin.session_admin"))));
//         判断是否登录 请求控制器不是Login
        if(empty(session(config("admin.session_admin"))) && !preg_match("/login/",$request->pathinfo())) {
           return redirect((string)url("login/index"));
        }
        if(!empty(session(config("admin.session_admin"))) && preg_match("/login/",$request->pathinfo())) {
            return redirect((string)url("index/index"));
        }

        return $next($request);
    }
}
