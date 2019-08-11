<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');	
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Motion</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
	<script src="https://cdn.ckeditor.com/ckeditor5/12.3.1/classic/ckeditor.js"></script>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php
	include_once('db-config.php');
?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.php">Bootstrap Admin Template </a>
      
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
        <li><a href="dashboard.php"><em class="icon-dashboard"></em><span>Dashboard</span> </a> </li>
	<li class="active"> <a href ="add.php"><em class ="icon-dashboard"></em><span>Add Motion</span></a></li>
	<li><a href="vote.php"><em class ="icon-dashboard"></em><span>Vote</span></a></li>
	 <li><a href="discussions.php"><em class ="icon-dashboard"></em><span>Discussions</span></a></li>
	<li><a href="userprefs.php"><em class ="icon-dashboard"></em><span>Prefences</span></a></li>
	      <li><a href="users.php"><em class="icon-dashboard"></em><span>Users</span></a></li>
         <li><a href="logout.php"><em class="icon-dashboard"></em><span>Logout</span></a> </li>

      </ul>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
	<form action="addmotion.php"  method="post">
		<FIELDSET>
			<LEGEND><strong>Submit a Motion</strong></LEGEND>
			<p><label>Motion Name: </label><input type="text" name="motionname" id="motionname" /></p>
			<p>Session:<br />
				<select name="boardsession" id="boardsession">
					<option value=""></option>
					<option value="Executive">Executive</option>
					<option value="Public">Public</option>
				</select>
			</p>
			<p><label>Motion Text: </label><textarea name="motiontext" id="motiontext" style="width: 1136px; height: 122px;"></textarea>
			<script>
    ClassicEditor
        .create( document.querySelector( '#motiontext' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
			<br /><input type="submit" value="Submit" name="Submit" />
			<input type="reset" value="Reset" name="Reset" />
		</FIELDSET>
	</form>

<!-- /span6 -->
        
        <!-- /span6 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
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
