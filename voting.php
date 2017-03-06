<?php
session_start();
if(empty($_SESSION['user_id'])){
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
	 <li><a href="dashboard.php"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
        <li> <a href ="add.php"><i class ="icon-dashboard"></i><span>Add Motion</span></a></li>
        <li class="active"><a href="vote.php"><i class ="icon-dashboard"></i><span>Vote</span></a></li>
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
	
			<?php
				include_once ('include/db-config.php');
				#Debug: echo $_SERVER['HTTP_REFERER'];
				$decision=$_POST['vote'];
				$motionid=$_POST['motionid'];

				if (isset($_POST['revote']))
				{
					//allready have userid
					$revoteoption=$_POST['revote1'];
					$vote=$_POST['vote'];
					if ( $revoteoption == "Yes" )
					{
						$updateVote=$db_con->prepare(
						"UPDATE votes set vote=:updatedvote where motions_id=:motionid AND users_id=:userid;");
						$updateVote->execute(array(':updatedvote'=>$vote,':motionid' =>$motionid, ':userid' => $userid));
						echo "Updated your vote";
					}// if ( $revoteoption == "Yes"
					else
					{
						echo "You decided not to revote";
					}//end of else if ( $revoteoption == "Yes"	
				}// if (isset($_POST['revote']))

				else
				{
					$addvote=$db_con->prepare(
						"SELECT * FROM votes WHERE users_id=:userid AND motions_id=:motionsid;");
					$addvote->execute(array(':userid' => $userid,':motionsid' => $motionid));
					$row=$addvote->fetchAll(PDO::FETCH_ASSOC);
					#Debug: echo count($row);
					if (count($row) == 1)
					{
						echo 	'<form id="voting" name="voting" method="POST" action="voting.php">
                					<input type="hidden" name="motionid" value="' . $motionid . '">
							<input type="hidden" name="revote" value="revote">
							<input type="hidden" name="vote" value="' . $_POST['vote'] . '">
                					<input type="radio" name="revote1" value="Yes">Yes<br />
                					<input type="radio" name="revote1" value="No">No<br />
                					<input type="Submit" name="Submit" value="Submit">
               						<input type="Reset" name="Reset" value="Reset">
        						</form>';
					}// if (count($row) == 1)
					else
					{
						#Debug: echo "Vote: " . $vote . "<br />";
						#Debug: echo "Decision: " . $decision . "<br />";
						$initialVote=$db_con->prepare(
						"INSERT INTO votes (users_id,motions_id,vote) VALUE (:users_id, :motions_id, :vote)");
						$initialVote->bindParam(':users_id',$userid);
						$initialVote->bindParam(':motions_id',$motionid);
						$initialVote->bindParam(':vote',$decision);
						$initialVote->execute();
						echo "Voted";

						#$enabledCount=$db_con->prepare(
						#"SELECT * FROM users where enabled=1;");
						#$enabledCount->execute();
						#$row=$enabledCount->fetchAll(PDO::FETCH_ASSOC);
						#$usercount=count($row);


						#$votecount=$db_con->prepare(
						#"SELECT * FROM votes where motions_id=:motionid");
						#$votecount->bindParam(':motionid',$motionid);
						#$votecount->execute();
						#$voterow=$votecount->fetch(PDO::FETCH_ASSOC);
						#$votecount=count($voterow);

						#echo "<br />User Count: " . $usercount . "<br />";
						#echo "Vote Count: " . $votecount;
						#if ($usercount == $votecount)
						#{
						#	$disposition="PASSED";
						#	$motiondep=$db_con->prepare(
                                                #		"UPDATE motions set motion_disposition =:disposition WHERE motion_id=:motion_id");
                                                #	$motiondep->bindParam(':disposition',$disposition);
                                                #	$motiondep->bindParam(':motion_id',$motionid);
                                                #	$motiondep->execute();
						#	echo "<br /> Updated the final disposition of the motion";
						#}
						#else
						#{
						#	echo "";	
						#}

						if ($decision=="NO")
						{						
							$disposition="FAILED";
							$motiondep=$db_con->prepare(
								"UPDATE motions set motion_disposition =:disposition WHERE motion_id=:motion_id");
							$motiondep->bindParam(':disposition',$disposition);
							$motiondep->bindParam(':motion_id',$motionid);
							$motiondep->execute();
						}
						else
						{
						 	echo "";
						}
					}//end of else if (count($row) == 1)
				}//end of else  if (isset($_POST['revote']))
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
