<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');
        }
?>
<html>
<head>
	<title>Change Password</title>
</head>
<body>
	<?php
		include_once('./include/db-config.php');
		$currentpassword=$_POST['currentpassword'];
		
		if ( $currentpassword != "" )
		{
			$newpassword=$_POST['newpassword'];
			$confirmpassword=$_POST['confirmpassword'];
			if ( sha1($newpassword) == sha1($confirmpassword) )
			{
            			$statement = $db_con->prepare("select * from users where users_id = :usersid 
								AND password = :password;" );
        			$statement->execute(array(':usersid' => $_SESSION['user_id'],
							  'password'=> sha1($currentpassword));
                		$row = $statement->fetchAll(PDO::FETCH_ASSOC);
				echo "<br />Current Password: " . $currentpassword;
				echo "<br />Current Password Hash: " . sha1($currentpassword);
				echo "<br />New Password: " . $newpassword;
				echo "<br />New Password: " . sha1($newpassword);	
				echo "<br />Count: " . count($row);		    
				if(count($row)>0)
				{
					$passwordUpdatestatement = $db_con->prepare("update users set password = :password where 
									users_id =:usersid;");
					$passwordUpdatestatement->execute(array(':password' => sha1($confirmpassword),
						':usersid'=>$_SESSION['user_id']));
					echo "<br />Your password has been changed";
					$passwordUpdatestatement->closeCursor();
					$temppwUpdate=$db_con->prepare("update users set temppw= 0 where users_id=:usersid");
					$temppwUpdate=$db_con->execute(array(':usersid'=>$_SESSION['user_id']));
					echo "<br />Change status back to non-temp password";
					$temppwUpdate->closeCursor();
				}
				else
				{	
					echo "<br />Your speecified password does not match your current password";
				}
				$statement->closeCursor();
			}
			else
			{
				echo "<br />Please enter the same new password";
			}
		}
		else
		{
			echo "<br />You have not entered your current password";
		}

		echo "<br /><a href='dashboard.php'>Main Dashboard</a>";
	?>
</body>
