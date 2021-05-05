<?php
session_start();
//unset($_SESSION['username']);
//unset($_SESSION['userID']);
session_destroy();

header('Location: login.php');
