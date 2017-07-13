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
<title>Report for Motion</title>
</head>
<body>
<?php
	$userid=$_SESSION['user_id'];
	$motionid=$_POST['motionid'];
	include_once ('include/db-config.php');
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			$motion->execute();
			while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			{
				$motionid=$row['motion_id'];
				$motionname=$row['motion_name'];
				$dateadded=$row['dateadded'];
				$motiondesc=$row['motion_description'];
				$disposition=$row['motion_disposition'];
				$session=$row['Session'];
			
				echo "<h1>" . $motionname . "</h1>";
				echo "<h2>Date Added:</h2>" . $dateadded . "<br />";
				echo "<h2>Motion Text</h2>";
				echo $motiondesc;
				echo "<h2>Session</h2>";
				echo $session;
				echo "<h2>Disposition:</h2>";
				echo $disposition;
				
			}//end of while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			$motion->closeCursor();
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
						"SELECT u.first_name, u.last_name, v.time, v.vote from votes v inner join motions m on m.motion_id=v.motions_id INNER join users u on u.users_id=v.users_id where m.motion_id=:motionid ORDER BY v.time ASC;");
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
					}// while ($row=$votes->fetch(PDO::FETCH_ASSOC))
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
					"SELECT u.first_name,u.last_name,d.dateadded,d.discussion_text from users u inner join discussion d on d.user_id=u.users_id where d.motion_id=:motionid ORDER BY d.dateadded DESC");
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
				echo "</table>";
			?>
			
				<br /><br />
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

 
</body>
</html>
