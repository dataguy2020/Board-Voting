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
<title>Main Dashboard</title>
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>
<body>
<?php
	$userid=$_SESSION['user_id'];
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
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
        <li class="active"><a href="dashboard.php"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
	<li> <a href ="add.php"><i class ="icon-dashboard"></i><span>Add Motion</span></a></li>
	<li><a href="vote.php"><i class ="icon-dashboard"></i><span>Vote</span></a></li>
	<li><a href="discussions.php"><i class ="icon-dashboard"></i><span>Discussions</span></a></li>
	<li><a href="userprefs.php"><i class ="icon-dashboard"></i><span>Prefences</span></a></li>
         <li><a href="logout.php"><i class="icon-dashboard"></i><span>Logout</span></a> </li>

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
      <div class="row">
	      <div class="alert alert-danger" role="alert">
  		<strong>MESSAGE:</strong>This system will be going offline starting at 9:00p.m. on Friday September 29, 2017 until 12:00 a.m. on Saturday September 30, 2017
	      </div>
	<table border="1" width="100%" cellpadding="10">
		<tr>
			<th>Date Added</th>
			<th>Session</th>
			<th>Motion Name</th>
			<th>Motion Text</th>
			<th>Status</th>
			<th>Report</th>
		</tr>
        <?php
		include_once ('include/db-config.php');
		$motions=$db_con->prepare(
			"select * from motions ORDER BY dateadded desc;");
		$motions->execute();
		while ( $row = $motions->fetch(PDO::FETCH_ASSOC))
		{ ?>
		<form id="report" name="report" action="report.php" method="POST">
		 <?php $motionid=$row['motion_id']; ?>
		<tr>
			<td><?php echo $row['dateadded']; ?> </td>
			<td><?php echo $row['Session']; ?> </td>
			<td><?php echo $row['motion_name']; ?> </td>
			<td><?php echo $row['motion_description']; ?> </td>
			<td><?php echo $row['motion_disposition']; ?> </td>
		<?php echo '<input type="hidden" id="motionid" name="motionid" value="' . $motionid . '">'; ?>
			<td><input type="Submit" name="Submit" value="Report"></td>
		</tr>
		</form>
		<?php }//end of while ?>
	</table>
	      <br />
	      <br />	
	      <h3>Legend</h3>
	      <p>Deferred: Deferred to be discussed at the next board meeting</p>
	      <p>Approved: Approved as it will be ratified at the next board meeting</p>
	      <p>Failed: Will be discussed at the next board meeting</p>
	      <p>In Progress: Currently open for voting</p>
	      <p>Revoked: Revoved by board meeting. Not open for voting anymore</p>
	      
	<?php $motions->closeCursor(); ?>
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
