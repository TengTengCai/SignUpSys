<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>选课情况</title>

<link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet"/>
<link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet"/>
<link href="<?php echo APP_CSS_URL;?>datepicker3.css" rel="stylesheet"/>
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
				<a class="navbar-brand" href="#"><span>Class</span>Admin</a>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Admin <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="/SignUpSys/index.php/Admin/CSelection/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
            <ul class="nav menu">
                <li><a href="/SignUpSys/index.php/Admin/Student/"><span class="glyphicon glyphicon-dashboard"></span>学生信息</a></li>
                <li class="parent">
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
                <li class="parent active">
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
                            <li class="active">选课情况</li>
                    </ol>
            </div><!--/.row-->
            
            <div class="row">
                <div class="select_button">
                    <label>年级</label>
                    <select id="select_grade" class="form-control"> 
                        <?php echo $grades;?>
                    </select>
                </div>
                <div>
                    <label>专业</label>
                    <select id="select_major" class="form-control">
                        <?php echo $major;?>
                    </select>
                </div>
                <div class="select_button">
                    <label>方向/模块</label>
                    <select id="select_direction" class="form-control"> 
                    </select>
                </div>

                <button id="bt_select" class="btn btn-info">查询</button>
                <button id="bt_output" class="btn btn-info">导出</button>
                <button id="bt_without" class="btn btn-info">未选择查询</button>
            </div><!--/.row-->
                <table data-toggle="table"  id="stuTable" >
            </table>
	</div>	<!--/.main-->

	<script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo APP_JS_URL;?>jquery.ztree.all.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap-table.js"></script>
	<script>
		$('#calendar').datepicker({
		});
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
                    exportDataType: "basic",            //basic', 'all', 'selected'.
                    striped: true,  //表格显示条纹
                    toolbar: '#toolbar',
                    pagination: true,
                    dataType: 'json',
                    showRefresh:false,
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
                        width:'20%',
                        title:'姓名'
                    },{
                        field:'stu_Sex',
                        width:'10%',
                        title:'性别'
                    },{
                        field:'stu_Grade',
                        width:'10%',
                        title:'年级'
                    },{
                        field:'stu_Profession',
                        width:'30%',
                        title:'专业'
                    },{
                        field:'stu_Class',
                        width:'10%',
                        title:'班级'
                    },],
                    onLoadSuccess: function (data) {
                         $('#bt_select').show();
                        return false;
                    },
                });
                
            }
            window.onload = initTable(); 
//            function getCourseName(){
//                var direction = $('#select_direction').val();
//                var grade = $('#select_grade').val();
//                $.get("/SignUpSys/index.php/Admin/CSelection/getClassName",{
//                    direction:direction,
//                    grade:grade
//                },function(data){
////                    alert(data);
//                    $('#select_course').html(data);
//                });
//            }
            function getWithout(){
                $('#stuTable').bootstrapTable('removeAll');
                $('#stuTable').bootstrapTable(
                    'refresh',{
                    url: "/SignUpSys/index.php/Admin/CSelection/getWithout"
                    }
                );
            }
            function getInfo(){
                var nodeID = $('#select_direction').find("option:selected").attr("id");
                var grade = $('#select_grade').val();
                //$('#bt_select').hide();
//                alert(nodeID);
//                $.post("/SignUpSys/index.php/Admin/CSelection/getInfo",{
//                    cId:couser
//                },function(data){
//                    alert(data);
//                    eval('(' + data + ')');
//                    $('#stuTable').bootstrapTable('removeAll');
//                    $('#stuTable').bootstrapTable('load',data);
//                });
//                
                $('#stuTable').bootstrapTable('removeAll');
                $('#stuTable').bootstrapTable(
                    'refresh',{
                    url: "/SignUpSys/index.php/Admin/CSelection/getInfo?nodeID="+nodeID+"&grade="+grade, //重设数据来源
                    }
                );
            }
            function getDirection(){
                var major = $('#select_major').val();
                $.get("/SignUpSys/index.php/Admin/CSelection/getDirection",{
                    major:major
                },function(data){
//                    alert(data);
                    $('#select_direction').html(data);
                });
            }
            function outPut(){
                window.open('/SignUpSys/index.php/Admin/CSelection/getExcel'); 
            }
            $().ready(function(){
                //$('#select_direction').bind("click",getCourseName);
                $('#select_major').bind("click",getDirection);
                $('#bt_select').bind("click",getInfo);
                $('#bt_output').bind("click",outPut);
                $('#bt_without').bind("click",getWithout);
            });
        </script>
</body>

</html>