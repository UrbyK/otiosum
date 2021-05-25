<?php

    // check if user is already logged in and redirect to home page
    if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
        header("Location: ./index.php");
        exit();
    } else {

        include_once './dbh.inc.php';
        $pdo = pdo_connect_mysql();

        // save values from html form to new variables
        $username = $_POST['username'];
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $phone = $_POST['phoneNumber'];
        $address = $_POST['addressOne'];
        $addressTwo = $_POST['addressTwo'];
        $postalCode = $_POST['postalCode'];
        $city = $_POST['city'];
        $countryId = $_POST['country'];

        // check if required fields are not empty
        if (!empty($username) && !empty($email) && !empty($password) && !empty($confirmPassword) && !empty($fname) && !empty($lname) && !empty($address) && !empty($postalCode) && !empty($city)  && !empty($countryId)){
            // check if DB for specific username
            $stmt = $pdo->prepare("SELECT * FROM account WHERE username = ?");
            $row = $stmt->execute([$username]);
            $num = $stmt->rowCount();

            // check if there is no account with the same username
            if ($num == 0) {

                //check if the username is longer then 4 characters
                if (strlen($username) >= 4) {
                    
                    $stmt = $pdo->prepare("SELECT * FROM account WHERE email = ?");
                    $row = $stmt->execute([$email]);
                    $num = $stmt->rowCount();

                    // check if email is already in use
                    if ($num == 0) {

                        // check if email is a valid format
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                            // check if password is correctly structured
                            // check password length
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

                                                    echo "Vse dobro";
                                                    header("Location: ../../register.php?error=ok");
                                                    exit();

                                                // return an error if passwords do not match
                                                } else {
                                                    echo "Passwords do not match!";
                                                    header("Location: ../../register.php?error=pass-match");
                                                    exit();
                                                }

                                            // return an error if password does not contain a special character
                                            } else {
                                                echo "Password must contain at least one special character";
                                                header("Location: ../../register.php?error=pass-special");
                                                exit();
                                            }

                                        // return an error if password does not contain a digit
                                        } else {
                                            echo "Password must contain at least one digit/number";
                                            header("Location: ../../register.php?error=pass-digit");
                                            exit();
                                        }

                                    // return an error if password does not contain an uppercase character
                                    } else {
                                        echo "Password must contain at least one uppercase char";
                                        header("Location: ../../register.php?error=pass-upper");
                                        exit();
                                    }

                                //return an error if password does not contain a lowercase character
                                } else {
                                    echo "Password must contain at least one lowercase char";
                                    header("Location: ../../register.php?error=pass-lower");
                                    exit();
                                }

                            } else {
                                echo "Password must be at least 8 char longs";
                                header("Location: ../../register.php?error=pass-length");
                                exit();
                            }

                        // if email format is invalid return an error
                        } else {
                            echo "Email format is invalid";
                            header("Location: ../../register.php?error=email-invalid");
                            exit();
                        } 

                    // return an error if email is already in use
                    } else {
                        echo "Email already in use";
                        header("Location: ../../register.php?error=email-in-use");
                        exit();
                    }

                // if username is less then 4 chars return an error
                } else {
                    echo "Username is to short";
                    header("Location: ../../register.php?error=user-length");
                    exit();
                }
            // if username already exists return an error
            } else {
                echo "Username already exists";
                header("Location: ../../register.php?error=user-exists");
                exit();
            }

        // returns an error if any of the mandatory fields are empty
        } else {
            echo "Please fill in all required fields";
            header("Location: ../../register.php?error=empty");
            exit();
        }

        // returns an error in case the is a failure in the procedure
        echo "An unknown error has occured please try again later";
        header("Location: ../../register.php?error=err");
        exit();
    }

?>