<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <title>学生选择的信息</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>bootstrap-table.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet">
    </head>
    <body id="mybody">
        <div>
            <h1 class="page-header">当前<span id="number"><?php echo $name;?></span>信息</h1>
            <hr/>
            <p><?php echo $message;?></p>
            <button class="btn btn-info" id="btn_clearC">清除专业分流选择</button>
            <button class="btn btn-info" id="btn_clearT">清除实训课程选择</button>
            <button class="btn btn-info" id="btn_reset">重置密码</button>
            <p>专业分流详情</p>
            <div class="row">
                <table class="table table-hover" >
                    <thead>
                        <tr>
                            <th>课程名称</th>
                            <th>人数</th>
                            <th>限制</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $table;?>
                    </tbody>
                </table>
            </div>
            <p>实训选择详情</p>
            <div class="row">
                <table class="table table-hover" >
                    <thead>
                        <tr>
                            <th>课程名称</th>
                            <th>人数</th>
                            <th>限制</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $mytable;?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="<?php echo APP_JS_URL;?>jquery-3.1.1.js"></script>
        <script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
        <script>
             $('#btn_clearC').click(
                function(){
                    if(confirm("确定删除专业选择？")){
                        $number = $('#number').html();
//                        alert($number);
                        $.get("/SignUpSys/index.php/Admin/Student/clearChoice",{stu_Number:$number},
                        function(data){
                            alert(data);
                            window.close();
                        });
                    }else{
                        return;
                    }
                });
            $('#btn_clearT').click(
                function(){
                    if(confirm("确定删除实训课程？")){
                        $number = $('#number').html();
//                        alert($number);
                        $.get("/SignUpSys/index.php/Admin/Student/clearTraining",{stu_Number:$number},
                        function(data){
                            alert(data);
                            window.close();
                        });
                    }else{
                        return;
                    }
                });    
            $('#btn_reset').click(function(){
                if(confirm("是否重置该学生密码？")){
                    $number = $('#number').html();
//                        alert($number);
                        $.get("/SignUpSys/index.php/Admin/Student/reSetPassword",{stu_Number:$number},
                        function(data){
                            alert(data);
                            window.close();
                        });
                }else{
                    return;
                }
            });
        </script>
            

    </body>
</html>