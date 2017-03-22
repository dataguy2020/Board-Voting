<?php
	session_start();
	echo '<pre>';
	var_dump($_SESSION);
	echo '</pre>';
	echo '<br />';
	echo "<pre>";
	var_dump($_POST);
	echo "<pre>";

?>
<html>
<head>
	<title>Change Temporary Password</title>
</head>
<body>
<?php
	include_once('include/db-config.php');
	if ( isset ($_POST))
	{
		$currentpassword=$_POST['currentpassword'];
		$currentpassword=sha1($currentpassword);
		$newpassword=$_POST['newpassword'];
		$newpassword=sha1($newpassword);
		$confirmpassword=$_POST['confirmpassword'];
		$confirmpassword=sha1($confirmpassword);
		
		if ($currentpassword != $row[0]['password'])
		{
			echo "Your password is not correct";
		}
		else
		{
			if ($newpassword==$confirmpassword)
			{
				$updatepassword=$db_con->prepare(
					"UPDATE users set password=:password where userid=:userid;");
				$updatepassword->bindParam(':password',$confirmpassword);
				$updatepassword->bindParam(':userid',$_SESSION['user_id']);
				$updatepassword->execute();
				$updatepassword->closeCursor();
				$temppw=$db_con->prepare(
					"UPDATE users set temppw=:temppw where userid=:userid;");
				$temppw->bindParam(':temppw', 1);
				$temppw->bindParam(':userid',$_SESSION['user_id']);
				$temppw->execute();
				$temppw->closeCursor();
			}
			else
			{
				echo "Your new passwords are not the same";
			}//end of else
		}
	
	}
	if (!isset($_POST))
	{
		var_dump($_SESSION);
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
