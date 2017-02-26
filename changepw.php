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
            			$statement = $db_con->prepare("select * from users where users_id = :usersid AND password = :password;" );
        			$statement->execute(array(':usersid' => $_SESSION['user_id'],'password'=> sha1($_POST['currentpassword'])));
                		$row = $statement->fetchAll(PDO::FETCH_ASSOC);
		
				if(count($row)>0)
				{
					#$regex='/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
					#if (! preg_match ($regex,$confimpassword))
					#{
					#	echo "<br >Your password is does not meet our minimum requirements";
					#}
					#else
					#{
						$passwordUpdatestatement = $db_con->prepare("update users set password = :password where users_id = :usersid;");
						$passwordUpdatestatement->execute(array(':password' => sha1($confirmpassword),
							':usersid'=>$_SESSION['user_id']));
					#}
					echo "<br />Your password has been changed";
				}
				else
				{
					echo "<br />Your speecified password does not match your current password";
				}
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
