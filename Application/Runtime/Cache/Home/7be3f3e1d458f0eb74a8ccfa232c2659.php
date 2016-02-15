<?php if (!defined('THINK_PATH')) exit();?>﻿﻿<!doctype>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登陆</title>

    <script type="text/javascript" src="/newsc/web/Public/js/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/login.css" />

</head>

<body>
<div class="logindiv">

    <div class="pr" style="top:-100px"><img class="loginlogo" src="/newsc/web/Public/image/tako.png"></div>
    <div class="pr"><div class="logintitle ">STOCK INFO <br>MINNING SYSTEM</div></div>
    <div class="pr"><div class="usericon"></div><input class="username logininput" type="text" placeholder="请输入用户名"  /></div>
    <div class="pr"><div class="passwordicon"></div><input class="password logininput" placeholder="请输入密码" type="password"  /></div>
    <div class="pr"><input class="loginButton" type="button"  value="登 陆"/></div>
</div>
</body>
</html>
<script language="JavaScript">
    !
            function(){
                $(".loginButton").click(function(e){
                    $.post("/newsc/web/index.php/Home/Index/login",{"name":$(".username").val(),"pwd":$(".password").val()},function(data){
                           data=$.parseJSON(data);
                           if(data.code==1){
                            location.href="/newsc/web/index.php/Home/index/index";
                           }else{
                               alert(data.result);
                           }
                    });
                });
                $(document).keydown(function(e){
                    if(e.keyCode==13){
                        $(".loginButton").click();
                    }
                });
            }()
</script>