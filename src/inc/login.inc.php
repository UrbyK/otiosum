<?php
    session_start();
    // check if user is already loged in and redirect them to front page (index)
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("Location: ./index");
        exit();
    } else {
        // check if form was submited
        if ($_POST['Submit']) {
            include_once './dbh.inc.php';
            $pdo = pdo_connect_mysql();

            include_once './xss_cleaner.inc.php';   

            $email = htmlspecialchars($_POST['email']);
            $pass = xss_cleaner($_POST['password']);

            $stmt = $pdo->prepare("SELECT * FROM account WHERE UPPER(email) LIKE UPPER(?)");
            $stmt->execute([$email]);
            // check if an account exists
            if ($stmt->rowCount() == 0) {
                if ($stmt->rowCount() == 1) {
                    $acc = $stmt->fetch();
                    // check if account has been activated
                    if ($acc['active'] == 1) {
                        // check if passwords match
                        if (password_verify($pass, $acc['password'])) {
                            // check if account is admin/moderator
                            $_SESSION['loggedin'] = True;
                            $_SESSION['id'] = $acc['id'];
                            $_SESSION['username'] = $acc['username'];

                            if ($acc['account_type_id']!=null) {
                                // get all account types from DB
                                $accType = $pdo->prepare("SELECT * FROM account_type");
                                $accType->execute();
                                $accType = $accType->fetchAll(PDO::FETCH_ASSOC);
                                // move through each account type and compare the value with account_type_FK to check for match and set session
                                foreach ($accType as $item) {
                                    // check if account type exists and and set session for that type
                                    if($acc['account_type_id'] == $item['id']) {
                                        $type = strtolower($item['type']);
                                        $_SESSION["$type"] = true;
                                    }
                                } // end of foreach
                            } // check if account has a type

                            $userID = $acc['id'];
                            $nowDate = date("Y-m-d H:i:s");
                            $sec = $pdo->prepare("UPDATE security SET last_login = '$nowDate' WHERE account_id = $userID");
                            $sec->execute();

                            header("Location: ../../index");
                            exit();

                        // return error if passwords do not match
                        } else {
                            header("Location: ../../login?error=pass&email=$email");
                            exit();
                        }
                    // redirect if account has not yet been verified/activated
                    } else {
                        header("Location: ../../verify?status=verify-email");
                        exit();
                    }
                // return error if there is account discrepancy (no account/multiple account with the same email)
                } else {
                    header("Location: ../../login?error=err&email=$email");
                    exit();
                }
            // return error if account doesn't exist
            } else {
                header("Location: ../../login?error=user");
                exit();
            }
        // return error if form wasn't submited properly
        } else {
            header("Location: ../../login?error=err");
            exit();
        }

    }
?>