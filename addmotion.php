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
		include_once('db-config.php');

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
				$motionstatement= $db_con->prepare ("INSERT into motions (motion_name,motion_description) VALUES (:name, :motion)");
				$motionstatement -> bindParam(':name',$motionname);
				$motionstatement -> bindParam(':motion',$motiontext);
				$motionstatement->execute();
				echo "Added motion to the database .... ";
				echo "<br />";

				$searchmotion = $db_con->prepare ("SELECT * from motions where motion_name = :name AND motion_description = :description;");
				$searchmotion -> bindParam(':name',$motionname);
				$searchmotion -> bindParam(':description',$motiontext);
				$searchmotion->execute();
				$searchrows = $searchmotion->fetchAll(PDO::FETCH_ASSOC);

				echo (count($searchrows));
				echo "<br />";
				if (count($searchrows) == "1")
				{
					$votesmotionid=$searchrows[0]['motion_id'];
					$vote = "YES";
					$votestatement = $db_con->prepare ("INSERT into votes (users_id,motions_id,vote) VALUES (:users_id, :motion_id, :vote)");
					$votestatement -> bindParam(':users_id', $_SESSION['user_id']);
					$votestatement -> bindParam(':motion_id', $votesmotionid);
					$votestatement -> bindParam(':vote', $vote);
					$votestatement->execute();
					echo "Added your vote as you created the motion";
				}
				else
				{
					echo "Can not insert a record indicating that you created this motion";
				}
			}
	}

	?>
</body>
