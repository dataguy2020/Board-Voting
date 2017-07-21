<?php
session_start();
if(empty($_SESSION['user_id'])){
header('location: index.php');	
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Main Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php
	$userid=$_SESSION['user_id'];	
	include_once ('include/db-config.php');
	//Start Added April 19, 2017
	function addDiscussionMail($motionid, $boardEmail, $emailDiscussions)
		{
			global $db_con;
			$motionArray = array($motionid);
			$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
			$userSearch->execute();
			foreach ($motionArray as $motionid)
			{
				$motion=$db_con->prepare ("SELECT * from motions where motion_id = :motionid");
				$motion->bindParam(':motionid',$motionid);
				if (!$motion->execute()) var_dump($motion->errorinfo());
				$body="<html>
						<head>
							<title>New Discussion Item</title>
						</head>
						<body>";
				$body .= "Dear Board Member<br /><br />";
				$body .= "A new discussion item has been added for the below motion";
				while ($row=$motion->fetch(PDO::FETCH_ASSOC))
				{
					$motionid=$row['motion_id'];
					$motionname=$row['motion_name'];
					$dateadded=$row['dateadded'];
					$motiondesc=$row['motion_description'];
					$body .= "<br ><br />Motion ID: " . $motionid;
					$body .= "<br />Motion Name: " . $motionname;
					$body .= "<br />Motion Text: " . $motiondesc;
					$body .= "<br />";
					$body .= $emailDiscussions;
				}//End of while

				$body .= "</body>";
				$body .= "</html>";
			}
						
			$subject = "New Discussion Added for Motion" . $motionid;
			$message = $body;
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[]= 'From: Tanyard Springs Votes <noreply@tanyardspringshoa.com>';
			//mailing
			if (mail($boardEmail,$subject,$message, implode("\r\n", $headers)))
				print "<br />Email successfully sent";
			else
				print "<br />An error occured";
		}//end of function
		//End Added April 19, 2017
?>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                    class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span> </a><a class="brand" href="index.php">Tanyard Springs Board Portal</a>
      
      <!--/.nav-collapse --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container">
      <ul class="mainnav">
	 <li><a href="dashboard.php"><i class="icon-dashboard"></i><span>Dashboard</span> </a> </li>
        <li> <a href ="add.php"><i class ="icon-dashboard"></i><span>Add Motion</span></a></li>
        <li><a href="vote.php"><i class ="icon-dashboard"></i><span>Vote</span></a></li>
        <li class="active"><a href="discussions.php"><i class ="icon-dashboard"></i><span>Discussions</span></a></li>
        <li><a href="userprefs.php"><i class ="icon-dashboard"></i><span>Prefences</span></a></li>
         <li><a href="logout.php"><i class="icon-dashboard"></i><span>Logout</span></a> </li>
      </ul>
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
	
			<?php
				
				//echo $_SERVER['HTTP_REFERER'];
				$motionid=$_POST['motionid'];
				$userid=$_POST['userid'];
				$text=$_POST['discussiontext'];
				if ( strlen($text) == 0 )
				{
					echo "You have not entered any text";
				}
				else
				{
					$addDiscussion=$db_con->prepare("INSERT INTO discussion (user_id,motion_id,
									discussion_text) VALUES(:userid, :motionid, :text)");
					$addDiscussion->bindParam(':userid',$userid);
					$addDiscussion->bindParam(':motionid',$motionid);
					$addDiscussion->bindParam(':text',$text);
					$addDiscussion->execute();
					$addDiscussion->closeCursor();
					echo "Added Discussion Text";
					
					//Start Added on April 19, 2017
					$discussions=$db_con->prepare("
							SELECT u.first_name,u.last_name,d.discussion_text, d.dateadded FROM 
							discussion d inner join users u on u.users_id=d.user_id 
							WHERE motion_id=:motionid ORDER BY dateadded DESC");
					$discussions->bindParam(':motionid',$motionid);
					$discussions->execute();
					
					$emailDiscussions ="";
					$emailDiscussions .="<table border='1' width='100%'>
						<tr>
							<th>User</th>
							<th>Date Added</th>
							<th>Discussion Text</th>
						</tr>";
					while ($row=$discussions->fetch(PDO::FETCH_ASSOC))
					{
						$firstName=$row['first_name'];
						$lastName=$row['last_name'];
						$name="$firstName $lastName";
						$discussion=$row['discussion_text'];
						echo "<br />";
						$dateAdded=$row['dateadded'];
						$emailDiscussions .= "";
						$emailDiscussions .= "<tr>";
						$emailDiscussions .= "<td> ";
						$emailDiscussions .= "$firstName $lastName";
						$emailDiscussions .= "</td><td>"; 
						$emailDiscussions .= "$dateAdded";
						$emailDiscussions .= "</td><td>";
						$emailDiscussions .= "$discussion";
						$emailDiscussions .= "</td>";
					}
					
					$emailDiscussions .= "</table>";
					
					echo "This is a test below<br />";
					echo "============================<br />";
					echo $emailDiscussions;
										
					$userSearch=$db_con->prepare("SELECT * from users where enabled=1;");
					$userSearch->execute();
					$boardEmail="";
					while ($row=$userSearch->fetch(PDO::FETCH_ASSOC))
					{
						$boardEmail .= $row['email'] .",";
					}
					addDiscussionMail($motionid,$boardEmail,$emailDiscussions);
					//End Added on APril A19, 2017
				}
			?>
        <!-- /span6 -->
        
        <!-- /span6 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->

<!-- /extra -->
<div class="footer">
  <div class="footer-inner">
    <div class="container">
      <div class="row">
        <div class="span12"> &copy; 2013 <a href="http://www.egrappler.com/">Bootstrap Responsive Admin Template</a>. </div>
        <!-- /span12 --> 
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /footer-inner --> 
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery-1.7.2.min.js"></script> 

<script src="js/bootstrap.js"></script>
 

</body>
</html>
