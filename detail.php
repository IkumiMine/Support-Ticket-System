<?php
session_start();
use assignment2\models\Xml;
use assignment2\functions\Functions;
require_once 'vendor/autoload.php';
date_default_timezone_set('America/Toronto');

//if session is not set, go back to login page
If(!isset($_SESSION['user'])) {
    header('Location: login.php');
} else {
    $session_username = $_SESSION['user']['fname']." ".$_SESSION['user']['lname'];
}

//Get functions
$f = new Functions();

//Load a xml file
$new = new Xml();
$xml_ticket = $new->loadTickets();
$xml_user = $new->loadUser();
$ticket = $new->getSelectedTicketByNum($_GET['number'], $xml_ticket);
//$user = $new->getSelectedUser($_SESSION['user']['id'], $xml_user);

If(isset($_GET['details'])){

    $number = $ticket->number;
    $status = $ticket->status;
    $inquiry_type = $ticket->inquiryType;
    $messages = $ticket->messages;
    $new_date = $f->formatDate($ticket->dateOfIssue);
}

//update status
if(isset($_POST['update'])) {
    $ticket->status = $_POST['status'];
    $xml_ticket->saveXML("xml/tickets-support-ticket.xml");
}

//reply message
if(isset($_POST['reply'])){

    //Get values of reply message
    $msg_err = $new_msg = "";
    if($_POST['replyMsg'] == "") {
        $msg_err = "Please enter your message.";
    } else {
        $new_msg = $_POST['replyMsg'];
        $new_user_id = $_SESSION['user']['id'];
        $new_user_name = $_SESSION['user']['fname']." ".$_SESSION['user']['lname'];
        $formatted_new_send_date = date(DateTime::RFC3339, time());

        //add userId, username send date, message
        $new_message = $ticket->messages->addChild("message", $new_msg);
        $new_message->addAttribute("userId", $new_user_id);
        $new_message->addAttribute("userName", $new_user_name);
        $new_message->addAttribute("sendDateTime", $formatted_new_send_date);
        $xml_ticket->saveXML("xml/tickets-support-ticket.xml");
    }
}

//link to the list page
$link = "";
$user_type = $_SESSION['user']['type'];
if ($user_type == "client") {
    $link = "list-client.php";
} else if ($user_type == "staff") {
    $link = "list-staff.php";
} else {
    echo "error";
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
<body>
    <?php include_once 'header.php' ?>
    <main class="ticket-list pb-5">
        <div class="container-fluid">
            <h1 class="my-5 ms-5">Welcome back, <span><?= isset($session_username) ? $session_username : ""; ?></span>!</h1>

            <!--message detail-->
            <div class="card w-75 my-5 mx-auto p-5 shadow">
                <div class="card-body">
                    <h2 class="mb-5">Ticket Details</h2>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <p>Ticket Number:</p>
                            <p><?= $number; ?></p>
                        </div>
                        <div class='col-lg-6'>
                            <p>Status:</p>
                            <?php if($user_type == "staff"){ ?>
                                <form action="" method="post" class="row row-cols-lg-auto align-items-center">
                                    <div class="col-12">
                                        <select name="status" id="status" class="form-select newMsg-form-font">
                                            <option value="New" <?= (isset($status) && $status == "New") ? "selected": ""; ?>>New</option>
                                            <option value="On-going" <?= (isset($status) && $status == "On-going") ? "selected": ""; ?>>On-going</option>
                                            <option value="Resolved" <?= (isset($status) && $status == "Resolved") ? "selected": ""; ?>>Resolved</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <input type="submit" class='btn btn-success detail-btn' name='update' value='update' />
                                    </div>
                                </form>
                            <?php } else { ?>
                                <p><?= $status; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <p>Inquiry Type:</p>
                            <p><?= $inquiry_type; ?></p>
                        </div>
                        <div class='col-lg-6'>
                            <p>Date of Issue:</p>
                            <p><?= $new_date; ?></p>
                        </div>
                    </div>
                    <div class='mt-4'>
                        <p>Message:</p>
                        <ul class="list-group">
                        <?php for($i = 0; $i < count($messages->message); $i++) {
                            //format date
                            $send_date = $f->formatDate($messages->message[$i]['sendDateTime']);

                            //set bg color green if it's staff
                            $sender = $new->getSelectedUser($messages->message[$i]['userId'], $xml_user);
                            if($sender['type'] == "staff"){
                                $list_color = "list-group-item-success";
                            } else {
                                $list_color = "";
                            }
                        ?>
                            <li class="list-group-item p-3 <?= isset($list_color) ? "$list_color" : ""; ?>">
                                <?= $messages->message[$i]; ?><br>
                                <span>(<?= $messages->message[$i]['userName']." ".$send_date; ?>)</span>
                            </li>
                        <?php } ?>
                        </ul>
                    </div>

                <!--Reply form-->
                <form class="mt-5" action="" method="post">
                    <label for="replyMsg">Reply: </label>
                    <span class="error_msg"><?= isset($msg_err)? $msg_err: ''; ?></span>
                    <textarea name="replyMsg" id="replyMsg" class="form-control newMsg-form-font"></textarea>
                    <div class="mt-3">
                        <input type="submit" value="Reply" name="reply" class="btn btn-secondary newMsg-form-font">
                    </div>
                </form>

                <!--Back to list link-->
                <div class="mt-5">
                    <a href="<?= $link; ?>" class="link-success back-list">Back to list</a>
                </div>
            </div>

        </div>
    </main>
    <?php include_once 'footer.php' ?>
    <!--bootstrap js-->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--custom js-->
</body>
</html>