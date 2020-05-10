<?php
return [
//    //直播
//    "sms_pre" => "sms_" ,
//    "user_pre" => "user_",

    // 商城
    "mall_cart_pre"             =>      "mall_cart_",               // 购物车
    "mall_cart_expire"          =>      3600*24*90,                 // 购物车有效时间
    "mall_token_pre"            =>      "mall_token_",              // Token
    "mall_token_expire"         =>      1800,                       // Token有效时间
    "mall_phone_code_pre"       =>      "mall_phone_code_",         // 手机短信
    "mall_phone_code_expire"    =>      300,                        // 手机短信有效时间
    "mall_goods_pv_pre"         =>      "mall_goods_pv_",           // 商品PV

    //延迟队列
    "mall_order_status_key"     =>      "mall_order_status",        //
    "mall_order_expire"         =>      60*20,                      // 订单有效时间



];