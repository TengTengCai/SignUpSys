<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Expires" CONTENT="0"> 
        <meta http-equiv="Cache-Control" CONTENT="no-cache"> 
        <meta http-equiv="Pragma" CONTENT="no-cache"> 
        <title>选课系统</title>

        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>datepicker3.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>zTreeStyle.css" rel="stylesheet" type="text/css"/>

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
                    <a class="navbar-brand" href="#">选课系统</a>
                    <ul class="user-menu">
                        <li class="dropdown pull-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $name;?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/SignUpSys/index.php/Home/Practice/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
        <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
            <ul class="nav menu">
                    <li id="pshunt"><a href="/SignUpSys/index.php/Home/Student/choice"><span class="glyphicon glyphicon-dashboard"></span>专业分流选择</a></li>
                    <li class="active"><a href="#"><span class="glyphicon glyphicon-dashboard"></span>实训课程选择</a></li>
            </ul>
            <div class="attribution"><p >Copyright&copy;<a href="http://www.tjclouds.cc/" target="_blank" title="tjclouds">tjclouds</a> <br/> Powered By SignUpSystem <br/>Version 1.0.1 </p></div>
        </div><!--/.sidebar-->

        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">		
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li class="active">专业方向模块选择</li>
                    <li><?php echo $profession; ?></li>
                </ol>
            </div><!--/.row-->
            <div class="row">
                <div class="ztree_wrap">
                    <ul id="tree" class="ztree"></ul>
                    <button id="bt_sure" class="btn btn-info">确定选择</button>
                </div>
            </div><!--/.row-->
            <div class="property">
                <table data-toggle="table">
                    <thead>
                        <tr>
                            <th data-width="15%">名称</th>
                            <th data-width="15%">人数</th>
                            <th data-width="15%">上限</th>
                            <th data-width="55%">实训简介</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="ttr_Name"></td>
                            <td id="count"></td>
                            <td id="ttr_tLimit"></td>
                            <td id="ttr_Synopsis"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="property">
                <div><p>你当前已选择的实训课程</p></div>
                <table data-toggle="table">
                    <thead>
                        <tr>
                            <th data-width="15%">名称</th>
                            <th data-width="15%">人数</th>
                            <th data-width="15%">上限</th>
                            <th data-width="55%">课程简介</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $table;?>
                    </tbody>
                </table>

            </div><!--/.row-->
        </div>	<!--/.main-->
        <script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap-table.js"></script>
        <script src="<?php echo APP_JS_URL;?>jquery.ztree.all.min.js"></script>
        <script>
            var setting = {
                treeId:"tree",
                async:{
                    enable: true,
                    dataType:"text",
                    url: "/SignUpSys/index.php/Home/Practice/getTTree",
                },
                view: {
                    selectedMulti: false,
                    nameIsHTML: true
                },
                edit: {
                    enable: true,
                    drag: {
			isCopy: false,
			isMove: false
                    },
                    editNameSelectAll: true,
                    showRemoveBtn: false,
                    showRenameBtn: false
                },
                check: {
                enable: true,
                chkStyle: "radio",
                chkboxType: { "Y": "s", "N": "s" },
                radioType: "level"
                },
                data:{
                    simpleData:{
                        enable:true,
                        idKey:"ttr_NodeId",
                        pIdKey:"ttr_ParentId",
                        rootPid:0
                    }
                },
                callback: {
                    onAsyncSuccess: zTreeOnAsyncSuccess,
                    onClick: zTreeOnClick
                }
            }
            function zTreeOnAsyncSuccess(event, treeId, treeNode, msg) {
                var zTreeObj = $.fn.zTree.getZTreeObj(treeId);
                var nodes = zTreeObj.getNodes();
                var nodes_array = zTreeObj.transformToArray(nodes);
//                alert(nodes_array.length);
                for(var i = 0;i<nodes_array.length;i++){
                    if(nodes_array[i].level<3){
                        nodes_array[i].nocheck = true;
                    }
                }
//                alert(nodes.nocheck);
                zTreeObj.expandAll(true);
            };
            function zTreeOnClick(event, treeId, treeNode) {
                if(treeNode.level > 1){
                    $('#ttr_Name').html(treeNode.name);
                    $('#ttr_tLimit').html(treeNode.ttr_Limit);
                    $('#ttr_Synopsis').html(treeNode.ttr_Synopsis);
                    $.get("/SignUpSys/index.php/Home/Practice/getCount",{
                        nodeId:treeNode.ttr_NodeId
                    },function(data){
                        $('#count').html(data);
                    });
                }
                if(treeNode.level == 3){
                    var treeObj = $.fn.zTree.getZTreeObj(treeId);
                    treeObj.checkNode(treeNode,true,false);
                }
            }
            function confirmChoice(){
                var treeObj = $.fn.zTree.getZTreeObj("tree");
                var nodes = treeObj.getCheckedNodes(true);
                var ids;
//                    alert(nodes[0].ttr_NodeId);
                for(i = 0;i<nodes.length;i++){
                    ids = nodes[i].ttr_NodeId+","
                }
                if(nodes.length !== 0){
                    if(confirm('确认选择'+nodes[0].name)){
                        $.get("/SignUpSys/index.php/Home/Practice/submitResult",{ids:ids},function(data){
                            if(data == '1'){
                                window.location.reload();
                            }else{
                                alert("人数已达上限");
                            }
                        });
                    }else{
                        alert("error");
                    }
                }else{
                    alert("未选择");
                }
            }
            $().ready(function(){
                zTreeObj = $.fn.zTree.init($("#tree"), setting);
                var grade = "<?php echo $grade?>";
                $('#bt_sure').bind('click',confirmChoice);
                $('#pshunt').hide();
                if(grade == '<?php echo $rule_grade;?>'){
                    $('#pshunt').show();
                }
            });
        </script>
    </body>

</html>