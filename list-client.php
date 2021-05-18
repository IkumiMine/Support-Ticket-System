<?php
session_start();
use assignment2\models\Xml;
use assignment2\functions\Functions;
require_once 'vendor/autoload.php';
date_default_timezone_set('America/Toronto');

//if session is not set and user type is not client, go back to login page
If(!isset($_SESSION['user'])){
    header('Location: login.php');
} else if ($_SESSION['user']['type'] != "client"){
    header('Location: login.php');
} else {
    $session_clientname = $_SESSION['user']['fname']." ".$_SESSION['user']['lname'];
}

//Get functions
$f = new Functions();

//load xml files
$new = new Xml();
$xml_ticket = $new->loadTickets();
$xml_user = $new->loadUser();
$client_tickets = $new->getSelectedTicketById($_SESSION['user']['id'], $xml_ticket);
$selected_user = $new->getSelectedUser($_SESSION['user']['id'], $xml_user);
//var_dump(strval($selected_user['type']));

//create new ticket
if(isset($_POST['create'])){

    $type_err = $msg_err = "";
    var_dump($_POST['inquiryType']);

    if($_POST['inquiryType'] == "0" && $_POST['create_msg'] == ""){
        $type_err = "Please fill out the form.";
    } else if($_POST['inquiryType'] == "0" && $_POST['create_msg'] !== "") {
        $type_err = "Please select inquiry type.";
    } else if ($_POST['inquiryType'] !== "0" && $_POST['create_msg'] == "") {
        $msg_err = "Please enter your message.";
    } else {
        //Get values of reply message
        $new_number = count($xml_ticket) + 1;
        $new_date = date(DateTime::RFC3339, time());
        $new_status = "New";
        $new_type = $_POST['inquiryType'];
        $new_user_id = $_SESSION['user']['id'];
        $new_msg = $_POST['create_msg'];
        $new_user_name = $_SESSION['user']['fname']." ".$_SESSION['user']['lname'];

        //Add to xml file with nice format - it's not working yet.
        //$basedata = $new_ticket;
        //$add_ticket = new SimpleXMLElement($basedata, null, false);

        $add_ticket = $xml_ticket->addChild('ticket');
        $add_ticket->addChild('number', $new_number);
        $add_ticket->addChild('dateOfIssue', $new_date);
        $add_ticket->addChild('status', $new_status);
        $add_ticket->addChild('userId', $new_user_id);
        $add_ticket->addChild('inquiryType', $new_type);
        $add_msg = $add_ticket->addChild('messages');
        $add_new_msg = $add_msg->addChild('message', $new_msg);
        $add_new_msg->addAttribute('userId', $new_user_id);
        $add_new_msg->addAttribute("userName", $new_user_name);
        $add_new_msg->addAttribute('sendDateTime', $new_date);

        $xml_ticket->saveXML("xml/tickets-support-ticket.xml");
        $client_tickets = $new->getSelectedTicketById($_SESSION['user']['id'], $xml_ticket);
        /*
        $dom = dom_import_simplexml($add_ticket)->ownerDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->save("../xml/tickets-support-ticket.xml");
    */
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
    <body>
        <?php include_once 'header.php' ?>
        <main class="ticket-list pb-5">
            <div class="container-fluid">
                <h1 class="my-5 ms-5">Welcome back, <span><?= isset($session_clientname) ? $session_clientname : ""; ?></span>!</h1>

                <!--submit new ticket-->
                <!--only user will have new ticket function-->
                <div class="card shadow newMsg-form w-75 my-5 mx-auto p-5">
                    <h2>Create a new message</h2>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="inquiryType">Inquiry Type</label>
                            <span class="error_msg"><?= isset($type_err)? $type_err: ''; ?></span>
                            <select name="inquiryType" id="inquiryType" class="form-select newMsg-form-font newMsg-select">
                                <option value="0">-Select inquiry type-</option>
                                <option value="General">General</option>
                                <option value="Products">Products</option>
                                <option value="Exchange and return">Exchange and return</option>
                                <option value="Careers">Careers</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="msg">Message</label>
                            <span class="error_msg"><?= isset($msg_err)? $msg_err: ''; ?></span>
                            <textarea class="form-control newMsg-form-font" name="create_msg"></textarea>
                        </div>
                        <input type="submit" value="Send new message" name="create" class="btn btn-secondary newMsg-form-font">
                    </form>
                </div>

                <!--ticket list-->
                <div class="card shadow w-75 mx-auto p-5">
                    <h2 class="mb-2">Ticket history</h2>
                    <div class="w-100 overflow-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Ticket#</th>
                                <th scope="col">Date of Issue</th>
                                <th scope="col">Status</th>
                                <th scope="col">Inquiry Type</th>
                                <th scope="col">View Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($client_tickets as $client_ticket) {
                                $new_date = $f->formatDate($client_ticket->dateOfIssue);
                            ?>
                                <tr>
                                    <td scope='row' class='align-middle'><?= $client_ticket->number; ?></td>
                                    <td class='align-middle'><?= $new_date; ?></td>
                                    <td class='align-middle'><?= $client_ticket->status; ?></td>
                                    <td class='align-middle'><?= $client_ticket->inquiryType; ?></td>
                                    <td class='align-middle'>
                                        <form action='detail.php' method='get'>
                                            <input type='hidden' name='number' value='<?= $client_ticket->number; ?>' />
                                            <input type='submit' class='btn btn-success detail-btn' name='details' value='Details' />
                                        </form>
                                    </td>
                                </tr>

                            <?php }?>
                            </tbody>
                        </table>
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
