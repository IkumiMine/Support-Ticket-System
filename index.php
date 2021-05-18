<?php
session_start();
use assignment2\models\Xml;
require_once 'vendor/autoload.php';

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

        $selected_user = $new->getSelectedUser($id, $xml_user);

        //Create a session
        $_SESSION['user'] = $selected_user;
        $user_type = $_SESSION['user']['type'];
        //var_dump($_SESSION['user']['type']);

        //If usertype is client leads to the client list page
        //If usertype is staff leads to the staff list page
        if($user_type == "client"){
            header ('Location: list-client.php');
        } else if($user_type == "staff") {
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
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!--custom css-->
        <link rel="stylesheet" href="css/style.css">
        <!--icons-->
        <link href="css/all.css" rel="stylesheet">
    </head>
    <body class="login-body">
    <?php include_once 'components/header.php' ?>
    <main class="position-relative vh-100">
        <div class="container-fluid">
            <?php include_once 'components/login.php' ?>
        </div>
    </main>
    <?php include_once 'components/footer.php' ?>
    <!--bootstrap js-->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--custom js-->
    </body>
</html>