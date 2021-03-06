<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>管理员登录</title>

        <link href="<?php echo APP_CSS_URL;?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>datepicker3.css" rel="stylesheet">
        <link href="<?php echo APP_CSS_URL;?>styles.css" rel="stylesheet">

        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>
	
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">管理员登录</div>
				<div class="panel-body">
                                    <form role="form" action="/SignUpSys/index.php/Admin/Login/doLogin" method="post">
						<fieldset>
							<div class="form-group">
								<input class="form-control" placeholder="帐号" name="username" type="text" autofocus="">
							</div>
							<div class="form-group">
								<input class="form-control" placeholder="密码" name="password" type="password" value="">
							</div>
                                                        <button type="submit" class="btn btn-primary">登录</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
	
		

	<script src="<?php echo APP_JS_URL;?>jquery-1.11.1.min.js"></script>
	<script src="<?php echo APP_JS_URL;?>bootstrap.min.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
				$(this).find('em:first').toggleClass("glyphicon-minus");	  
			}); 
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>	
    </body>

</html>