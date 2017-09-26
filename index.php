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
	`<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">
    
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/pages/signin.css" rel="stylesheet" type="text/css">
  <style>
#error-msg{ display:none }
#success-msg{ display:none }
	  .name {
		font-family: 'Tangerine', serif;
        	font-size: 48px;
		  text-align: center;
vertical-align: middle;
	  }
	  
</style>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>

<body>
	
<div class="account-container">
	
	<div class="content clearfix">
		<p style="text-align:center"><img src="./img/image004.png" /></p>
		<br /><br /><div class="name">Board of Directors <br /><br />Online Voting System</div><br /><br />
		<form name="log-in-form"  id="log-in-form" method="post">
		
            <div class="alert" id="error-msg">
            
           </div>
           
           <div class="alert alert-success" id="success-msg">
            
          </div>	
			
			<div class="login-fields">
				
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
      <div class="alert alert-danger" role="alert">
  		<strong>MESSAGE:</strong>This system will be going offline starting at 9:00p.m. on Friday September 29, 2017 until 12:00 a.m. on Saturday September 30, 2017
	      </div>




<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>

<script src="js/login.js"></script>
</body>

</html>
