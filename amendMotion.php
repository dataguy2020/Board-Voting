<html>
	<head>
		<title>Amending Motion</title>
	</head>
	<body>
		<?php
		$motionid=$_POST['motionid'];
		$motiontext=$_POST['motiondesc'];
		include_once('include/db-config.php');
	
		if ($motiontext != "")
		{
			echo "You did not enter anything";
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
		}	
		else
		{
			echo "You did not enter anything";
		}
		?>
		<a href="dashboard.php">Main Dashboard</a>
	</body>
</html>
