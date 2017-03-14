<?php
session_start();
if(($_SESSION['user_id'])){
header('location: dashboard.php');
        }
?>

<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>Tanyard Springs HOA Board Portal</title>
	<meta name="detectify-verification"
content="7d5cea67efa74a6200b4a875969ce64d" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/pages/signin.css" rel="stylesheet" type="text/css">
  <style>
#error-msg{ display:none }
#success-msg{ display:none }
</style>
</head>

<body>
	
	<div class="navbar navbar-fixed-top">
	
	<div class="navbar-inner">
		
		<div class="container">
			
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand" href="index.php">
				Tanyard Springs Board Portal
			</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					
					<!--<li class="">						
						<a href="signup.php" class="">
							Don't have an account?
						</a>
						
					</li>-->
					
				
				</ul>
				
			</div><!--/.nav-collapse -->	
	
		</div> <!-- /container -->
		
	</div> <!-- /navbar-inner -->
	
</div> <!-- /navbar -->



<div class="account-container">
	
	<div class="content clearfix">
		
		<form name="log-in-form"  id="log-in-form" method="post">
		
			<h1>Login</h1>	
            <div class="alert" id="error-msg">
            
           </div>
           
           <div class="alert alert-success" id="success-msg">
            
          </div>	
			
			<div class="login-fields">
				
				<p>Please provide your details</p>
				
				<div class="field">
					<label for="username">Email</label>
					<input type="text" id="username" name="email"  placeholder="Email" class="login username-field" />
				</div> <!-- /field -->
				
				<div class="field">
					<label for="password">Password:</label>
					<input type="password" id="password" name="password"  placeholder="Password" class="login password-field"/>
				</div> <!-- /password -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
				
			
									
				<button class="button btn btn-success btn-large" id="log-in">Sign In</button>
				
			</div> <!-- .actions -->
			
			
			
		</form>
		<p><a href="forgotpw.php">Forgot Password?</a></p>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->





<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/login.js"></script>
</body>

</html>
