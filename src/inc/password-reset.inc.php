<?php
    require_once './session.php';
    // if(isLogin()){
    //     header("Location: ../../index");
    //     exit();
    // }

    if(!isset($_POST['submit'])) {
        header("Location: ../../password-reset");
        exit();
    }

    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();
    // check if reset is set to save new password or to send the reset email
    if(isset($_POST['reset']) && $_POST['reset'] == 0) {
        if(isset($_POST['email']) && !empty($_POST['email'])) {
            $email = htmlspecialchars($_POST['email']);
            
            try {
                $pdo->beginTransaction();

                // check if email is a valid format
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // check if email exist in db
                    $stmt = $pdo->prepare("SELECT id FROM account WHERE UPPER(email) = UPPER(:email)");
                    $stmt->execute(['email'=>$email]);
                    $num = $stmt->rowCount();
                    if ($num == 1) {
                        $acc = $stmt->fetch(PDO::FETCH_ASSOC);
                        $aid=$acc['id'];
                        $token = bin2hex(random_bytes(30));
                        $sql = "UPDATE account SET token = :token WHERE id = :aid";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['token'=>$token, 'aid'=>$aid]);
                        $stmtErr = $stmt->errorInfo();
                        if ($stmtErr[0] != 0) {
                            throw new PDOException("error=err-token");
                        }
                    } elseif ($num == 0) {
                        throw new PDOException("error=email");
                    } else {
                        throw new PDOException("error=err");
                    }

                } else {
                    throw new PDOException("error=email-invalid");
                }

                $pdo->commit();
                include_once './password-reset-email.inc.php';
                send_passReset_email($email, $token, $_SERVER['HTTP_HOST']);
                header("Location: ../../password-reset?status=check");
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = $e->getMessage();
                header("Location: ../../password-reset?$error");
                exit();
            }

        } else {
            header("Location: ../../password-reset?error=empty");
            exit();
        }
    }
    
    if (isset($_POST['reset']) && !empty($_POST['reset']) && $_POST['reset'] == 1) {
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = $_POST['password'];
            if (isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])) {
                $confirmPassword = $_POST['confirmPassword'];
                if (isset($_POST['email']) && !empty($_POST['email'])) {
                    $email = htmlspecialchars($_POST['email']);
                    if (isset($_POST['token']) && !empty($_POST['token'])) {
                        $oldToken = $_POST['token']; 
                        try {
                            $pdo->beginTransaction();

                            $query = "SELECT id FROM account WHERE upper(email) LIKE upper(:email) AND token LIKE :token";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute(['email'=>$email, 'token'=>$oldToken]);
                            if($stmt->rowCount() == 1) {
                                $acc = $stmt->fetch(PDO::FETCH_ASSOC);
                                $aid = $acc['id'];
                                if (strlen($password) >= 8) {
                                    // check if password has a lowercase
                                    if (preg_match('/[a-z]/', $password)) {
                                        // check if password has uppercase
                                        if (preg_match('/[A-Z]/', $password)) {
                                            // check if password contains a digit/number
                                            if (preg_match('/\d/', $password)) {
                                                // check if password contains a special character
                                                if (preg_match('/[^a-zA-Z\d]/', $password)) {
                                                    // check if password and confirmation password match
                                                    if ($password==$confirmPassword) {
                                                        // hash password
                                                        $password = password_hash($password, PASSWORD_DEFAULT);
                                                        $token = bin2hex(random_bytes(30));
                                                        $sql = "UPDATE account SET password = :password, token = :token WHERE id = :aid";
                                                        $stmt = $pdo->prepare($sql);
                                                        $stmt->execute(['password'=>$password, 'token'=>$token, 'aid'=>$aid]);
                                                        $stmtErr = $stmt->errorInfo();
                                                        if ($stmtErr[0] != 0) {
                                                            throw new PDOException("error=err");
                                                        }

                                                    } else {
                                                        throw new PDOException("error=pass-match");
                                                    }
                                                } else {
                                                    throw new PDOException("error=pass-special");
                                                }
                                            } else {
                                                throw new PDOException("error=pass-digit");
                                            }
                                        } else {
                                            throw new PDOException("error=pass-upper");
                                        }
                                    } else {
                                        throw new PDOException("error=pass-lower");
                                    }
                                } else {
                                    throw new PDOException("error=pass-length");
                                }
                            } else {
                                throw new PDOException("error=validation");
                            }
                            $pdo->commit();
                            header("Location: ../../login?status=pass-change");
                            exit();
                        } catch (PDOException $e) {
                            $pdo->rollBack();
                            $error = $e->getMessage();
                            header("Location: ../../password-reset?status=verify&$error&token=$oldToken&email=$email");
                            exit();
                        }
                    }
                }
            }
        }
        header("Location: ../../password-reset?status=verify&error=validation&token=$oldToken&email=$email");
        exit();
    } 

?>