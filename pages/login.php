<?php
session_start();
require_once '../models/Xml.php';

//load xml user file
$new = new Xml();
$xml_user = $new->loadUser();
$new_user = $new->getUsers($xml_user);
//var_dump($users['id']);

//When the login button is clicked
if(isset($_POST['login'])){

    $id = $_POST['userid'];
    $pass = $_POST['password'];
    $id_pattern = "/^\d{4}$/";//4 digits
    $user_passhash = md5($pass);

    //userID and password validation
    if($id == ""){
        $user_err = "Please enter userID.";
    } else if (!preg_match($id_pattern, $id)) {
        $err_msg = "userID or password is invalid.";
    } else if ($pass == "") {
        $pass_err = "Please enter password.";
    } else if (!in_array($user_passhash, $new_user['pass'])) {
        $err_msg = "userID or password is invalid.";
    } else if (!in_array($id, $new_user['id'])) {
        $err_msg = "userID or password is invalid.";
    } else {

        //$xml_user = simplexml_load_file("xml/users-support-ticket.xml");
        $selected_user = $new->getSelectedUser($id, $xml_user);
        //var_dump($selected_user['type']);

        //Create a session
        $_SESSION['user_fname'] = $selected_user['fname'];
        $_SESSION['user_lname'] = $selected_user['lname'];
        $_SESSION['userID'] = $selected_user['id'];
        print_r($_SESSION);

        if($selected_user['type'] == "client"){
            header ('Location: list-client.php');
        } else if($selected_user['type'] == "staff") {
            header ('Location: list-staff.php');
        } else {
            echo "error";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Support Ticket System</title>
      <!--bootstrap 5.0.0-->
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <!--custom css-->
      <link rel="stylesheet" href="../css/style.css">
      <!--icons-->
      <link href="../css/all.css" rel="stylesheet">
  </head>
  <body class="login-body">
      <?php include_once 'header.php' ?>
      <main class="position-relative vh-100">
          <div class="container-fluid">
              <!--login-->
              <div class="card login-form-box position-absolute top-50 start-50 p-5 shadow">
                  <h1 class="card-title text-center">Login</h1>

                  <form action="login.php" method="post" class="card-body">
                      <div class="row g-3 align-items-center">
                          <div class="col-lg-3">
                              <label for="userid" class="col-form-label">UserID</label>
                          </div>
                          <div class="col-lg-9">
                              <input type="text" name="userid" id="userid" class="form-control newMsg-form-font">
                              <span class="error_msg"><?= isset($user_err)? $user_err: ''; ?></span>
                          </div>
                      </div>
                      <div class="row g-3 align-items-center">
                          <div class="col-lg-3">
                              <label for="password" class="col-form-label">Password</label>
                          </div>
                          <div class="col-lg-9">
                              <input type="password" name="password" id="password" class="form-control newMsg-form-font">
                              <span class="error_msg"><?= isset($pass_err)? $pass_err: ''; ?></span>
                          </div>
                      </div>
                      <span class="error_msg"><?= isset($err_msg)? $err_msg: ''; ?></span>
                      <div class="text-center mt-5 w-100">
                          <input type="submit" name="login" value="login" class="btn btn-secondary login-btn px-5 w-100">
                      </div>
                  </form>
              </div>
          </div>
      </main>
      <?php include_once 'footer.php' ?>
      <!--bootstrap js-->
      <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
      <!--custom js-->
  </body>
</html>
