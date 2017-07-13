<?php
	include_once ('include/db-config.php');
	function mailing($motionid)
	{
		global $db_con;
		$motionArray = array($motionid);
		foreach ($motionArray as $motion)
		{
			$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
			$motion->bindParam(':motionid',$motionid);
			$motion->execute();
			$body="<html>
					<head>
						<title>Status of Motion</title>
					</head>
					<body>";
			 while ($row=$motion->fetch(PDO::FETCH_ASSOC))
			 {
			 	$motionid=$row['motion_id'];
			 	$motionname=$row['motion_name'];
			 	$dateadded=$row['dateadded'];
			 	$motiondesc=$row['motion_description'];
			 	$disposition=$row['motion_disposition'];
				$session=$row['Session'];

			 	 $body .= "<h1>" . $motionname . "</h1>
                                	   <h2>Date Added:</h2>" . $dateadded . "<br />
                                	   <h2>Motion Text</h2>" .
                                	   $motiondesc .
					 "<h2>Session</h2> .
					 $session .
                                	   "<h2>Disposition:</h2>" .
                                	   $disposition;
			}//End of while

			$body .= "<br /><br />
					<h2>Current Votes</h2>
					<table border=\"1\" width=\"100%\">
					<tr>
						<th>User</th>
						<th>Date</th>
						<th>Vote</th>
					</tr>";

			$votes=$db_con->prepare(
                        	"SELECT u.first_name, u.last_name, v.time, v.vote from votes v 
				inner join motions m on m.motion_id=v.motions_id INNER join users u on 
				u.users_id=v.users_id where m.motion_id=:motionid ORDER BY v.time ASC;");
                     	
			$votes->bindParam(':motionid',$motionid);
                        $votes->execute();
                        while ($row=$votes->fetch(PDO::FETCH_ASSOC))
                        {
                        	$firstname=$row['first_name'];
                                $lastname=$row['last_name'];
                                $votetime=$row['time'];
                                $votecast=$row['vote'];
                                $body .= "<tr>
					<td>" . $firstname . " " . $lastname . "</td>
                                        <td>" . $votetime . "</td>
                                        <td>" . $votecast . "</td>
                                        </tr>";
                        }// while ($row=$votes->fetch(PDO::FETCH_ASSOC))
    			$body .= "</table>";
			$body .= "<br /><br />
					<h2>Discussions</h2>
					<table border=\"1\" width=\"1\">
					<tr>
						<th>User</th>
						<th>Date</th>
						<th>Comment</th>
					</tr>";

			$motiondiscussions=$db_con->prepare(
				"SELECT u.first_name,u.last_name,d.dateadded,d.discussion_text
				 from users u inner join discussion d on d.user_id=u.users_id where d.motion_id=:motionid");
			$motiondiscussions->bindParam(':motionid',$motionid);
                       	$motiondiscussions->execute();
			while ($row=$motiondiscussions->fetch(PDO::FETCH_ASSOC))
			{
				$firstname=$row['first_name'];
				$lastname=$row['last_name'];
                                $discussiontime=$row['dateadded'];
                                $discussiontext=$row['discussion_text'];
				$body .= "<tr>
							<td>" . $firstname . " " . $lastname . "</td>
							<td>" . $discussiontime . "</td>
							<td>" . $discussiontext . "</td>
						</tr>";
			}//end of while
			$body .= "</table>";

			$body .= "</body>
				</html>";
		}//end of foreach

		$boardEmail="";
		$emailSearch=$db_con->prepare("SELECT email from users where enabled=1;");
		$emailSearch->execute();
		while ($row=$emailSearch->fetch(PDO::FETCH_ASSOC))
		{
			$boardEmail .= $row['email'] .",";
		}
		$subject = "New Motion Added; Motion ID# " . $motionid;
		$message = $body;
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		$headers[] = "Cc: $boardEmail";
		$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
		
		
		$to="";
		$managementSearch=$db_con->prepare("SELECT email from management where fenabled=1;");
		$managementSearch->execute();
		while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
		{
			$to .= $row['email'] . ", ";
		}
		if(mail($to,$subject,$message, implode("\r\n", $headers)))
		{
			print "Email successfully sent";
			$motionSent=$db_con->prepare("UPDATE motions set sent=1 where motion_id=:motionid;");
			$motionSent->bindParam(':motionid',$motionid);
			$motionSent->execute();
		}
		else
		{
			print "An error occured";	
			$motionSent=$db_con->prepare("UPDATE motions set sent=2 where motion_id=:motionid;");
			$motionSent->bindParam(':motionid',$motionid);
			$motionSent->execute();
		}
	}//end of function

	function temppassword($temppassword, $email)
		{
			$body = "<html><head><title>New Password Generated</title></head><body>";
			$body .="Your temporary password has been set. Your new password is $temppassword";
			$body .="</body></html>";
			$subject = "New Password";
			$message = $body;
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = "To: $email";
			$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
			if(mail($email,$subject,$message, implode("\r\n", $headers)))
			{
				print "Email successfully sent";
			}
			else
			{
				print "An error occured";
			}
		}//end of function
?>
