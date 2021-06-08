<?php
    // initialize the session
    session_start();

    // unset all sessions except cart session
    foreach ($_SESSION as $key => $value) {
        if ($key != "cart") {
            unset($_SESSION[$key]);
        }
    }

    header("Location: ./index");
    exit();

?>