<?php
	session_start();
	if(empty($_SESSION['user_id']))
	{
		header('location: index.php');
        }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Change Email</title>
</head>
<body>
	<?php
		include_once('./include/db-config.php');
		$currentEmail=$_POST['currentpassword'];
		
		if ( $currentEmail != "" )
		{
			$newemail=$_POST['newemail'];
			$confirmemail=$_POST['confirmemail'];
			if ( $newemail == $confirmemail )
			{
            			$statement = $db_con->prepare("select * from users where users_id = :usersid AND email = :email;" );
        			$statement->execute(array(':usersid' => $_SESSION['user_id'],'email'=> $currentEmail));
                		$row = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($row)>0)
				{
					$emailUpdatestatement = $db_con->prepare("update users set email = :email where 
									users_id =:usersid;");
					$emailUpdatestatement->execute(array(':email' => $confirmemail,
						':usersid'=>$_SESSION['user_id']));
					echo "<br />Your email has been changed";
					$emailUpdatestatement->closeCursor();
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
	?>
		<br /><a href='dashboard.php'>Main Dashboard</a>
</body>
