<?php
	function addmailing($motionid)
	{
		$motionArray = array($motionid);
		foreach ($motionArray as $motion)
		{
			//Database Connection
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
                        $motion->bindParam(':motionid',$motionid);
                        $motion->execute();
			$body="<html>
					<head>
						<title>New Motion Addded</title>
					</head>
					<body>";
			$body .= "Dear Board Member: <br /><br />";
			$body .= "A new electronic vote has been created, please review it as soon as possible. The information
				is below.";
                        while ($row=$motion->fetch(PDO::FETCH_ASSOC))
                        {
                                $motionid=$row['motion_id'];
                                $motionname=$row['motion_name'];
                                $dateadded=$row['dateadded'];
                                $motiondesc=$row['motion_description'];
			
				$body .= "Motion ID: " . $motionid;
				$body .= "<br />Motion Name: " . $motionname;
				$body .= "<br />Date Added: " . $dateadded;
				$body .= "<br />Motion Text: " . $motiondesc;  
			}//End of while
			
			$body .= "</body>
				</html>";
		}//end of foreach

		//$to="michaelbrown.tsbod@gmail.com";
		
		$boardEmail="";
		$emailSearch=$db_con->prepare("SELECT email from users where enabled=1;");
		$emailSearch->execute();
		while ($row=$emailSearch->fetch(PDO::FETCH_ASSOC))
		{
			$boardEmail .= $row['email'] .",";
		}
		$subject = "Summary for Motion " . $motionid;
		$message = $body;
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "Cc: $boardEmail";
		$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
		
		//mailing
		$to="";
		$managementSearch=$db_con->prepare("SELECT email from management;");
		$managementSearch->execute();
		while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
		{
			$to .= $row['email'] . ", ";
		}
		mail($to,$subject,$message, implode("\r\n", $headers));
	}//end of function
?>
