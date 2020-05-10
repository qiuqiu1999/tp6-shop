<?php

use think\facade\Route;


Route::rule('detail', 'Login/index2','GET')->middleware(app\admin\middleware\Param::class);
