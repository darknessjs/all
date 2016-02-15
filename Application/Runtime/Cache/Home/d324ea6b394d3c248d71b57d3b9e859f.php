<?php if (!defined('THINK_PATH')) exit();?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo ($title); ?></title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/bootstrap.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/sb-admin.css" />

    <!-- Morris Charts CSS -->
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/plugins/morris.css" />

    <!-- Custom Fonts -->
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/font-awesome/css/font-awesome.css" />




    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/Rswitch.css" />
    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/Rpage.css" />


    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/newsc.css" />


    <script type="text/javascript" src="/newsc/web/Public/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="/newsc/web/Public/js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->



    <link rel="stylesheet" type="text/css" href="/newsc/web/Public/css/Rwindow.css" />
    <script type="text/javascript" src="/newsc/web/Public/js/Rwindow.js"></script>

    <script type="text/javascript" src="/newsc/web/Public/js/Rswitch.js"></script>
    <script type="text/javascript" src="/newsc/web/Public/js/Rpage.js"></script>
    <script type="text/javascript" src="/newsc/web/Public/js/Rcheck.js"></script>
    <script type="text/javascript" src="/newsc/web/Public/js/Rfall.js"></script>





    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



    <script language="JavaScript">
        var TASK_LIST="/newsc/web/index.php/Home/Task/taskList";//得到任务list

        var ALARM_LIST="/newsc/web/index.php/Home/Alarm/alarmList";//查询预警提醒

        var ALARM_CONTENT_LIST="/newsc/web/index.php/Home/Alarm/contentList";//查询最新采集

        var NEWS_LIST="/newsc/web/index.php/Home/collect/collectList";//新闻list和查找

        var ANSWER_LIST="/newsc/web/index.php/Home/collect/collectList";//问答list和查找

        var UPDATE_ANSWER="/newsc/web/index.php/Home/collect/updateRead";//修改是否阅读

        var TASK_DATA_COUNT="/newsc/web/index.php/Home/count/taskDataCount";//得到任务数据

        var NOW_ACSWER_COUNT="/newsc/web/index.php/Home/count/nowAnswerCount";//当天采集问答数

        var ALL_ANSWER_COUNT="/newsc/web/index.php/Home/count/allAnswerCount";//采集总数

        var TASK_VALUE_DATA_COUNT="/newsc/web/index.php/Home/count/taskValueDataCount";//有效数

        var TASK_COUNT="/newsc/web/index.php/Home/count/taskCount";//任务数

        var WRITE_DB="/newsc/web/index.php/Home/system/writeDB";//备份数据

        var READ_DB="/newsc/web/index.php/Home/system/readDB";//恢复数据

        var SELECT_DB="/newsc/web/index.php/Home/system/selectDB";//查询备份

        var DELETE_DB="/newsc/web/index.php/Home/system/deleteDB";//删除备份

        var UPDATE_RULE="/newsc/web/index.php/Home/system/updateRule";//修改数据库删除规则

        var USER_LIST="/newsc/web/index.php/Home/user/userList";//用户列表

        var DELETE_USER="/newsc/web/index.php/Home/user/deleteUser";//删除用户

        var UPDATE_USER="/newsc/web/index.php/Home/user/UpdateUser";//修改用户

        var ADD_USER="/newsc/web/index.php/Home/user/addUser";//添加用户

        var ALARM_SETTING_LIST="/newsc/web/index.php/Home/alarmSetting/alarmSettingList";//报警list

        var ADD_ALARM_SETTING="/newsc/web/index.php/Home/alarmSetting/addAlarmSetting";//添加报警

        var UPDATE_ALARM_SETTING="/newsc/web/index.php/Home/alarmSetting/updateAlarmSetting";//修改报警设置

        var DELETE_ALARM_SETTING="/newsc/web/index.php/Home/alarmSetting/deleteAlarmSetting";//删除报警设置

        var UPDATE_TASK="/newsc/web/index.php/Home/Task/updateTask";//修改任务

        var DELETE_TASK="/newsc/web/index.php/Home/Task/deleteTask";//删除任务

        var ADD_TASK="/newsc/web/index.php/Home/Task/addTask";//添加任务

        var SE_LIST="/newsc/web/index.php/Home/SE/seList";//搜索引擎list

        var SE_ADD_SE="/newsc/web/index.php/Home/SE/addSE";//添加搜索引擎

        var SE_UPDATE_SE="/newsc/web/index.php/Home/SE/updateSE";//添加搜索引擎

        var SE_DELETE_SE="/newsc/web/index.php/Home/SE/deleteSE";//删除搜索引擎

        var LOGIN_OPERATION="/newsc/web/index.php/Home/index/loginOperation";// 登出和查看状态

        var GET_TASK_BY_ID="/newsc/web/index.php/Home/task/getTaskById";//根据id得到任务信息

        var GET_DELETE_DAY="/newsc/web/index.php/Home/count/getDeleteDay";//得到默认删除天数

       var __APP="/newsc/web/index.php";
        var __SELF="/newsc/web/index.php/Home/Index/answer";
        var __ROOT="/newsc/web";
        function cleantable(jqtable){
            jqtable.children("tbody").html("");
        }


        function getArgs(){
            var args = {};
            var match = null;
            var search = decodeURIComponent(location.search.substring(1));
            var reg = /(?:([^&]+)=([^&]+))/g;
            while((match = reg.exec(search))!==null){
                args[match[1]] = match[2];
            }
            return args;
        }
        var args=getArgs();


        Rswitch.setallfunc(function(jqobj){
            //alert(jqobj.attr("data-read-id"));
            if(jqobj.hasClass("R_switch_a"))
            $.post(UPDATE_ANSWER,{"ccid":jqobj.attr("data-read-id"),"isRead":(jqobj.attr("data-ison")=="true"?1:0)});
        });
    </script>


</head>

<body>
<audio id="warningaudio" src="/newsc/web/Public/ding.wav"></audio>
<div id="wrapper">

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->

        <div class="navbar-header">
            <a class="navbar-brand">信息采集系统</a>
        </div>

        <ul class="nav navbar-right top-nav">
            <li class="warninginfo" id="testdiv"><div class="warningredcircle"></div><a><i class="fa fa-comment"></i><div></div></a></li>
            <li class="warninginfo"><div class="warningredcircle"></div><a><i class="fa fa-bell"></i><div></div></a></li>
            <li class="warninginfo"><div class="warningredcircle"></div><a><i class="fa fa-info-circle"></i><div></div></a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="u_name"></span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="useredit openRwindow_l"><i class="fa fa-fw fa-gear"></i>信息设置</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" class="layout_logout"><i class="fa fa-fw fa-power-off"></i>退出</a>
                    </li>
                </ul>
            </li>
        </ul>



        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="navlogo" style="text-align: center; height: 200px;">
                        <img class="loginlogo" src="/newsc/web/Public/image/tako.png">
                </li>
                <!--class="active"-->
                <li>
                    <a href="/newsc/web/index.php/Home/index/index"><i class="fa fa-fw fa-2x fa-dashboard l-i maincolor"></i> 系统概况</a>
                </li>
                <li>
                    <a href="/newsc/web/index.php/Home/index/answer"><i class="fa fa-fw fa-2x fa-comment l-i maincolor"></i> 问答采集</a>
                    <ul class="collapse left_answer_list ulpointer" >
                    </ul>
                </li>
                <li>
                    <a href="/newsc/web/index.php/Home/index/search"><i class="fa fa-fw fa-2x fa-search l-i maincolor"></i> 搜索采集</a>
                    <ul class="collapse left_search_list ulpointer" >
                    </ul>
                </li>
                <li>
                    <a href="/newsc/web/index.php/Home/index/warning"><i class="fa fa-fw fa-2x fa-bell l-i maincolor"></i> 预警提醒</a>
                </li>
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#sys_setting"><i class="fa fa-fw fa-2x fa-cog l-i maincolor"></i> 系统设置 <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="sys_setting" class="collapse">
                        <li>
                            <a href="/newsc/web/index.php/Home/setting/usersetting">用户管理</a>
                        </li>
                        <li>
                            <a href="/newsc/web/index.php/Home/setting/answersetting">问答采集设置</a>
                        </li>
                        <li>
                            <a href="/newsc/web/index.php/Home/setting/searchsetting">搜索采集设置</a>
                        </li>
                        <li>
                            <a href="/newsc/web/index.php/Home/setting/warningsetting">报警设置</a>
                        </li>
                        <li>
                            <a href="/newsc/web/index.php/Home/setting/systemsetting">系统配置维护</a>
                        </li>
                    </ul>

                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>






<div id="page-wrapper">

    <div class="container-fluid">


        <div class="row">
            <form role="form">
                <div class="col-lg-10 col-md-10">
                    <div class="form-group searchbox">
                        <input class="form-control" placeholder="输入搜索关键词">
                        <button class="searchbotton"> <i class="fa fa-search"></i> 搜索</button>
                    </div>
                </div>

            </form>

            <div class="col-lg-2 col-md-2 ">
                <div class="form-group">
                    <button class="form-control allread"><i class="fa fa-check-circle" style="margin-right: 5px"></i>全部已读</button>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div class="table_page pagestatus"></div>
                    <table class="answertable table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th class="table_status">状态</th>
                            <th class="table_time">发布时间</th>
                            <th>采集来源</th>
                            <th>内容摘要</th>
                            <th>查看详情</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

</div>

<script type="text/javascript" src="/newsc/web/Public/js/answer-js.js"></script>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">警告</h4>
            </div>
            <div class="modal-body">
                确定是否删除
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">否</button>
                <button type="button" class="btn btn-primary delete_yes" onclick="delete_ok(this)" data-dismiss="modal">是</button>
            </div>
        </div>
    </div>
</div>




<div id="userset">
    <div class="R_windows_head">
        用户设置
        <div class="R_window_close_button"><i class="fa fa-close"></i></div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group l_password">
                    <label>密码</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group l_rppassword">
                    <label>密码确认</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group l_email">
                    <label>邮箱</label>
                    <input class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <button class="btn btn-primary l_useredit">确定</button>
                <button class="ml10 btn R_window_cancel_button">取消</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<script type="text/javascript" src="/newsc/web/Public/js/plugins/morris/raphael.min.js"></script>
<script type="text/javascript" src="/newsc/web/Public/js/plugins/morris/morris.min.js"></script>
<script type="text/javascript" src="/newsc/web/Public/js/plugins/morris/morris-data.js"></script>

<script language="JavaScript">
    var thispageindex_temp=<?php echo ($index); ?>;
    $(".navbar-nav").children("li:eq(<?php echo ($index); ?>)").addClass("active");
    $(".navbar-nav").children("li:eq(<?php echo ($index); ?>)").children("ul").addClass("in");
</script>
<script type="text/javascript" src="/newsc/web/Public/js/layout-js.js"></script>