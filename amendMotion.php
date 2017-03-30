<html>
	<head>
		<title>Amending Motion</title>
	</head>
	<body>
		<?php
		$motionid=$_POST['motionid'];
		$motionname=$_POST['motionname'];
		$newmotiondesc=$_POST['newmotiondesc'];
		$existingmotiondec=$_POST['existingmotiondec'];
		$userid=$_SESSION['userid'];
		
		include_once('include/db-config.php');
	
		if ($newmotiondesc != "")
		{
			if ($newmotiondesc == $existingmotiondec)
			{
				echo "The new motion text and the existing is the same";
			}
			elseif ($newmotiondesc != $existingmotiondec)
			{
				$updateMotion=$db_con->prepare(
					"UPDATE motions SET motion_description=:description where motions_id=:motionid;");
				$updateMotion->execute(array(':updatedvote'=>$motiontext,':motionid' =>$motionid));
				echo "Updated the motion";
				echo "<br />";
				
				$dispo="AMENDED";
				$amendMotion=$db_con->prepare(
						"UPDATE motions SET motion_disposition=:dispo where motions_id=:motionid;");
				$amendMotion->execute(array(':dispo'=>$dispo,':motionid'=>$motionid));
				echo "Updated motion disposition";
				
				$getUserDeteails=$db_con->prepare(
					"SELECT * FROM users where user
				
			}
			else
			{
				echo "You did not enter anything";
			}
		}	
		else
		{
			echo "You did not enter anything";
		}
		?>
		<a href="dashboard.php">Main Dashboard</a>
	</body>
</html>
