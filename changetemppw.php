<?php
	session_start();
?>
<html>
<head>
	<title>Change Temporary Password</title>
</head>
<body>
<?php
	include_once('include/db-config.php');
	if (count($_POST) > 0)
	{
		$currentpassword=$_POST['currentpassword'];
		$currentpassword=sha1($currentpassword);
		$newpassword=$_POST['newpassword'];
		$newpassword=sha1($newpassword);
		$confirmpassword=$_POST['confirmpassword'];
		$confirmpassword=sha1($confirmpassword);
		
		//TODO: Try/Catch
		$getCurrentPassword=$db_con->prepare("SELECT * FROM users where users_id=:userid;");
		$getCurrentPassword->bindParam(':userid',$_SESSION['users_id']);
		$getCurrentPassword->execute();
		while ($getCurrentPasswordRow=$getCurrentPassword->fetch(PDO::FETCH_ASSOC))
		{
			if ($currentpassword != $getCurrentPasswordRow[0]['password'])
			{
				echo "Your password is not correct";
			}
			else
			{
				if ($newpassword==$confirmpassword)
				{
					//TODO: Try/catch
					$updatepassword=$db_con->prepare(
						"UPDATE users set password=:password where users_id=:userid;");
					$updatepassword->bindParam(':password',$confirmpassword);
					$updatepassword->bindParam(':userid',$_SESSION['user_id']);
					$updatepassword->execute();
					
					//TODO: Try/catch
					$temppw=$db_con->prepare(
						"UPDATE users set temppw=:temppw where users_id=:userid;");
					$temppw->bindParam(':temppw', 0);
					$temppw->bindParam(':userid',$_SESSION['user_id']);
					$temppw->execute();
					echo "We have updated your password";
					echo '<a href="index.php">Homepage</a>';
				}
				else
				{
					echo "Your new passwords are not the same";
				}//end of else
			}//end of else
		}//end of while
	}//end of if
	else
	{
		echo '<pre>';
		echo '</pre>';
		echo '<form id="changetemppw" name="changetemppw" action="changetemppw.php" method="POST">
                <fieldset>
                        <legend>Change Temporary Password</legend>
                        Current Password:<input type="password" name="currentpassword" id="currentpassword">
                        <br />New Password: <input type="password" name="newpassword" id="newpassword">
                        <br />Confirm Password: <input type="password" name="confirmpassword" id="confirmpassword">
                        <br /><input type="submit" name="submit" value="Submit"> <input type="Reset" name="Reset" value="Reset">
                </fieldset>
        </form>';
	}
?>	
</body>
</html>
