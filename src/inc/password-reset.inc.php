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
                    print_r($acc);
                    $token = bin2hex(random_bytes(30));
                    echo"<br> $token";
                    $sql = "UPDATE account SET token = :token WHERE id = :aid";
                    $smtm = $pdo->prepare($sql);
                    $stmt->execute(['token'=>$token, 'aid'=>$acc['id']]);
                    $stmtErr = $stmt->errorInfo();
                    if ($stmtErr[0] != 0) {
                        throw new PDException("error=err-token");
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
            include_once '.password-reset-email.inc.php';
            send_passReset_email($email, $token, $_SERVER['HTTP_HOST']);
            header("Location: ../../password-reset?check");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
            header("Location: ../../password-reset?$error");
            
        }

    } else {
        header("Location: ../../password-reset?error=empty");
        exit();
    }

?>