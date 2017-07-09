<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="/Public/Wechat/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Wechat/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main{margin-bottom: 60px;}
        .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
    </style>
</head>
<body>
<div class="main">
    <!--导航部分-->
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container-fluid text-center">
            <div class="col-xs-3">
                <p class="navbar-text"><a href="index.html" class="navbar-link">首页</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">服务</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">发现</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">我的</a></p>
            </div>
        </div>
    </nav>
    <!--导航结束-->

    <div class="container-fluid" data-act-id="<?php echo ($info["id"]); ?>">
        <div class="blank"></div>
        <h3 class="noticeDetailTitle"><strong><?php echo ($info["title"]); ?></strong></h3>
        <div class="noticeDetailInfo">发布者:<?php echo ($name_info); ?></div>
        <div class="noticeDetailInfo">发布时间：<?php echo (time_format($info["create_time"])); ?></div>
        <button class="btn btn-xs btn-warning" id="join_in_button">我要参加该活动</button>
        <div class="noticeDetailContent">
            <?php echo ($content["content"]); ?>
        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/Public/Wechat/js/jquery-1.11.2.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/Public/Wechat/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $('#join_in_button').on('click',function () {
        if(confirm('您确认报名参加该活动吗？')){
            var act_id = $(this).closest('.container-fluid').attr('data-act-id');
            var url = '/wechat.php/CommunityActivity/audit/act_id/'+ act_id +'.html';
            $.getJSON(url,{'act_id':act_id},function (response) {
                alert(response.msg);
                if(response.sta == -1){
                    window.location.href = '/wechat.php/Wechat/login.html';
                }
            });
        }
    })
</script>
</body>
</html>