<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>选择结果</title>
        <meta charset="UTF-8">
        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet"/>
        <link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet">
    </head>
    <body>
        <div id="result_main" >
            <div>
                <p><?php echo $stuData?></p>
                <h1>你选了如下内容</h1>
                <dl class="dl-horizontal">
                    <?php echo $msg;?>
                </dl>
            </div>
            <div>
                <p>关联课程</p>
                <table data-toggle="table">
                    <thead>
                        <tr>
                            <th data-width="20%">名称</th>
                            <th data-width="10%">人数</th>
                            <th data-width="10%">上限</th>
                            <th data-width="60%">课程简介</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $table;?>
                    </tbody>
                </table>
            </div>
            <button id="result_bt_sure" class="btn btn-info">确定</button>
            <button id="result_bt_cancel" class="btn btn-info">取消</button>
        </div>
        <script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
	<script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap-table.js"></script>
        <script>
            var ids = "<?php echo $ids;?>";
            var error = "<?php echo $error;?>";
            $('#result_bt_sure').click(function(){
                if(error == 1){
                    alert("所选课程超过限制");
                    location.href = "/SignUpSys/index.php/Home/Student/choice";
                }else {
                    $.get("/SignUpSys/index.php/Home/Student/submitResult",{ids:ids},function(data){
                        if(data ==='1'){
                            location.href = "/SignUpSys/index.php/Home/Student/choiced";
                        }else{
                            location.href = "/SignUpSys/index.php/Home/Student/choice";
                        }
                    });
                }
            });
        </script>
    </body>
</html>