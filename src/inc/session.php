<?php
    session_start();
        
    function isLogin() {
        if (isset($_SESSION['loggedin']) && !empty($_SESSION['loggedin']) 
                && isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            return true;
        }
        return false;
    }

    function isAdmin() {
        if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
            return true;
        }
        return false;
    }

    function isMod() {
        if (isset($_SESSION['moderator']) && !empty($_SESSION['moderator'])) {
            return true;
        }
        return false;
    }


?>