<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once 'config.php'; session_start(); ?>
        
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title><?php echo conf::APP_Name; ?></title>
        <meta name="description" content="Automatic Cat Feeder" />
        <meta name="keywords" content="automatic cat feeder" />
        <meta name="author" content="<?php echo conf::APP_Developer; ?>" />
        <link rel="shortcut icon" href="favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/noty/packaged/jquery.noty.packaged.min.js"></script>
		<script type="text/javascript" src="js/modernizr.custom.63321.js"></script>
        
        <script type="text/javascript">
			$( document ).ready(function() {
				$('body').css({background: '#eedfcc url(images/bg' + (1 + Math.floor(Math.random() * 11)) + '.jpg) no-repeat center top', "-webkit-background-size": 'cover', "-moz-background-size": 'cover', "background-size": 'cover'});
			});
		</script>
        
		<style>
			body {
				background: #eedfcc url(images/bg.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
		</style>
        
    </head>
    <body>
        <p>&nbsp;</p>
        <div class="container">
			<section class="main">
            	<div class="content">
                	<div class="title" align="center"><?php echo conf::APP_Name; ?></div>
    				<div class="sub-title" align="center"><?php echo conf::APP_Version; ?> - By <?php echo conf::APP_Developer; ?> - Time: <?php echo date("G:i:s"); ?></div>
					
                    <div id="include-content">
						<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                            include 'include/control_feeder.php';
                        } else {
                            include 'include/login_form.php';
                        } ?>
                    </div>
                    <div id="loading" align="center" style="display:none;">
                        <img border="0" src="images/ajax-loader.gif" />
                    </div>
            	</div>
			</section>
        </div>
    </body>
    </body>
</html>