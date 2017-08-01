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
<title>Vote</title>
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
					<body>
					Management, <br />
					This motion was deferred to the next board meeting. Please add it to the agenda under 
					the motions section of the agenda.";
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
					<table border=\"1\" width=\"1\">
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
			$body .= "<br />";
			$body .= "<h2>Change Log</h2>
		<table border='1' width='100%'>
			<tr>
				<th>User</th>
				<th>Date</th>
				<th>Field</th>
				<th>Old Value</th>
				<th>New Value</th>
			</tr>";
			
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
					$body .= "<tr>";
						$body .= "<td>" . $firstname . " " . $lastname . "</td>";
						$body .= "<td>" . $changeLogTime . "</td>";
						$body .= "<td>" . $field . "</td>";
						$body .= "<td>" . $oldValue . "</td>";
						$body .= "<td>" . $newValue . "</td>";
					$body .= "</tr>";
				}//end of while
				$body .= "</table>";
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
	      
		if (!empty($_POST) && !isset($_POST['Amend']) && !isset($_POST['Revoke']) && !($_POST['Deferred']))
		{
			$motionid=$_POST['motionid'];
			#echo "Debug: " . $motionid;
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			$motion->execute();
			while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			{
				$motionid=$row['motion_id'];
				$motionname=$row['motion_name'];
				$dateadded=$row['dateadded'];
				$motiondesc=$row['motion_description'];
				$dispo=$row['motion_disposition'];
			
			
				echo "<h1>" . $motionname . "</h1><br />";
				echo "<h2>Date Added:</h2>" . $dateadded . "<br /><br />";
				echo "<h2>Motion Text</h2>";
				echo $motiondesc;
				
				echo "<h2> Motion Disposition </h2>";
				echo $dispo;
			}
			$motion->closeCursor();
			?>
	      <br />
	     	 <h2>Change Log</h2>
		<table border="1" width="100%">
			<tr>
				<th>User</th>
				<th>Date</th>
				<th>Field</th>
				<th>Old Value</th>
				<th>New Value</th>
			</tr>
			<?php
				$changeLog=$db_con->prepare(
					"SELECT u.first_name,u.last_name,mcl.date, mcl.field,mcl.oldValue,mcl.newValue 
					FROM users u 
					inner join motionChangeLog mcl on mcl.userid=u.users_id 
					WHERE mcl.motionid=:motionid ORDER BY mcl.date DESC;");
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
				?>
			<br /><br />
			<h2>Current Votes</h2>
			<table border="1" width="100%">
			<tr>
				<th>User</th>
				<th>Date</th>
				<th>Vote</th>
			</tr>
			<?php
				$votes=$db_con->prepare(
					"SELECT u.first_name, u.last_name, v.time, v.vote from votes v inner join motions m on m.motion_id=v.motions_id INNER join users u on u.users_id=v.users_id where m.motion_id=:motionid");
				$votes->bindParam(':motionid',$motionid);
				$votes->execute();
			while ($row=$votes->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
				$votetime=$row['time'];
				$votecast=$row['vote'];
				echo "<tr>";
					echo "<td>" . $firstname . " " . $lastname . "</td>";
					echo "<td>" . $votetime . "</td>";
					echo "<td>" . $votecast . "</td>";
				echo "</tr>";
			}
			$votes->closeCursor();
			echo "</table>";
		?>

		<br /><br />
		<h2>Discussions</h2>
		<table border="1" width="100%">
		<tr>
			<th>User</th>
			<th>Date</th>
			<th>Comment</th>
		</tr>
		<?php
			$motiondiscussions=$db_con->prepare(
			"SELECT u.first_name,u.last_name,d.dateadded,d.discussion_text from users u inner join discussion d on d.user_id=u.users_id where d.motion_id=:motionid");
			$motiondiscussions->bindParam(':motionid',$motionid);
			$motiondiscussions->execute();
			while ($row=$motiondiscussions->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
				$discussiontime=$row['dateadded'];
				$discussiontext=$row['discussion_text'];
				echo "<tr>";
					echo "<td>" . $firstname . " " . $lastname . "</td>";
					echo "<td>" . $discussiontime . "</td>";
					echo "<td>" . $discussiontext . "</td>";
				echo "</tr>";
			}//end of while
			$motiondiscussions->closeCursor();
			echo "</table>";
		?>
		 <br />
        <br />
	<h2>Your Decision</h2>
		
        <?php echo '<form id="voting" name="voting" method="POST" action="voting.php">
                <input type="hidden" name="motionid" value="' . $motionid . '">
		<input type="radio" name="vote" value="YES">Vote for Motion<br />
                <input type="radio" name="vote" value="NO">Vote against Motion<br />
                <input type="Submit" name="Submit" value="Submit">
                <input type="Reset" name="Reset" value="Reset">
        </form>';
	?>

		<?php			
		}//end of if statement
		
		elseif (isset($_POST['Deferred']))
		{
			$userid=$_SESSION['user_id'];
			$motionid=$_POST['motionid'];
			$action=$_POST['Deferred'];
			$deferredMotion=$db_con->prepare("UPDATE motions set motion_disposition=:dispo where motion_id=:motionid;");
			$deferredMotion->bindParam(':dispo',$action);
			$deferredMotion->bindParam(':motionid',$motionid);
			$deferredMotion->execute();
			echo "Change the status of the motion to " . $action;
			
			$userVote=$db_con->prepare("INSERT into motionChangeLog (userid,motionid,field,oldValue,newValue) 
							VALUES (:users_id,:motions_id,:field,:oldValue,:newValue");
			$userVote->bindParam(':users_id',$userid);
			$userVote->bindParam(':motions_id',$motionid);
			$field="Motion Disposition";
			$oldValue="IN PROGRESS";
			$newValue="DEFERRED";
			$userVote->bindParam(':field',$field);
			$userVote->bindParam(':oldValue',$oldValue);
			$userVote->bindParam(':newValue',$newValue);
			$userVote->execute();
			echo "<br />";
			echo "Added your vote";
			
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
		        
		elseif (isset($_POST['Revoke']))
		{
			$userid=$_SESSION['user_id'];
                        $motionid=$_POST['motionid'];
                        $action="MOTIONED";
                        $motionSelect=$db_con->prepare("SELECT * FROM votes where vote=:action and motions_id=:motionid");
                        $motionSelect->bindParam(':action',$action);
                        $motionSelect->bindParam(':motionid',$motionid);
                        $motionSelect->execute();
                        while ($voteRow=$motionSelect->fetch(PDO::FETCH_ASSOC))
                        {
                                $vote=$voteRow['vote'];
                                $motionuser=$voteRow['users_id'];
                        }

                        echo "<br />Motion ID: " . $motionid;
                        echo "<br />User ID: " . $userid;
                        echo "<br />Motion User: " . $motionuser;
			
			if ($userid != $motionuser)
                                {
                                        echo "<br />You are not the user who motioned the vote";
                                        echo "<br />Please have the person who created the motion revoke it";
                                }//end of the if
                                else
                                {
                                        $dispo="REVOKED";
					try
                                        {
						$updatemotion=$db_con->prepare("UPDATE motions set motion_disposition=:dispo
                                                                                WHERE motion_id=:motionid");
                                                $updatemotion->bindParam(':dispo',$dispo);
                                                $updatemotion->bindParam(':motionid',$motionid);
                                                $updatemotion->execute();
                                        }//end of try
                                        catch (PDOException $e)
                                        {
                                                print "PDO Exception: " . $e->getMessage() . "<br/>";
                                                die();
                                        }//end of catch
                                        catch (Exception $e)
                                        {
                                                print "Exceptoin: " . $e->getMessage() . "<br />";
                                                die();
                                        }//end of catch

                                        try
                                        {
                                                $field="Motion Disposition";
                                                $oldvalue="MOTIONED";
                                                $dispo="REVOKED";
                                                $insertrevoke=$db_con->prepare("INSERT into motionChangeLog (userid,motionid,field,oldvalue,newValue)
                                                                        VALUES(:users_id,:motions_id,:field,:oldValue,:newValue)");
                                                $insertrevoke->bindParam(':users_id',$userid);
                                                $insertrevoke->bindParam(':motions_id',$motionid);
                                                $insertrevoke->bindParam(':field',$field);
                                                $insertrevoke->bindParam(':oldValue',$oldvalue);
                                                $insertrevoke->bindParam(':newValue',$dispo);
                                                $insertrevoke->execute();
						 echo "<br />Updated motion disposition and your vote";
                                        }//end of try
                                        catch (PDOException $e)
                                        {
                                                print "PDO Exception: " . $e->getMessage() . "<br/>";
                                                die();
                                        }//end of catch
                                        catch (Exception $e)
                                        {
                                                print "Exceptoin: " . $e->getMessage() . "<br />";
                                                die();
                                        }//end of catch
				}//end of else
                }

		elseif (isset($_POST['Amend']))
		{
			$motionid=$_POST['motionid'];
			#echo "Debug: " . $motionid;
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			$motion->execute();
			while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			{
				$motionid=$row['motion_id'];
				$motionname=$row['motion_name'];
				$dateadded=$row['dateadded'];
				$motiondesc=$row['motion_description'];
			
			
				echo "<h1>" . $motionname . "</h1><br />";
				echo "<h2>Date Added:</h2>" . $dateadded . "<br /><br />";
				echo "<h2>Motion Text</h2>";
				echo $motiondesc;
			}
			?>
			<br /><br />
			<h2>Current Votes</h2>
			<table border="1" width="100%">
			<tr>
				<th>User</th>
				<th>Date</th>
				<th>Vote</th>
			</tr>
			<?php
				$votes=$db_con->prepare(
					"SELECT u.first_name, u.last_name, v.time, v.vote from votes v inner join motions m on m.motion_id=v.motions_id INNER join users u on u.users_id=v.users_id where m.motion_id=:motionid");
				$votes->bindParam(':motionid',$motionid);
				$votes->execute();
			while ($row=$votes->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
				$votetime=$row['time'];
				$votecast=$row['vote'];
				echo "<tr>";
					echo "<td>" . $firstname . " " . $lastname . "</td>";
					echo "<td>" . $votetime . "</td>";
					echo "<td>" . $votecast . "</td>";
				echo "</tr>";
			}
			$votes->closeCursor();
			echo "</table>";
		?>

		<br /><br />
		<h2>Discussions</h2>
		<table border="1" width="100%">
		<tr>
			<th>User</th>
			<th>Date</th>
			<th>Comment</th>
		</tr>
		<?php
			$motiondiscussions=$db_con->prepare(
			"SELECT u.first_name,u.last_name,d.dateadded,d.discussion_text from users u inner join discussion d on d.user_id=u.users_id where d.motion_id=:motionid");
			$motiondiscussions->bindParam(':motionid',$motionid);
			$motiondiscussions->execute();
			while ($row=$motiondiscussions->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
				$discussiontime=$row['dateadded'];
				$discussiontext=$row['discussion_text'];
				echo "<tr>";
					echo "<td>" . $firstname . " " . $lastname . "</td>";
					echo "<td>" . $discussiontime . "</td>";
					echo "<td>" . $discussiontext . "</td>";
				echo "</tr>";
			}//end of while
			$motiondiscussions->closeCursor();
			echo "</table>";
		?>
		 <br />
        <br />
	<h2>Your Amendment</h2>
		
        <?php echo '<form id="voting" name="voting" method="POST" action="amendMotion.php">
                <input type="hidden" name="motionid" value="' . $motionid . '">
		Motion Name: <input type="text" name="motionname" readonly id="motionname" value="'. $motionname . '">
		<br />Existing Motion Text: <textarea name="existingmotiondec" id="existingmotiondec" style="width:1136px; height: 122px;">' . $motiondesc . '</textarea>
		<br />Motion Text: <textarea name="newmotiondesc" id="newmotiondesc" style="width:1136px; height: 122px;"></textarea>
                <input type="Submit" name="Submit" value="Submit">
                <input type="Reset" name="Reset" value="Reset">
        </form>';
		}
		else
		{
	?>
	<p>Please choose a motion to vote on. Only one motion can be voted
		on at a time</p>
		<table border="1" width="100%">
                <tr>
                        <th>Motion ID</th>
                        <th>Motion Name</th>
                        <th>Date Added</th>
			<th>Action</th>
                </tr>
        <?php
                $motions=$db_con->prepare(
                        "select * from motions where motion_disposition NOT IN ('PASSED','FAILED','DEFERRED','REVOKED')");
                $motions->execute(); 
                while ( $row = $motions->fetch(PDO::FETCH_ASSOC))
                { 
			$motionid=$row['motion_id'];
			$motionname=$row['motion_name'];
			$dateadded=$row['dateadded'];		
		echo '
		<form id="vote" action="vote.php" method="post">
		<tr>
                        	<td><input type="text" name="motionid" readonly value="'.$motionid. '" /> </td>
				<td>' . $row['motion_name'].'</td>
				<td>' . $row['dateadded'] . '</td>
				<td><input type="submit" value="Vote">  
				<input type="submit" value="Amend" id="Amend" name="Amend"> 
				<input type="submit" value="Revoke" id="Revoke" name="Revoke">
				<input type="submit" value="Deferred" id="Deferred" name="Deferred">
                </tr>
		</form>';
                }//end of while
			$motions->closeCursor();
		echo '
        	</table>';
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
 
<?php } ?>
</body>
</html>
