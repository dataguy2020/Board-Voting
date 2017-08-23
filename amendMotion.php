<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');
	}//end of if(empty($_SESSION['user_id']))
?>
<html>
	<head>
		<title>Amending Motion</title>
	</head>
	<body>
		<?php
			include_once('include/db-config.php');
			function amendmail($motionid,$boardEmail)
			{
				global $db_con;
				$body = "<html><head><title>A motion has been amended</title></head><body>";
				$body .= "Dear Board Member:";
				$body .="<br /><br />A motion has been amended. Please log into the system and 
					verify your vote is still accurate. Some of the information of this history 
					of this motion is below<br /><br />";


				$motionSelect=$db_con->prepare ("SELECT * FROM motions where motion_id=:motionid");
				$motionSelect->bindParam(':motionid',$motionid);
				$motionSelect->execute();
				while ($row=$motionSelect->fetch(PDO::FETCH_ASSOC))
				{
					$motionname=$row['motion_name'];
				}//end of while ($row=$motionSelect->fetch(PDO::FETCH_ASSOC))

				$body .= "<strong>Motion Name:</strong> " . $motionname;
				$body .= '<table border="1" width="100%">
					<tr>
						<th>User</th>
						<th>Date</th>
						<th>Field</th>
						<th>Old Value</th>
						<th>New Value</th>
					</tr>';

				$changeLog=$db_con->prepare("SELECT u.first_name,u.last_name,mcl.date,
						mcl.field,mcl.oldValue,mcl.newValue FROM users u inner join
						motionChangeLog mcl on mcl.userid=u.users_id WHERE
						mcl.motionid=:motionid ORDER BY date DESC;");
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
				}//end of while ($row=$changeLog->fetch(PDO::FETCH_ASSOC))
				$body .= "</table>";
				$body .= "</body>";
				$body .= "</html>";

			$subject = "Motion has been Amended:" . $motionname;
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
			$motionname=$_POST['motionname'];
			$newmotiondesc=$_POST['newmotiondesc'];
			$existingmotiondec=$_POST['existingmotiondec'];
			$userid=$_SESSION['user_id'];
		
			if ($newmotiondesc != "")
			{
				if ($newmotiondesc == $existingmotiondec)
				{
					echo "The new motion text and the existing is the same";
				}//end of if ($newmotiondesc == $existingmotiondec)
				
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
					
					//Move all votes to the changelog
					//Remove all votes
					//person who amended set to MOTIONED
								
					//grabbing email addresses for Board and management
					$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
					$userSearch->execute();
					$boardEmail="";
					
					while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
					{
						$boardEmail .= $row['email'] .",";
					}//end of while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
					amendmail($motionid,$boardEmail);
				}//end of elseif ($newmotiondesc != $existingmotiondec)
			}//end of if ($newmotiondesc != "")
		?>
		<br /><a href="dashboard.php">Main Dashboard</a>
	</body>
</html>
