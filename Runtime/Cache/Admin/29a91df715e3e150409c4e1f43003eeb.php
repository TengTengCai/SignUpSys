<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>选课系统 - 课程信息</title>

        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>datepicker3.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>zTreeStyle.css" rel="stylesheet"/>

        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span>Class</span>Admin</a>
                    <ul class="user-menu">
                        <li class="dropdown pull-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $manager; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li id="logout"><a href="/SignUpSys/index.php/Admin/TTree/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.container-fluid -->
        </nav>

        <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
            <ul class="nav menu">
                <li><a href="/SignUpSys/index.php/Admin/Student/"><span class="glyphicon glyphicon-dashboard"></span>学生信息</a></li>
                <li class="parent active">
                    <a href="#">
                        <span class="glyphicon glyphicon-list"></span> 信息 <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right" aria-expanded="true"><em class="glyphicon glyphicon-s glyphicon-plus glyphicon-minus"></em></span>
                    </a>
                    <ul class="children collapse in" id="sub-item-1" aria-expanded="true">
                        <li>
                            <a class="" href="/SignUpSys/index.php/Admin/CTree/">
                                <span class="glyphicon glyphicon-share-alt"></span> 专业信息
                            </a>
                        </li>
                        <li>
                            <a class="" href="/SignUpSys/index.php/Admin/TTree/">
                                    <span class="glyphicon glyphicon-share-alt"></span> 实训信息
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="parent">
                    <a href="#">
                        <span class="glyphicon glyphicon-th"></span> 选择情况 <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right" aria-expanded="true"><em class="glyphicon glyphicon-s glyphicon-plus glyphicon-minus"></em></span>
                    </a>
                    <ul class="children collapse in" id="sub-item-2" aria-expanded="true">
                        <li>
                            <a class="" href="/SignUpSys/index.php/Admin/CSelection/">
                                    <span class="glyphicon glyphicon-share-alt"></span> 专业选择情况
                            </a>
                        </li>
                        <li>
                            <a class="" href="/SignUpSys/index.php/Admin/TSelection/">
                                    <span class="glyphicon glyphicon-share-alt"></span> 实训选择情况
                            </a>
                        </li>
                    </ul>
                </li>
                <li role="presentation" class="divider"></li>
            </ul>
            <div class="attribution"><p >Copyright&copy;<a href="http://www.tjclouds.cc/" target="_blank" title="tjclouds">tjclouds</a> <br/> Powered By SignUpSystem <br/>Version 1.0.1 </p></div>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">		
            <div class="row">
                    <ol class="breadcrumb">
                            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                            <li>信息</li>
                            <li class="active">实训信息</li>
                    </ol>
            </div><!--/.row-->
            <div class="ztree_wrap">
                <ul id="tree" class="ztree"></ul>
            </div>
            <div class="ztree_right">
                <button id="bt_add" class="btn btn-info" >添加节点</button>
                <button id="bt_remove" class="btn btn-info">删除节点</button>
                <div>
                    <label>名称:</label>
                    <input type="text" class="form-control" id="input_course_name" placeholder="名称"/>
                    <label>限制:</label>
                    <input type="number" class="form-control" id="input_course_limit" placeholder="限制"/>
                    <label>简介:</label>
                    <textarea class="form-control" id="input_course_synopsis" placeholder="简介"></textarea>
                    <button id="bt_addCourse" class="btn btn-info">添加/修改</button>
                </div>
            </div>
        </div>	<!--/.main-->
        <script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo APP_JS_URL;?>jquery.ztree.all.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap-table.js"></script>
        <script>
            !function ($) {
                $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
                    $(this).find('em:first').toggleClass("glyphicon-minus");      
                }); 
                $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
            }(window.jQuery);

            $(window).on('resize', function () {
              if ($(window).width() > 768) $('#sidebar-collapse').collapse('show');
            });
            $(window).on('resize', function () {
              if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide');
            });
        </script>
        <script>
            var setting = {
                treeId:"tree",
                async:{
                    enable:true,
                    dataType:"json",
                    url:"/SignUpSys/index.php/Admin/TTree/getTree"
                },
                view: {
                    selectedMulti: false,
                    nameIsHTML: true
                },
                edit: {
                    enable: true,
                    editNameSelectAll: true,
                    showRemoveBtn: false,
                    showRenameBtn: false
                },
                data:{
                    simpleData:{
                        enable:true,
                        idKey:"ttr_NodeId",
                        pIdKey:"ttr_ParentId",
                        rootPid:0
                    }
                },
                //回调函数
                callback: {
                    onAsyncSuccess: zTreeOnAsyncSuccess,
                    onClick: zTreeOnClick
                }
            };
            function zTreeOnAsyncSuccess(event, treeId, treeNode, msg) {
                var zTree = $.fn.zTree.getZTreeObj("tree");
                zTree.expandAll(true);
            }
            function zTreeOnClick(event, treeId, treeNode) {
                var limit;
                if(treeNode.level<4){
                    $('#input_course_limit').hide();
                    $('#input_course_synopsis').hide();
                }else{
                    limit = treeNode.ctr_Limit;
                    $('#input_course_limit').show();
                    $('#input_course_synopsis').show();
                }
                var arr = treeNode.name.split("C:");
                $('#input_course_name').val(arr[0]);
                $('#input_course_limit').val(treeNode.ttr_Limit);
                $('#input_course_synopsis').val(treeNode.ttr_Synopsis);
            }
            function add() {
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
                if (nodes.length === 0) {
                    alert("请先选择一个节点");
                    return;
                }
                if(treeNode.level >= 4){
                    alert("该节点无法添加");
                    return;
                }
                $.get("/SignUpSys/index.php/Admin/TTree/addChild",{pId:treeNode.ttr_NodeId,level:treeNode.level},function(data){
                    if(data === '0'){
                        alert("数据错误");
                    }
                    refresh();
                });
            }
            function remove(){
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode = nodes[0];
                if (nodes.length === 0) {
                    alert("请先选择一个节点");
                    return;
                }
                if(treeNode.isParent){
                    alert("该父节点无法删除");
                    return;
                }
                $.get("/SignUpSys/index.php/Admin/TTree/removeChild",{ttr_NodeId:treeNode.ttr_NodeId},function(data){
                    if(data === 0){
                        alert("数据错误");
                    }
                    refresh();
                });
            }
            function refresh(){
                setTimeout(function () { 
                    var zTree = $.fn.zTree.getZTreeObj("tree");
                    zTree.reAsyncChildNodes(null, "refresh");
                },500);
            }
            
            function saveInfo(){
                var zTree = $.fn.zTree.getZTreeObj("tree"),
                nodes = zTree.getSelectedNodes(),
                treeNode =  nodes[0],
                nodeName = $('#input_course_name').val(),
                nodeSynopsis = $('#input_course_synopsis').val(),
                limit = $('#input_course_limit').val();
                if(nodes.length === 0){
                    alert("请先选择一个子节点");
                    return;
                }else {
                    if(treeNode.level <= 3){
                        if(nodeName.length === 0 || nodeSynopsis.length > 0){
                            alert("修改当前结点名字不能为空");
                            return;
                        }else{
                            $.get("/SignUpSys/index.php/Admin/TTree/saveInfo",
                            {ttr_NodeId:treeNode.ttr_NodeId,
                                ttr_Name:nodeName},
                            function(data){
                                if(data === '0'){
                                    alert("数据错误");
                                }
                            });
                        }
                    }else {
                        if(nodeName.length === 0 ||  nodeSynopsis.length === 0 ){
                            alert("修改当前结点名字和简介不能为空");
                            return;
                        }else {
                            $.get("/SignUpSys/index.php/Admin/TTree/saveInfo",
                            {ttr_NodeId:treeNode.ttr_NodeId,
                                ttr_Name:nodeName,
                                ttr_Limit:limit,
                                ttr_Synopsis:nodeSynopsis},
                            function(data){
                                if(data === '0'){
                                    alert("数据错误");
                                }
                            });
                        }
                    }
                }
                $('#input_course_name').val("");
                $('#input_course_limit').val("");
                $('#input_course_synopsis').val("");
                refresh();
            }
            $(document).ready(function(){
                zTreeObj = $.fn.zTree.init($("#tree"), setting);
                zTreeObj.expandAll(true);
                $("#bt_add").bind("click", add);
                $("#bt_remove").bind("click", remove);
                $('#bt_addCourse').bind("click",saveInfo);
            });
        </script>
    </body>

</html>