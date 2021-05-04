<?php

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("Location: ./index.php");
        exit();
    }
    
    $email = $_POST['email'];
    $pass = $_POST['password'];

    if(isset($_POST['submit'])) {
        if(!empty($user) && !empty($pass)) {
            $query = "SELECT * FROM account WHERE email = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user]);

            if($acc['active'] != 1) {
                header("Location: ./index.php?page=login&status=varify-email");
                exit();
            } else {
                if(password_verify($pass, $acc['password'])) {
                    $_SESSION['loggedin'] = true;
                }
            }

        }
    }

?>