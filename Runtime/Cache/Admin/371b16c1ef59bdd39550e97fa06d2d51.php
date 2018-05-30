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
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php echo $manager; ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
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
        </div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
            <div class="row">
                    <ol class="breadcrumb">
                            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
                            <li class="active">实训选择情况</li>
                    </ol>
            </div><!--/.row-->
            
            <div class="row">
                <div class="select_button">
                    <label>专业</label>
                    <select id="select_profession1" class="form-control"> 
                        <?php echo $profession;?>
                    </select>
                </div>
                <div class="select_button">
                    <label>年级</label>
                    <select id="select_grade" class="form-control"> 
                    </select>
                </div>
                <div class="select_button">
                    <label>实训时间</label>
                    <select id="select_time" class="form-control"></select>
                </div>
                <div class="select_button">
                    <label>实训名称</label>
                    <select id="select_course" class="form-control"></select>
                </div>
                <button id="bt_select" class="btn btn-info">查询</button>
                <button id="bt_output" class="btn btn-info">导出</button>
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
                    }]
                });
                
            }
            window.onload = initTable(); 
            function getGrade(){
                var profession = $('#select_profession1').val();
                $.get("/SignUpSys/index.php/Admin/TSelection/getGrade",{
                    profession:profession
                },function(data){
                    $('#select_grade').html(data);
                });
            }
            function getTime(){
                var grade = $('#select_grade').val();
                var profession = $('#select_profession1').val();
                if(grade == null || grade == "")
                {
                    return;
                }
                $.get("/SignUpSys/index.php/Admin/TSelection/getTime",{
                    grade:grade,
                    profession:profession
                },function(data){
                    $('#select_time').html(data);
                });
            }
            function getCourse(){
                var grade = $('#select_grade').val();
                var profession = $('#select_profession1').val();
                var time = $('#select_time').val();
                if(grade == null || grade == "")
                {
                    return;
                }
                $.get("/SignUpSys/index.php/Admin/TSelection/getCourse",{
                    grade:grade,
                    profession:profession,
                    time:time
                },function(data){
                    $('#select_course').html(data);
                });
            }
            function getInfo(){
                var id = $("#select_course option:selected").attr("id");
//                var nodeName = $('#select_course').val();
                if(id){
                    $('#stuTable').bootstrapTable('removeAll');
                    $('#stuTable').bootstrapTable(
                        'refresh',{
                        url: "/SignUpSys/index.php/Admin/TSelection/getInfo?nodeId="+id, //重设数据来源
                        }
                    );
                }
            }
            function outPut(){
                window.open('/SignUpSys/index.php/Admin/TSelection/getExcel'); 
            }
            $().ready(function(){
                $('#select_profession1').bind("click",getGrade);
                $('#select_grade').bind("click",getTime);
                $('#select_time').bind("click",getCourse);
                $('#bt_select').bind("click",getInfo);
                $('#bt_output').bind("click",outPut);
            });
        </script>
</body>

</html>