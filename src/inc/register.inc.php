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
        $countryID = $_POST['country'];
        $cityID = null;

        // check if required fields are not empty
        if (!empty($username) && !empty($email) && !empty($password) && !empty($confirmPassword) && !empty($fname) && !empty($lname) && !empty($address) && !empty($postalCode) && !empty($city)  && !empty($countryID)){
            // check if DB for specific username
            $stmt = $pdo->prepare("SELECT * FROM account WHERE username = ?");
            $stmt->execute([$username]);
            $num = $stmt->rowCount();

            // check if there is no account with the same username
            if ($num == 0) {

                //check if the username is longer then 4 characters
                if (strlen($username) >= 4) {
                    
                    $stmt = $pdo->prepare("SELECT * FROM account WHERE UPPER(email) = UPPER(?)");
                    $stmt->execute([$email]);
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

                                                    // check if country has the specified city
                                                    $stmt = $pdo->prepare("SELECT id FROM city WHERE upper(city) LIKE upper(?) AND upper(postal_code) = upper(?) AND country_id = ?");
                                                    $stmt->execute([$city, $postalCode, $countryID]);
                                                    
                                                    // if city with that postal code doesn't exist for the specified country, input the city/postal code for that county
                                                    if ($stmt->rowCount()<1) {
                                                        $pdo->prepare("INSERT INTO city(country_id, postal_code, city) VALUES (?, ?, ?)")->execute([$countryID, $postalCode, $city]);
                                                        $cityID = $pdo->lastInsertId();
                                                    } elseif ($stmt->rowCount() == 1) {
                                                        $item = $stmt->fetch();
                                                        $cityID=$item['id'];
                                                    } else {
                                                        header("Location: ../../register.php?error=err&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                                        exit();
                                                    }

                                                    $query = "INSERT INTO account(username, email, password, first_name, last_name, address, address2, phone_number, token, city_id) VALUES(?,?,?,?,?,?,?,?,?,?)";
                                                    $stmt = $pdo->prepare($query);
                                                    $stmt->execute([$username, $email, $password, $fname, $lname, $address, $addressTwo, $phone, $token, $cityID]);
                                                    

                                                    echo "So far so good!";
                                                    header("refresh:10; url=../../register.php?error=empty");
                                                    exit();

                                                // return an error if passwords do not match
                                                } else {
                                                    echo "Passwords do not match!";
                                                    header("Location: ../../register.php?error=pass-match&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                                    exit();
                                                }

                                            // return an error if password does not contain a special character
                                            } else {
                                                echo "Password must contain at least one special character";
                                                header("Location: ../../register.php?error=pass-special&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                                exit();
                                            }

                                        // return an error if password does not contain a digit
                                        } else {
                                            echo "Password must contain at least one digit/number";
                                            header("Location: ../../register.php?error=pass-digit&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                            exit();
                                        }

                                    // return an error if password does not contain an uppercase character
                                    } else {
                                        echo "Password must contain at least one uppercase char";
                                        header("Location: ../../register.php?error=pass-upper&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                        exit();
                                    }

                                //return an error if password does not contain a lowercase character
                                } else {
                                    echo "Password must contain at least one lowercase char";
                                    header("Location: ../../register.php?error=pass-lower&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                    exit();
                                }

                            } else {
                                echo "Password must be at least 8 char longs";
                                header("Location: ../../register.php?error=pass-length&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                                exit();
                            }

                        // if email format is invalid return an error
                        } else {
                            echo "Email format is invalid";
                            header("Location: ../../register.php?error=email-invalid&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                            exit();
                        } 

                    // return an error if email is already in use
                    } else {
                        echo "Email already in use";
                        header("Location: ../../register.php?error=email-exists&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                        exit();
                    }

                // if username is less then 4 chars return an error
                } else {
                    echo "Username is to short";
                    header("Location: ../../register.php?error=user-length&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                    exit();
                }
            // if username already exists return an error
            } else {
                echo "Username already exists";
                header("Location: ../../register.php?error=user-exists&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
                exit();
            }

        // returns an error if any of the mandatory fields are empty
        } else {
            echo "Please fill in all required fields";
            header("Location: ../../register.php?error=empty&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
            exit();
        }

        // returns an error in case the is a failure in the procedure
        echo "An unknown error has occured please try again later";
        header("Location: ../../register.php?error=err&user=$username&email=$email&fname=$fname&lname=$lname&phone=$phone&addr=$address&addrsec=$addressTwo&postalCode=$postalCode&city=$city&country=$countryID");
        exit();
    }

?>