<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');
	}
?>

<html>
<head>
	<title>Adding Motion</title>
</head>
<body>

	<?php
		include_once('include/db-config.php');
		$motionname=$_POST['motionname'];
		$motiontext=$_POST['motiontext'];

		if ( $motionname == "" )
		{
			echo "Motion Name is blank";
		}
	
		else
		{
			if ( $motiontext == "" )
			{
				echo "Motion Text is blank";
			}
		
			else
			{
				$today=date("Y-m-d H:i:s");   
				$motionstatement= $db_con->prepare ("INSERT into motions (motion_name,motion_description,dateadded) VALUES (:name, :motion, :dateadded)");
				$motionstatement -> bindParam(':name',$motionname);
				$motionstatement -> bindParam(':motion',$motiontext);
				$motionstatement -> bindParam(':dateadded',$today);
				$motionstatement->execute();
				$motionstatement->closeCursor();
				echo "Added motion to the database .... ";
				echo "<br />";				
				$searchmotion = $db_con->prepare ("SELECT * from motions where motion_name = :name AND motion_description = :description;");
				$searchmotion -> bindParam(':name',$motionname);
				$searchmotion -> bindParam(':description',$motiontext);
				$searchmotion->execute();
				$searchrows = $searchmotion->fetchAll(PDO::FETCH_ASSOC);
				if (count($searchrows) == "1")
				{
					$votesmotionid=$searchrows[0]['motion_id'];
					$vote = "MOTIONED";
					$auditMotionAdd = $db_con->prepare 
						("INSERT into audit (user_id, action) VALUE (:users_id, :action)");
					$auditMotionAdd -> bindParam(':users_id',$_SESSION['user_id']);
					$action="Added motion id " . $votesmotionid;
					$auditMotionAdd -> bindParam(':action',$vote);
					$auditMotionAdd -> execute();
					$auditMotionAdd ->closeCursor();
					
					$votestatement = $db_con->prepare ("INSERT into votes (users_id,motions_id,vote) VALUES (:users_id, :motion_id, :vote)");
					$votestatement -> bindParam(':users_id', $_SESSION['user_id']);
					$votestatement -> bindParam(':motion_id', $votesmotionid);
					$votestatement -> bindParam(':vote', $vote);
					$votestatement->execute();
					echo "Added your vote as you created the motion";
					echo "<br />Motion ID: " . $votesmotionid;
					include_once('addmail.php');
					addmailing($votesmotionid);
					$votestatement ->closeCursor();
					
				}
				else
				{
					$searchmotion->closeCursor();
					echo "Can not insert a record indicating that you created this motion";
				}
			}
	}
	?>
<br />
<a href="index.php">Main Dashboard</a>
</body>
