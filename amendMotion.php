<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');
	}
?>
<html>
	<head>
		<title>Amending Motion</title>
	</head>
	<body>
		<?php
			include_once('include/db-config.php');
					
			$motionid=$_POST['motionid'];
			//echo "Motion ID: " . $motionid;
			$motionname=$_POST['motionname'];
			//echo "<br />Motion Name: " . $motionname;
			$newmotiondesc=$_POST['newmotiondesc'];
			//echo "<br />New Motion Desc: " . $newmotiondesc;
			$existingmotiondec=$_POST['existingmotiondec'];
			//echo "<br />Existing Motion Desc: " . $existingmotiondec;
			$userid=$_SESSION['user_id'];
			//echo "<br />User ID: " . $_SESSION['user_id'] . "<br /><br />";
		
	
			if ($newmotiondesc != "")
			{
				if ($newmotiondesc == $existingmotiondec)
				{
					echo "The new motion text and the existing is the same";
				}
				elseif ($newmotiondesc != $existingmotiondec)
				{
					$updateMotion=$db_con->prepare(
						"UPDATE motions SET motion_description=:description where motion_id=:motionid;");
					$updateMotion->execute(array(':description'=>$newmotiondesc,':motionid' =>$motionid));
					echo "Updated the motion";
					echo "<br />";

					$dispo="AMENDED";
					$amendMotion=$db_con->prepare(
							"UPDATE motions SET motion_disposition=:dispo where motions_id=:motionid;");
					$amendMotion->execute(array(':dispo'=>$dispo,':motionid'=>$motionid));
					echo "Updated motion disposition";

					$field="Motion Description";
					$insertChange=$db_con->prepare(
						"INSERT INTO motionChangeLog
							(userid,motionid,field,oldValue,newValue)
							VALUES (:userid,:motionid,:field,:oldValue,:newValue);");
					$insertChange->bindParam(':userid',$userid);
					$insertChange->bindParam(':motionid',$motionid);
					$insertChange->bindParam(':field',$field);
					$insertChange->bindParam(':oldValue',$existingmotiondec);
					$insertChange->bindParam(':newValue',$newmotiondesc);
					$insertChange->execute();	
				
					//grabbing email addresses for Board and management
					$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
					$userSearch->execute();
					$boardEmail="";
					while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
					{
						$boardEmail .= $row['email'] .",";
					}

					amendmail($motionid,$boardEmail)
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
		<br /><a href="dashboard.php">Main Dashboard</a>
	</body>
</html>
