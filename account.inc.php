<?php
    include './functions.php';
    require_once './src/inc/session.php';

    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "fetchData") {
        if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $aid = $_SESSION['id'];
            $output = "";

            $user = user($aid);
            $city = city($user['city_id']);
            $countries = countries();

            $output = accountThemplate($user, $city, $countries);

            echo json_encode(['output'=>$output]);
            exit();
        }
    }

    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "update") {
        if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $aid = $_SESSION['id'];
            $countryID = $_POST['country'];
            $error = "";
            // check to see if data has been set and required fields are not empty
            if(isset($_POST['fname']) && !empty($_POST['fname']) && isset($_POST['lname']) && !empty($_POST['lname'])) {
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $phoneNumber = $_POST['phoneNumber'];
                if(isset($_POST['address']) && !empty($_POST['address'])) {
                    $address = $_POST['address'];
                    $addressTwo = $_POST['addressTwo'];
                    if(isset($_POST['city']) && !empty($_POST['city']) && isset($_POST['postalCode']) && !empty($_POST['postalCode']) && isset($_POST['country']) && !empty($_POST['country'])) {
                        $pdo = pdo_connect_mysql();
                        $city = $_POST['city'];
                        $postalCode = $_POST['postalCode'];
                        $countryID = $_POST['country'];
                        $query = "SELECT * FROM city WHERE UPPER(city) = UPPER(:city) AND UPPER(postal_code) = UPPER(:postalCode) AND country_id = :countryID";
                        // $rows = $pdo->query($query)->fetch();
                        $stmt = $pdo->prepare($query);
                        $stmt->execute(['city'=>$city, ":postalCode"=>$postalCode, "countryID"=>$countryID]);
                        // if city with that postal code doesn't exist for the specified country, input the city/postal code for that county
                        if ($stmt->rowCount()<1) {
                            $pdo->prepare("INSERT INTO city(country_id, postal_code, city) VALUES (?, ?, ?)")->execute([$countryID, $postalCode, $city]);
                            $cityID = $pdo->lastInsertId();
                        } elseif ($stmt->rowCount() == 1) {
                            $item = $stmt->fetch();
                            $cityID=$item['id'];
                        }

                        // update the account data
                        // $query = "UPDATE account SET first_name = :fname, last_name = :lname, phone_number = :phoneNumber, address = :address, address2 = addressTwo, city_id = :cityID WHERE id = :aid";
                        $query = "UPDATE account SET 
                            first_name = :fname,
                            last_name = :lname,
                            address = :address,
                            address2 = :addressTwo,
                            phone_number = :phoneNumber,
                            city_id = :cityID
                            WHERE id = :aid";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute(["fname"=>$fname, "lname"=>$lname, "phoneNumber"=>$phoneNumber, "address"=>$address, "addressTwo"=>$addressTwo, "cityID"=>$cityID, "aid"=>$aid]);

                    } else {
                        $error = "Mesto, poštna številka in država morajo biti podani!";
                    }
                } else {
                    $error = "Naslov mora biti podan!";
                }
            } else {
                $error = "Ime in priimek morata biti podana!";
            }
            echo json_encode(['error'=>$error]);
            exit();
        }
    }

    if(isset($_POST['updatePass']) && !empty($_POST['updatePass']) && $_POST['updatePass'] == "updatePassword") {
        if(isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $aid = $_SESSION['id'];

            include_once './src/inc/xss_cleaner.inc.php';
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                $password = xss_cleaner($_POST['password']);
                $pdo = pdo_connect_mysql();
                
                $acc = $pdo->query("SELECT * FROM account WHERE id = $aid")->fetch(PDO::FETCH_ASSOC);
                if(password_verify($password, $acc['password'])) {
                    if(isset($_POST['newPassword'], $_POST['confirmNewPassword']) && !empty($_POST['newPassword']) && !empty($_POST['confirmNewPassword'])) {
                        $newPassword = xss_cleaner($_POST['newPassword']);
                        $confirmPassword = xss_cleaner($_POST['newPassword']);

                        // check if password is longer then 8 chars
                        if (strlen($newPassword) >= 8) {
                            // check if password has a lowercase
                            if (preg_match('/[a-z]/', $newPassword)) {
                                // check if password has uppercase
                                if (preg_match('/[A-Z]/', $newPassword)) {
                                    // check if password contains a digit/number
                                    if (preg_match('/\d/', $newPassword)) {
                                        // check if password contains a special character
                                        if (preg_match('/[^a-zA-Z\d]/', $newPassword)) {
                                            if($newPassword == $confirmPassword) {
                                                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                                                try {
                                                    $pdo->beginTransaction();
                                                    $sql = "UPDATE account SET password = :newPassword WHERE id = :aid";
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->execute(['newPassword'=>$newPassword, 'aid'=>$aid]);
                                                    $stmtErr = $stmt->errorInfo();
                                                    if ($stmtErr[0] != 0) {
                                                        throw new PDOException("error=err");
                                                    }
                                                    $pdo->commit();
                                                    header("Location: ./profile?status=success");
                                                    exit();
                                                } catch (PDOException $e) {
                                                    $error = $e->getMessage();
                                                    header("Location: ./profile?$error");
                                                    exit();
                                                }

                                            } else {
                                                header("Location: ./profile?error=pass-match");
                                                exit();
                                            }
                                        } else {
                                            header("Location: ./profile?error=pass-special");
                                            exit();
                                        }
                                    } else {
                                        header("Location: ./profile?error=pass-digit");
                                        exit();
                                    }
                                } else {
                                    header("Location: ./profile?error=pass-upper");
                                    exit();
                                }
                            } else {
                                header("Location: ./profile?error=pass-lower");
                                exit();
                            }
                        } else {
                            header("Location: ./profile?error=pass-length");
                            exit();
                        }
                    } else {
                        header("Location: ./profile?error=empty");
                        exit();
                    }
                } else {
                    header("Location: ./profile?error=wrong");
                    exit();
                }
            } else {
                header("Location: ./profile?error=empty");
                exit();
            }
        }
    }

?>