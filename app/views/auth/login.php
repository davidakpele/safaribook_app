<?php  if(isset($_SESSION['email'])){header('location:' . ROOT . 'Admin/');}?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8" />
    <meta name="theme-color" content="#c9190c" />
    <meta name="robots" content="index,follow" />
    <meta htttp-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="msapplication-TileColor" content="#c9190c" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SAFARI BOOKS LIMITED. 1 Shell Close, Ile Ori Detu, Onireke GRA, Ibadan," /> 
    <link rel="icon" type="image/png" sizes="16x16" href="<?=ASSETS?>icons/logo.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?=ASSETS?>icons/logo.png" />
    <link rel="apple-touch-icon" href="<?=ASSETS?>icons/logo.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)" />
    <link rel="apple-touch-startup-image" href="<?=ASSETS?>icons/logo.png" media="(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)" />
    <title><?=$data['page_title'] . " | " . WEBSITE_TITLE;?></title>
    <link rel="shortcut icon" href="<?=ASSETS?>icons/logo.png" />
    <link rel="stylesheet" href="<?=ASSETS?>fontawesome/css/all.css"/>
    <link rel="stylesheet" href="<?=ASSETS?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=ASSETS?>css/fpo787jkfd.css" />
    <link rel="stylesheet" href="<?=ASSETS?>css/jquery.jgrowl.css" media="screen"/>
    <!-- Alternative loader -->
    <link type="text/json" href="<?=ASSETS?>light/manifest.json"/>
    <script type="text/javascript" src="<?=ASSETS?>js/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="<?=ASSETS?>js/bootstrap.js"></script>
    <script>
        const base_url='<?=ROOT?>';
    </script>
</head>
    <body>
        <div class="s-background animated fadeIn">
            <div class="gradients">
                <div class="blue"></div>
            </div>
        </div>
        <center class="mt-5" style="margin-top:40px">
            <img src="<?=ASSETS?>icons/logo.png" class="img-responsive" style="max-width:120px">
            <h1 style="font-size: 30px; color: white;">SAFARI BOOKS LIMITED</h1>
            <h1 style="font-size: 20px; color: white;">Head Business Development</h1>
        </center>
        <div class="items">
            <div class="mini-container login-widget" > 
                <div class="container__login" style="padding-top:40px">
                    <div id="error" class="error error-ico" style="display:none"></div>
                        <form method="POST" id="signIn_form" class="form-group" autocomplete="off" >
                        <div class="row p-0">
                        <div class="input-group form-group">
			    	  		<div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                              <input type="email" id="email" name="email" class="form-control form-input" value="<?=((isset($_POST['email']))?$_POST['email']: '')?>" placeholder="Email Address" />
			    		</div>
			    		<div class="input-group form-group">
			    		 	<div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                            <input type="password" id="password" name="password" value="<?=((isset($_POST['password']))?$_POST['password']: '');?>" class="form-control form-input"  placeholder="Password" autocomplete="off" />
			    		</div>
			    		<div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="Remember Me"> Remember Me
			    	    	</label>
			    	    </div>
                            <div class="col-12 p-0 px-4_btn">
                                <button class="btn btn-danger w-100 login-btn" value="Login" type="submit">
                                    <i class="fa fa-sign-in"></i>Login
                                </button> 
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php $this->view("auth/inc/footer"); ?>