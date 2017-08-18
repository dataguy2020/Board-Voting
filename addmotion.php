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
		//include_once('mail.php');
		function addmailing($votesmotionid, $boardEmail)
		{
			global $db_con;
			$motionArray = array($votesmotionid);
			$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
			$userSearch->execute();
			foreach ($motionArray as $motionid)
			{
				$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
				$motion->bindParam(':motionid',$motionid);
				if (!$motion->execute()) var_dump($motion->errorinfo());


				$body="<html>
						<head>
							<title>New Motion Addded</title>
						</head>
						<body>";
				$body .= "Dear Board Member<br /><br />";
				$body .= "A new electronic vote has been created, please review it as soon as possible. The information
					is below.";
				while ($row=$motion->fetch(PDO::FETCH_ASSOC))
				{
					$motionid=$row['motion_id'];
					$motionname=$row['motion_name'];
					$dateadded=$row['dateadded'];
					$motiondesc=$row['motion_description'];
					$boardsession=$row['Session'];
					$body .= "<br ><br />Motion ID: " . $motionid;
					$body .= "<br />Motion Name: " . $motionname;
					$body .= "<br />Session: " . $boardsession;
					$body .= "<br />Date Added: " . $dateadded;
					$body .= "<br />Motion Text: " . $motiondesc;
				}//End of while
				$body .= "</body>
					</html>";
			}//end of foreach
						
			$subject = "New Motion " . $motionid;
			$message = $body;
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
			//mailing
			if (mail($boardEmail,$subject,$message, implode("\r\n", $headers)))
				print "<br />Email successfully sent";
			else
				print "<br />An error occured";
		}//end of function
		$motionname=$_POST['motionname'];
		$motiontext=$_POST['motiontext'];
		$boardsession=$_POST['boardsession'];

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
			
			if ( $boardsession == "" )
			{
				echo "Session is blank";
			}
				
		
			else
			{
				//$today=date("Y-m-d H:i:s");
				$disposition="IN PROGRESS";
				//$motionstatement= $db_con->prepare ("INSERT into motions (Session, motion_name,motion_description,
				//dateadded,motion_disposition) VALUES (:session, :name, :motion, :dateadded, :disposition)");
				$motionstatement= $db_con->prepare ("INSERT into motions (Session, motion_name,motion_description,
				motion_disposition) VALUES (:session, :name, :motion, :disposition)");
				$motionstatement -> bindParam(':session',$boardsession);
				$motionstatement -> bindParam(':name',$motionname);
				$motionstatement -> bindParam(':motion',$motiontext);
				//$motionstatement -> bindParam(':dateadded',$today);
				$motionstatement -> bindParam(':disposition',$disposition);
				$motionstatement->execute();
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
					
					$votestatement = $db_con->prepare ("INSERT into votes (users_id,motions_id,vote) VALUES (:users_id, :motion_id, :vote)");
					$votestatement -> bindParam(':users_id', $_SESSION['user_id']);
					$votestatement -> bindParam(':motion_id', $votesmotionid);
					$votestatement -> bindParam(':vote', $vote);
					$votestatement->execute();
					echo "Added your vote as you created the motion";
					echo "<br />Motion ID: " . $votesmotionid;
					
					$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
					$userSearch->execute();
					$boardEmail="";
					while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
					{
						$boardEmail .= $row['email'] .",";
					}
					addmailing($votesmotionid,$boardEmail);										
				}
				else
				{
					echo "Can not insert a record indicating that you created this motion";
				}
			}
	}
	?>
<br />
<a href="index.php">Main Dashboard</a>
</body>
