<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>学生信息</title>

        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet">


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
                                                    <li id="logout"><a href="/SignUpSys/index.php/Admin/Student/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                                                </ul>
                                        </li>
                                </ul>
                        </div>
                </div><!-- /.container-fluid -->
        </nav>

        <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
            <ul class="nav menu">
                <li class="active"><a href="/SignUpSys/index.php/Admin/Student/"><span class="glyphicon glyphicon-dashboard"></span>学生信息</a></li>
                <li class="parent">
                    <a href="#">
                        <span class="glyphicon glyphicon-list"></span> 信息 <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span>
                    </a>
                    <ul class="children collapse" id="sub-item-1">
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
                        <span class="glyphicon glyphicon-th"></span> 选择情况 <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span>
                    </a>
                    <ul class="children collapse" id="sub-item-2">
                            <li>
                                    <a class="" href="/SignUpSys/index.php/Admin/CSelection/">
                                            <span class="glyphicon glyphicon-share-alt"></span> 专业选择情况
                                    </a>
                            </li>
                            <li>
                                    <a class="" href="/SignUpSys/index.php/Admin/TSelection">
                                            <span class="glyphicon glyphicon-share-alt"></span> 实训选择情况
                                    </a>
                            </li>
                    </ul>
                </li>  
                <li role="presentation" class="divider"></li>
            </ul>
            <div class="attribution"><p >Copyright&copy;<a href="http://www.tjclouds.cc/" target="_blank" title="tjclouds">tjclouds</a> <br/> Powered By SignUpSystem <br/>Version 1.0.2 </p></div>
        </div><!--/.sidebar-->
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li class="active">学生信息</li>
                </ol>
            </div><!--/.row-->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">学生信息</h1>
                </div>
            </div><!--/.row-->
            <div id="toolbar1" align="left">
                <input id="input_number" type="text" class="form-control" placeholder="学号">
                <input id="input_name" type="text" class="form-control" placeholder="姓名">
                <select id="input_grade" class="form-control"><?php echo $grade;?></select>
                <select id="input_profession" class="form-control"></select>
                <select id="input_class" class="form-control"></select>
                <button class="btn btn-info" id="bt_select">查询</button>
                <button class="btn btn-info" id="bt_reset">重置</button>
                <button class="btn btn-info" id="bt_output">导出</button>
            </div>
            <div class="toolbar"></div>
            <table data-toggle="table"  id="stuTable" >
                <thead>
                    <tr>
                        <th data-field="number"  data-sortable="true">学号</th>
                        <th data-field="name" >姓名</th>
                        <th data-field="sex" >性别</th>
                        <th data-field="grade" >年级</th>
                        <th data-field="profession" >专业</th>
                        <th data-field="class" >班级</th>
                    </tr>
                </thead>
            </table>
        </div>	<!--/.main-->
        <script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
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
            function initTable() {
                $('#stuTable').bootstrapTable({
                    method: 'get',
                    url:'/SignUpSys/index.php/Admin/Student/getAllStudentInfo',
                    showExport: true,                   //是否显示导出
                    exportDataType: "basic",            //basic', 'all', 'selected'.
                    striped: true,                      //表格显示条纹
                    toolbar: '#toolbar',
                    pagination: true,
                    dataType: 'json',
                    showRefresh:true,
                    search:true,
                    silent: true,
                    sortable: true,                     //是否启用排序
                    sortOrder: "asc",                   //排序方式
                    uniqueId: "number",                 //每一行的唯一标识，一般为主键列
                    pageSize: 20,                       //每页的记录行数（*）
                    pageList: [20, 50, 100, 200],       //可供选择的每页的行数（*）
                    columns: [{
                        field:'stu_Number',
                        title:'学号',
                        width:'20%',
                        sortable: true
                    },{
                        field:'stu_Name',
                        title:'姓名',
                        width:'10%'
                    },{
                        field:'stu_Sex',
                        title:'性别',
                        width:'10%'
                    },{
                        field:'stu_Grade',
                        title:'年级',
                        width:'10%'
                    },{
                        field:'stu_Profession',
                        title:'专业',
                        width:'40%'
                    },{
                        field:'stu_Class',
                        title:'班级',
                        width:'10%'
                    }],
                    onClickRow:function(row,tr){
                        window.open("/SignUpSys/index.php/Admin/Student/detail?stu_Number="+row.stu_Number,'_blank'
                        ,'width=555,height=555,menubar=no,toolbar=no,status=no,scrollbars=yes');
                    }
                });
            }
            window.onload = initTable(); 
            $('#bt_select').click(
                function (){
                    // $('#cusTable').bootstrapTable('destroy');
                    var number = $('#input_number').val();
                    var name = $('#input_name').val();
                    var garde = $('#input_grade').val() == null? "":$('#input_grade').val();
                    var profession = $('#input_profession').val()==null? "":$('#input_profession').val();
                    var classs = $('#input_class').val()==null? "":$('#input_class').val();
//                    alert(number+" "+name+" "+garde+" "+profession+" "+classs);
                    $('#stuTable').bootstrapTable('removeAll');
                    $('#stuTable').bootstrapTable(
                        'refresh',{
                        url: "/SignUpSys/index.php/Admin/Student/conditionQuery?number="+number+"&name="
                        +name+"&grade="+garde+"&profession="+profession+"&classs="+classs //重设数据来源
                        });
                }
            );
            function randomData(number,name,garde,profession,classs){
                var temp = null;
                $.get("/SignUpSys/index.php/Admin/Student/conditionQuery",{
                    number:number,
                    name:name,
                    garde:garde,
                    profession:profession,
                    classs:classs
                },function(data){
                    temp = data;
                });
                return temp;
            }
            $('#bt_reset').click(
                function (){
                    $('#stuTable').bootstrapTable(
                         'refresh',{
                        url: "/SignUpSys/index.php/Admin/Student/getAllStudentInfo" //重设数据来源
                        });
                    $('#input_number').val("");
                    $('#input_name').val("");
                    $('#input_grade').val("");
                    $('#input_profession').val("");
                    $('#input_class').val("");
                }
            );
            $('#bt_output').click(
                    function(){
                        window.open('/SignUpSys/index.php/Admin/Student/getExcel'); 
                    }
            );
            $('#logout').click(
                function(){
                    $.get("/SignUpSys/index.php/Admin/Student/logout",function(){
                        window.location.reload();
                    });
                }    
            );
            $('#input_grade').click(function(){
                $grade = $('#input_grade').val();
                $.get("/SignUpSys/index.php/Admin/Student/getProfessionList",{grade:$grade},function(data){
                    $('#input_profession').html(data);
                });
            });
            $('#input_profession').click(function(){
                $grade = $('#input_grade').val();
                $profession = $('#input_profession').val();
                if($grade!==null && $profession!==null){
                    $.get("/SignUpSys/index.php/Admin/Student/getClassList",{grade:$grade,profession:$profession},function(data){
                        $('#input_class').html(data);
                    });
                }
            });
            $().ready(function(){
                $('#input_number').val("");
                $('#input_name').val("");
                $('#input_grade').val("");
                $('#input_profession').val("");
                $('#input_class').val("");
            });
        </script>
    </body>
</html>