<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Change Password?</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php
	include_once ('include/db-config.php');
        include "passwords.php";
	include "forgotmail.php";

?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.php">Tanyard Springs Board Portal</a>
      
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
	<?php 
		if ($_POST['submit'])
		{
			try
			{
				if ($_POST == "")
				{
					echo "You did not enter a password";
				}
				else
				{
				    $userfind=$db_con->prepare("SELECT * FROM users where email=:email;");
					$userfind->bindParam(':email',$_POST['email']);
					$userfind->execute();
					$userCount=$userfind->rowCount();
					$email=$_POST['email'];
					if ( $userCount == 1)
					{
						$temppassword=randomPassword();
						$temppassword1=sha1($temppassword);
						$updatepw = $db_con->prepare("update users set password = :password where email = :email;");
						$updatepw->bindParam(':password',$temppassword1);
						$updatepw->bindParam(':email',$_POST['email']);
						$updatepw->execute();
						temppassword($temppassword,$_POST['email']);
						echo "E-mail sent";
						$updatepw->closeCursor();
					}
					else
					{
						$userfind->closeCursor();
						echo "Your e-mail is not found in our database";
					}
				}
			}
			catch (PDOException $e)
			{
				echo "Database Error:<br />" . $e->getMessage();
			}
			catch (Exception $e)
			{
				echo "General Error: <br />" . $e->getMessage();
			}
		}
		else
		{
	?>

	<form action="forgotpw.php" method="POST">
		<fieldset>
		<legend>Change Password?</legend>E-mail: <input type="text" name="email" id="email">
		<br /><input type="submit" id="submit" name="submit" value="Submit">
		<input type="reset" id="reset" name="reset" value="Reset">
		</fieldset>
	</form>
	<?php
		}
	?>
	<!-- /span6 -->
        
        <!-- /span6 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->

<!-- /extra -->
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2013 <a href="http://www.egrappler.com/">Bootstrap Responsive Admin Template</a>. </div>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 

<script src="js/bootstrap.js"></script>
 
</body>
</html>
