<?php
function temppassword($temppassword, $email)
        {
		$body = "<html><head><title>New Password Generated</title></head><body>";
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
