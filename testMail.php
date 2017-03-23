<?php
	include_once('include/db-config.php');
	function addmailing($votesmotionid)
	{
		global $db_con;
		$motionArray = array($votesmotionid);
		$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
		$userSearch->execute();
		foreach ($motionArray as $motionid)
		{
			while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
			{
				$firstName = $row['first_name'] .",";
				$lastName = $row['last_name'] .",";
				$name="$firstName $lastName";
			}

			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			if (!$motion->execute()) var_dump($motion->errorinfo());

			$body="<html>
						<head>
							<title>New Motion Addded</title>
						</head>
						<body>";
			$body .= "Dear $name <br /><br />";
			$body .= "A new electronic vote has been created, please review it as soon as possible. The information
					is below.";
			while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			{
				$motionid=$row['motion_id'];
				$motionname=$row['motion_name'];
				$dateadded=$row['dateadded'];
				$motiondesc=$row['motion_description'];
				$body .= "<br ><br />Motion ID: " . $motionid;
				$body .= "<br />Motion Name: " . $motionname;
				$body .= "<br />Date Added: " . $dateadded;
				$body .= "<br />Motion Text: " . $motiondesc;
			}//End of while
			
			$body .= "</body>
					</html>";
		}//end of foreach
		$boardEmail="";
		while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
		{
			$boardEmail .= $row['email'] .",";
		}
		$subject = "New Motion " . $motionid;
		$message = $body;
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "To: $boardEmail";
		$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
		//mailing
		mail($boardEmail,$subject,$message, implode("\r\n", $headers));
	}//end of function
	
	addmailing(52);