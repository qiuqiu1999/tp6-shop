document.writeln('<div class="site-nav-bg">');
document.writeln('<div class="site-nav w1200">');
document.writeln('<p class="sn-back-home">');
document.writeln('<i class="layui-icon layui-icon-home"></i>');
document.writeln('<a href="#">首页</a>');
document.writeln('</p>');
document.writeln('<div class="sn-quick-menu">');
getCartCount();
// console.log(localStorage.getItem('access_token'));
if (sessionStorage.getItem('access_token')) {
    document.writeln('<div class="login"><a href="center.html">个人中心，</a><button class="button" onclick="logout()">退出</button></div>');
    document.writeln('<div class="sp-cart"><a href="shopcart.html">购物车</a><span id="my_cart_num">0</span></div>');
} else {
    document.writeln('<div class="login"><a href="login.html">登录</a></div>');
}

document.writeln('</div>');
document.writeln('</div>');
document.writeln('</div>');


function logout() {
    sessionStorage.removeItem('access_token');
    window.location.href = 'http://layui.qiujincheng.top/html/login.html';
}

function getCartCount()
{
    if (sessionStorage.getItem('access_token')) {
        layui.config({
            base: '../res/static/js/util/' //你存放新模块的目录，注意，不是layui的模块目录
        }).use(['mm', 'jquery'], function () {
            $ = layui.$;
            $.ajax({
                type: "post",
                dataType: "json",
                url: 'http://shop.qiujincheng.top/api/init',
                data: '',
                headers: {
                    "Authorization": sessionStorage.getItem('access_token')//此处放置请求到的用户token
                },
                success: function (data) {
                    $('#my_cart_num').html(data.result.cart_num ? data.result.cart_num : 0);
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        });
    }
}









