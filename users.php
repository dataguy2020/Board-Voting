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
      <link href="css/pages/dashboard.css" rel="stylesheet">
      <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
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
      <script>
         $(document).ready(function() {
         $('#editUsers').DataTable(
             
              {     
           "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
             "iDisplayLength": 30
            } 
             );
         } );
         function checkAll(bx) {
         var cbs = document.getElementsByTagName('input');
         for(var i=0; i < cbs.length; i++) {
         if(cbs[i].type == 'checkbox') {
           cbs[i].checked = bx.checked;
         }
         }
         }
      </script>
       <script>
       $(document).ready(function() {
    $('#currentUsers').DataTable( {
        "dom": '<"toolbar">frtip'
    } );
 
    $("div.toolbar").html('<strong>Current Users</strong>');
} );
       </script>
    <style>
        .toolbar {
    float: left;
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
		<div class="toolbar"><strong>Current Users</strong></div>
                  <table id="currentUsers" name="currentUsers" border="1"  id="example" class="table table-striped table-bordered" style="width:100%">
                     <thead>
                        <tr>
                           <th>First Name</th>
                           <th>Last Name</th>
                           <th>User Name</th>
               <th>E-mail Address</th>
               <th>Enabled</th>
               <th>Edit</th>
                        </tr>
                     </thead>
                     <tbody>
                         <tr>
                             <td colspan="5" style="text-align: center; vertical-align: middle;">Do you want to add a new user?</td>
                             <td>
				     <form id="addUser" id="addUser" method="post" action="modifyUser.php">
					     <input type="Submit" name="Submit" value="Add User">
				     </form>
				 </td>
                         </tr>
                        <?php
include_once('include/db-config.php');
$users = $db_con->prepare("select * from users;");
$users->execute();
while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
?>
                       <form id="editUsers" name="editUsers" action="modifyUser.php" method="POST">
                           <?php
    $usersid = $row['users_id'];
?>
                          <tr>
                              <td><?php
    echo $row['first_name'];
?> </td>
                              <td><?php
    echo $row['last_name'];
?> </td>
                              <td><?php
    echo $row['username'];
?> </td>
                  <td><?php
    echo $row['email'];
?> </td>
<?php
    $enabled = $row['enabled'];
    if ($enabled == 1) {
        $enabledText = "Yes";
    } else {
        $enabledText = "No";
    }
?>
               <td><?php
    echo $enabledText;
?></td>
                              <?php
    echo '<input type="hidden" id="usersid" name="usersid" value="' . $usersid . '">';
?>
                             <td><input type="Submit" name="Submit" value="Edit"></td>
                           </tr>
                        </form>
                        <?php
} //end of while 
?>
                    </tbody>
                     <tfoot>
                        <tr>
                           <th>First Name</th>
                           <th>Second Name</th>
                           <th>User Name</th>
               <th>E-mail</th>
               <th>Enabled</th>
               <th>Edit user</th>
                        </tr>
                     </tfoot>
                  </table>
                  <br />
                  <br />    
                  <br />
                  <br />
                  <br />
                  <?php
$users->closeCursor();
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
