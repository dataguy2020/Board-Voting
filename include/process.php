<?php
	include_once('db-config.php');
	$error  = array();
	$res    = array();
	$success = "";
	if(empty($_POST['email']))
	{
		$error[] = "Email field is required";	
	}
	
	if(empty($_POST['password']))
	{
		$error[] = "Password field is required";	
	}
	
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false && $_POST['email']!= "" ) 
	{
     
        } 
	else 
	{
		$error[] = "Enter Valid Email address";
        }

	if(count($error)>0)
	{
		$resp['msg']    = $error;
		$resp['status'] = false;	
		echo json_encode($resp);
		exit;
	}
	
	$statement = $db_con->prepare("select * from users where email = :email AND password = :password" );
        $statement->execute(array(':email' => $_POST['email'],'password'=> sha1($_POST['password'])));
	$row = $statement->fetchAll(PDO::FETCH_ASSOC);
	$temppw=$row[0]['temppw'];
	if(count($row)>0)
	{
		session_start();
		$_SESSION['temppw'] = $temppw;
		$_SESSION['user_id'] = $row[0]['users_id'];
                $_SESSION['username'] = $row[0]['username'];
                $_SESSION['fname'] = $row[0]['first_name'];
                $_SESSION['lname'] = $row[0]['last_name'];
                $_SESSION['email'] = $row[0]['email'];

		if ($_SESSION['temppw'] == "1")
		{
			$resp['temppw'] = true;
			$resp['redirect'] = "changetemppw.php";
			echo json_encode($resp);
			exit;
		}
		elseif ($_SESSION['temppw'] == "0")
		{
			$today= date("Y-m-d H:i:s");   
			$lastlogin=$db_con->prepare("update users set lastlogin= :today where users_id = :usersid");
			$lastlogin->execute(array(':today' => $today, 'usersid' => $_SESSION['user_id']));
			$lastlogin->closeCursor();
			$useraudit=$db_con->prepare("insert into audit (user_id,action) VALUES (:userid, :action)");
			$action = $_SESSION['username'] . "logged in";
			$useraudit -> bindParam(':userid', $_SESSION['user_id']);
			$useraudit -> bindParam(':action', $action);
			$useraudit->execute();
			$useraudit->closeCursor();
			$resp['redirect']    = "dashboard.php";
			$resp['status']      = true;
			echo json_encode($resp);
			exit;
		}
		else
		{
			exit;
		}
	}
	else
	{
		$error[] = "Email and password does not match";
		$resp['msg']    = $error;
		$resp['status']      = false;	
		echo json_encode($resp);
		exit;	
	} 
?>
