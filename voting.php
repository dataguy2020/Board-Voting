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
		function mailing($motionid,$boardEmail,$managementEmail)

	{
		global $db_con;
		$motionArray = array($motionid);
		foreach ($motionArray as $motion)
		{
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			$motion->execute();
			$body="<html>
					<head>
						<title>Status of Motion</title>
					</head>
					<body>";
			 while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			 {
			 	$motionid=$row['motion_id'];
			 	$motionname=$row['motion_name'];
			 	$dateadded=$row['dateadded'];
			 	$motiondesc=$row['motion_description'];
			 	$disposition=$row['motion_disposition'];
			 	 $body .= "<h1>" . $motionname . "</h1>
                                	   <h2>Date Added:</h2>" . $dateadded . "<br />
                                	   <h2>Motion Text</h2>" .
                                	   $motiondesc .
                                	   "<h2>Disposition:</h2>" .
                                	   $disposition;
			}//End of while
			$body .= "<br /><br />
					<h2>Current Votes</h2>
					<table border=\"1\" width=\"100%\">
					<tr>
						<th>User</th>
						<th>Date</th>
						<th>Vote</th>
					</tr>";
			$votes=$db_con->prepare(
                        	"SELECT u.first_name, u.last_name, v.time, v.vote from votes v 
				inner join motions m on m.motion_id=v.motions_id INNER join users u on 
				u.users_id=v.users_id where m.motion_id=:motionid ORDER BY v.time ASC;");
                     	
			$votes->bindParam(':motionid',$motionid);
                        $votes->execute();
                        while ($row=$votes->fetch(PDO::FETCH_ASSOC))
                        {
                        	$firstname=$row['first_name'];
                                $lastname=$row['last_name'];
                                $votetime=$row['time'];
                                $votecast=$row['vote'];
                                $body .= "<tr>
					<td>" . $firstname . " " . $lastname . "</td>
                                        <td>" . $votetime . "</td>
                                        <td>" . $votecast . "</td>
                                        </tr>";
                        }// while ($row=$votes->fetch(PDO::FETCH_ASSOC))
    			$body .= "</table>";
			$body .= "<br /><br />
					<h2>Discussions</h2>
					<table border=\"1\" width=\"100%\">
					<tr>
						<th>User</th>
						<th>Date</th>
						<th>Comment</th>
					</tr>";
			$motiondiscussions=$db_con->prepare(
				"SELECT u.first_name,u.last_name,d.dateadded,d.discussion_text
				 from users u inner join discussion d on d.user_id=u.users_id where d.motion_id=:motionid");
			$motiondiscussions->bindParam(':motionid',$motionid);
                       	$motiondiscussions->execute();
			while ($row=$motiondiscussions->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
                                $discussiontime=$row['dateadded'];
                                $discussiontext=$row['discussion_text'];
				$body .= "<tr>
							<td>" . $firstname . " " . $lastname . "</td>
							<td>" . $discussiontime . "</td>
							<td>" . $discussiontext . "</td>
						</tr>";
			}//end of while
			$body .= "</table>";
			echo "<br />";
			echo '<h2>Change Log</h2>
		<table border=\"1\" width=\"100%\">
			<tr>
				<th>User</th>
				<th>Date</th>
				<th>Field</th>
				<th>Old Value</th>
				<th>New Value</th>
			</tr>';
			
				$changeLog=$db_con->prepare(
					"SELECT u.first_name,u.last_name,mcl.date, mcl.field,mcl.oldValue,mcl.newValue 
					FROM users u 
					inner join motionChangeLog mcl on mcl.userid=u.users_id 
					WHERE mcl.motionid=:motionid;");
				$changeLog->bindParam(':motionid',$motionid);
				$changeLog->execute();
				while ($row=$changeLog->fetch(PDO::FETCH_ASSOC))
				{
					$firstname=$row['first_name'];
					$lastname=$row['last_name'];
					$changeLogTime=$row['date'];
					$field=$row['field'];
					$oldValue=$row['oldValue'];
					$newValue=$row['newValue'];
					echo "<tr>";
						echo "<td>" . $firstname . " " . $lastname . "</td>";
						echo "<td>" . $changeLogTime . "</td>";
						echo "<td>" . $field . "</td>";
						echo "<td>" . $oldValue . "</td>";
						echo "<td>" . $newValue . "</td>";
					echo "</tr>";
				}//end of while
				echo "</table>";
			$body .= "</body>
				</html>";
		}//end of foreach
		$emailSearch=$db_con->prepare("SELECT email from users where enabled=1;");
		$emailSearch->execute();
		$subject = "Motion Summary for Motion:" . $motionname;
		$message = $body;
		$to=$managementEmail;
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "Cc: $boardEmail";
		$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
		
		
		if(mail($to,$subject,$message, implode("\r\n", $headers)))
			print "<br />Email successfully sent";
		else
			print "<br />An error occured";	
	}//end of function
	?>
			<?php
				
				//include "mail.php";
	      			$decision=$_POST['vote'];
				$motionid=$_POST['motionid'];
	      			echo "Motion ID: " . $motionid;
	      			echo "<br />Decision: " . $decision;
			
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
						$secondedVote=$db_con->prepare(
							"SELECT * FROM votes where motions_id=:motionid;");
						$secondedVote->bindParam(':motionid',$motionid);
						$secondedVote->execute();
						$secondedCount=$secondedVote->rowCount();
						if ($secondedCount == 1)
						{
							if ($decision == "NO")
							{
								$initialVote=$db_con->prepare(
								"INSERT INTO votes (users_id,motions_id,vote) VALUE (:users_id, :motions_id, :vote)");
								$initialVote->bindParam(':users_id',$userid);
								$initialVote->bindParam(':motions_id',$motionid);
								$initialVote->bindParam(':vote',$decision);
								$initialVote->execute();
								echo "Voted";
								
								$disposition="FAILED";
								$motiondep=$db_con->prepare(
								"UPDATE motions set motion_disposition =:disposition WHERE motion_id=:motion_id");
								$motiondep->bindParam(':disposition',$disposition);
								$motiondep->bindParam(':motion_id',$motionid);
								$motiondep->execute();
							
								//grabbing email addresses for Board and management
                                                       	 	$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
                                                        	$userSearch->execute();
                                                        	$boardEmail="";
                                                        	while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
                                                        	{
                                                           	     $boardEmail .= $row['email'] .",";
                                                       		}
                                                       		$managementSearch=$db_con->prepare("SELECT * FROM management where fenabled=1;");
                                                        	$managementSearch->execute();
                                                        	$managementEmail="";
                                                        	while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
                                                        	{
                                                                	$managementEmail .= $row['email'] .",";
                                                        	}
                                                        	mailing($motionid,$boardEmail,$managementEmail);
							}
							
							else
							{
								$decision="SECONDED";
								$initialVote=$db_con->prepare(
									"INSERT INTO votes (users_id,motions_id,vote) VALUE (:users_id, :motions_id, :vote)");
								$initialVote->bindParam(':users_id',$userid);
								$initialVote->bindParam(':motions_id',$motionid);
								$initialVote->bindParam(':vote',$decision);
								$initialVote->execute();
								echo "Voted";
							}
							
						}
						else
						{
							$initialVote=$db_con->prepare(
								"INSERT INTO votes (users_id,motions_id,vote) VALUE (:users_id, :motions_id, :vote)");
							$initialVote->bindParam(':users_id',$userid);
							$initialVote->bindParam(':motions_id',$motionid);
							$initialVote->bindParam(':vote',$decision);
							$initialVote->execute();
							echo "Voted";
						}
						
						$enabledCount=$db_con->prepare(
						"SELECT * FROM users where enabled=1;");
						$enabledCount->execute();
						$enabledUserCount = $enabledCount->rowCount();

						$votecount=$db_con->prepare(
						"SELECT * FROM votes where motions_id=:motionid");
						$votecount->bindParam(':motionid',$motionid);
						$votecount->execute();
						$votesCount= $votecount->rowCount();


						if ($votesCount == $enabledUserCount)
						{
							$disposition="PASSED";
							$motiondep=$db_con->prepare(
                                                		"UPDATE motions set motion_disposition =:disposition WHERE motion_id=:motion_id");
                                                	$motiondep->bindParam(':disposition',$disposition);
                                                	$motiondep->bindParam(':motion_id',$motionid);
                                                	$motiondep->execute();
							echo "<br /> Updated the final disposition of the motion";
							
							//grabbing email addresses for Board and management
							$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
							$userSearch->execute();
							$boardEmail="";
							while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
							{
								$boardEmail .= $row['email'] .",";
							}
							$managementSearch=$db_con->prepare("SELECT * FROM management where fenabled=1;");
							$managementSearch->execute();
							$managementEmail="";
							while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
							{
								$managementEmail .= $row['email'] .",";
							}
							mailing($motionid,$boardEmail,$managementEmail);
						}
						else
						{
							echo "";	
						}

						if ($decision=="NO")
						{						
							$disposition="FAILED";
							$motiondep=$db_con->prepare(
								"UPDATE motions set motion_disposition =:disposition WHERE motion_id=:motion_id");
							$motiondep->bindParam(':disposition',$disposition);
							$motiondep->bindParam(':motion_id',$motionid);
							$motiondep->execute();
							
							//grabbing email addresses for Board and management
                                                        $userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
                                                        $userSearch->execute();
                                                        $boardEmail="";
                                                        while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
                                                        {
                                                                $boardEmail .= $row['email'] .",";
                                                        }
                                                        $managementSearch=$db_con->prepare("SELECT * FROM management where fenabled=1;");
                                                        $managementSearch->execute();
                                                        $managementEmail="";
                                                        while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
                                                        {
                                                                $managementEmail .= $row['email'] .",";
                                                        }
                                                        mailing($motionid,$boardEmail,$managementEmail);

						}
						else
						{
						 	exit;
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
