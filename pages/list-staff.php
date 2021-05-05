<?php
session_start();
require_once '../models/Xml.php';

//load xml files
$new = new Xml();
$xml_user = $new->loadUser();
$new_user = $new->getSelectedUser($_SESSION['userID'], $xml_user);
$xml_tickets = $new->loadTickets();

//if session is not set and user type is not staff, go back to login page
If(!isset($_SESSION['user_fname']) && !isset($_SESSION['user_lname']) && !isset($_SESSION['userID'])){
    header('Location: login.php');
} else if (strval($new_user['type']) != "staff"){
    header('Location: login.php');
} else {
    $session_staffname = $_SESSION['user_fname']." ".$_SESSION['user_lname'];
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
<body>
<?php include_once 'header.php' ?>
<main class="ticket-list pb-5">
    <div class="container-fluid">
        <h1 class="my-5 ms-5">Welcome back, <span><?= isset($session_staffname) ? $session_staffname : ""; ?></span>!</h1>

        <!--ticket list-->
        <div class="card shadow w-75 mx-auto p-5">
            <h2 class="mb-2">All Tickets list</h2>
            <div class="w-100 overflow-auto">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Ticket#</th>
                        <th scope="col">Date of Issue</th>
                        <th scope="col">Status</th>
                        <th scope="col">Inquiry Type</th>
                        <th scope="col">Client Name</th>
                        <th scope="col">View Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($xml_tickets as $ticket) {
                        $new_date = $new->formatDate($ticket->dateOfIssue)
                    ?>
                        <tr>
                            <td scope='row' class='align-middle'><?= $ticket->number; ?></td>
                            <td class='align-middle'><?= $new_date; ?></td>
                            <td class='align-middle'><?= $ticket->status; ?></td>
                            <td class='align-middle'><?= $ticket->inquiryType; ?></td>
                            <td class='align-middle'>
                                <?php
                                    $ticket_user = $new->getSelectedUser($ticket->userId, $xml_user);
                                    echo $ticket_user['fname']." ".$ticket_user['lname'];
                                ?>
                            </td>
                            <td class='align-middle'>
                                <form action='detail.php' method='get'>
                                    <input type='hidden' name='number' value='<?= $ticket->number; ?>' />
                                    <input type='submit' class='btn btn-success detail-btn' name='details' value='Details' />
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include_once 'footer.php' ?>
<!--bootstrap js-->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<!--custom js-->
</body>
</html>

