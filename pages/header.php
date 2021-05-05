<?php
require_once '../models/Xml.php';

If(isset($_SESSION['user_fname']) && isset($_SESSION['user_lname']) && isset($_SESSION['userID'])){
    $new = new Xml();
    $xml_user = $new->loadUser();
    $new_user = $new->getSelectedUser($_SESSION['userID'], $xml_user);

    if($new_user['type'] == "client"){
        $list = "list-client.php";
    } else if($new_user['type'] == "staff") {
        $list = "list-staff.php";
    } else {
        echo "error";
    }
}

?>
<header class="header">
    <div class="container-fluid">
        <!--NAVIGATION-->
        <nav class="navbar navbar-expand-lg px-5">
            <a class="navbar-brand nav-title" href="login.php"><i class="fas fa-ticket-alt fa-fw"></i>TSS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars nav-toggle-icon"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li>
                        <a class="nav-link me-3" href="login.php">Login</a>
                    </li>
                    <li>
                        <a class="nav-link me-3" href="<?= $list; ?>">List</a>
                    </li>
                    <li>
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>