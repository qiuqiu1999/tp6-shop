document.writeln('<div class="site-nav-bg">');
document.writeln('<div class="site-nav w1200">');
document.writeln('<p class="sn-back-home">');
document.writeln('<i class="layui-icon layui-icon-home"></i>');
document.writeln('<a href="#">首页</a>');
document.writeln('</p>');
document.writeln('<div class="sn-quick-menu">');
console.log(localStorage.getItem('access_token'));
if (localStorage.getItem('access_token')) {
    document.writeln('<div class="login"><a href="center.html">个人中心，</a><button class="button" onclick="logout()">退出</button></div>');
} else {
    document.writeln('<div class="login"><a href="login.html">登录</a></div>');
}
document.writeln('<div class="sp-cart"><a href="shopcart.html">购物车</a><span>2</span></div>');
document.writeln('</div>');
document.writeln('</div>');
document.writeln('</div>');


function logout() {
    localStorage.removeItem('access_token');
    window.location.href = 'http://layui.qiujincheng.top/html/login.html';
}









