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
			
		function mailing($motionid,$boardEmail)
		{
			$body = "<html><head><title>A motion has been amended</title<body>";
			$body .= "Dear Board Member:";
			$body .="<br /><br />A motion has been amended. Please log into the system and verify you still support
					the motion.";
			$motionSelect=$db_con->prepare ("SELECT * FROM motions where motion_id=:motionid");
			$motionSelect->bindParam(':motionid',$motionid);
			$motionSelect->execute();
			while ($row=$motionSelect->fetch(PDO::FETCH_ASSOC))
			{
			 	$motionname=$row['motion_name'];
			 	$dateadded=$row['dateadded'];
			 	$motiondesc=$row['motion_description'];
			 	$disposition=$row['motion_disposition']
			}
			
			$body .= "Motion Name: " . $motionname;
			
			$motionChangeSelect=$db_con->prepare("SELECT * FROM

			$body .= "</body>
				</html>";
			$subject = "Motion Summary for Motion:" . $motionname;
			$message = $body;
			$to=$boardEmail;
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
		
		
			if(mail($to,$subject,$message, implode("\r\n", $headers)))
				print "<br />Email successfully sent";
			else
				print "<br />An error occured";	
		}//end of function
		
		$motionid=$_POST['motionid'];
		echo "Motion ID: " . $motionid;
		$motionname=$_POST['motionname'];
		echo "<br />Motion Name: " . $motionname;
		$newmotiondesc=$_POST['newmotiondesc'];
		echo "<br />New Motion Desc: " . $newmotiondesc;
		$existingmotiondec=$_POST['existingmotiondec'];
		echo "<br />Existing Motion Desc: " . $existingmotiondec;
		$userid=$_SESSION['user_id'];
		echo "<br />User ID: " . $_SESSION['user_id'] . "<br /><br />";
		
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
