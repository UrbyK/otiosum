<?php

    // check if user is already loged in and redirect them to front page (index)
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
                header("Location: ./verify?status=varify-email");
                exit();
            } else {
                if(password_verify($pass, $acc['password'])) {
                    $_SESSION['loggedin'] = true;
                }
            }

        }
    }

?>