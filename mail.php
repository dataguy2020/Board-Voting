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

                               $body .= "<h1>" . $motionname . "</h1>
                                	<h2>Date Added:</h2>" . $dateadded . "<br />
                                	<h2>Motion Text</h2>" .
                                	$motiondesc .
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
		
		//mailing
		$to="";
		$managementSearch=$db_con->prepare("SELECT email from management where fenabled=1;");
		$managementSearch->execute();
		while ($row=$managementSearch->fetch(PDO::FETCH_ASSOC))
		{
			$to .= $row['email'] . ", ";
		}
		mail($to,$subject,$message, implode("\r\n", $headers));
	}//end of function

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
				$lastName .= $row['last_name'] .",";
				$name=$firstName . " " . $lastname;
			}
			
                        $motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
                        $motion->bindParam(':motionid',$motionid);
			if (!$motion->execute()) var_dump($motion->errorinfo());
			
		
                        $body="<html>
                                        <head>
                                                <title>New Motion Addded</title>
                                        </head>
                                        <body>";
                        $body .= "Dear " $name . "<br /><br />";
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
                //$to="michaelbrown.tsbod@gmail.com";
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
function temppassword($temppassword, $email)
        {
		$body = "<html><head><title>New Password Generated</title></head><body>"
		$body .="Your temporary password has been set. Your new password is " . $temppassword;
		$body .="</body></html>";
                $subject = "New Password";
                $message = $body;
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                $headers[] = "To: $email";
                $headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
                //mailing
                mail($email,$subject,$message, implode("\r\n", $headers));
        }//end of function



?>
