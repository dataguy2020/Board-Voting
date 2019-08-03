<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Admin Users</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
      <link href="css/font-awesome.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
      <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
      <style>
         table{
         width:100%;
             border-collapse: separate;
         }
         #example_filter{
         float:right;
         }
         #example_paginate{
         float:right;
         }
         label {
         display: inline-flex;
         margin-bottom: .5rem;
         margin-top: .5rem;
         }
      </style>
    
  
   </head>
   <body>
       <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
          <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
      <?php
$userid = $_SESSION['user_id'];
?>
    <div class="navbar navbar-fixed-top">
         <div class="navbar-inner">
            <div class="container">
               <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
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
                  <li><a href="dashboard.php"><em class="icon-dashboard"></em><span>Dashboard</span> </a> </li>
                  <li> <a href ="add.php"><em class ="icon-dashboard"></em><span>Add Motion</span></a></li>
                  <li><a href="vote.php"><em class ="icon-dashboard"></em><span>Vote</span></a></li>
                  <li><a href="discussions.php"><em class ="icon-dashboard"></em><span>Discussions</span></a></li>
          <li><a href="userprefs.php"><em class ="icon-dashboard"></em><span>Prefences</span></a></li>
        <li class="active"><a href="users.php"><em class="icon-dashboard"></em><span>Users</span></a></li>
                  <li><a href="logout.php"><em class="icon-dashboard"></em><span>Logout</span></a> </li>
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
            <!--<div class="alert alert-danger" role="alert">
               <strong>ALERT: </strong>This message is as of October 17, 2018. Please be advised this site will be moved to a new server in the next week
                  </div>-->
            <div class="container">
       <div class="row">
        <?php
if (isset($_POST['usersid'])) {
    $usersid = $_POST['usersid'];
    echo "The userid is $usersid";
}
if ((!isset($_POST['usersid'])) && (isset ($_POST['addUser'])) && $_POST['addUser'] == "addUser")
{
?>

  <div class="card-body px-lg-5 pt-0">

        <form class="md-form" style="color: #757575;">

                    <input type="text" id="materialRegisterFormFirstName" class="form-control">
                    <label for="materialRegisterFormFirstName">First name</label>

                    <input type="email" id="materialRegisterFormLastName" class="form-control">
                    <label for="materialRegisterFormLastName">Last name</label>

                    <input type="email" id="materialRegisterFormEmail" class="form-control">
                    <label for="materialRegisterFormEmail">E-mail</label>

                    <input type="password" id="materialRegisterFormPassword" class="form-control" aria-describedby="materialRegisterFormPasswordHelpBlock">
                    <label for="materialRegisterFormPassword">Password</label>

                    <small id="materialRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                        At least 8 characters and 1 digit
                    </small>

                    <input type="password" id="materialRegisterFormPhone" class="form-control" aria-describedby="materialRegisterFormPhoneHelpBlock">
                    <label for="materialRegisterFormPhone">Phone number</label>

                    <small id="materialRegisterFormPhoneHelpBlock" class="form-text text-muted mb-4">
                        Optional - for two step authentication
                    </small>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="materialRegisterFormNewsletter">
                        <label class="form-check-label" for="materialRegisterFormNewsletter">Subscribe to our newsletter</label>
                    </div>


            <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Sign in</button>

            <div class="text-center">
                <p>or sign up with:</p>

                <a type="button" class="btn-floating btn-fb btn-sm">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a type="button" class="btn-floating btn-tw btn-sm">
                    <i class="fab fa-twitter"></i>
                </a>
                <a type="button" class="btn-floating btn-li btn-sm">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a type="button" class="btn-floating btn-git btn-sm">
                    <i class="fab fa-github"></i>
                </a>

                <hr>

                <p>By clicking
                    <em>Sign up</em> you agree to our
                    <a href="" target="_blank">terms of service</a> and
                    <a href="" target="_blank">terms of service</a>. </p>
            </div>

        </form>
    </div>
</div>


<?php
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
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="js/bootstrap.js"></script>
   </body>
</html>
