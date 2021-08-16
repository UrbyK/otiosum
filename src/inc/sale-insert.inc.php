<?php

    require_once './session.php';
    if (!isLogin()) {
        header("Location: ../../index");
        exit();
    } elseif (!isAdmin() && !isMod()) {
        header("Location: ../../index");
        exit();
    }

    if(!isset($_POST['submit'])) {
        header("Location: ../../sale.php");
        exit();
    } else {
        include_once './dbh.inc.php';
        $pdo = pdo_connect_mysql();

        $startDates = $_POST['start_date'];
        $endDates = $_POST['end_date'];
        $discounts = xss_cleaner($_POST['discount']);

        // number of failed inserts
        $fails = 0;

        for ($i=0; $i < count($startDates); $i++) { 
            echo "Začetek popusta: ".$startDates[$i]." / Konec popusta: ".$endDates[$i]." / Popust: ".$discounts[$i]."<br>";
            $startDate = date('Y-m-d', strtotime($startDates[$i]));
            $endDate = date('Y-m-d', strtotime($endDates[$i]));
            $discount  = $discounts[$i];

            // check if all values are given
            if (isset($startDate) && !empty($startDate)) {

                if (isset($endDate) && !empty($endDate)) {

                    if (!empty($discount) && (int)$discount > 0) {
                        // check if the combination of begining ending and discount value already exist
                        $query = "SELECT * FROM sale WHERE date_start LIKE ? AND date_end LIKE ? AND discount = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$startDate, $endDate, $discount]);
                        $num = $stmt->rowCount();
                        // if the sale doesn't exist save to table
                        if ($num == 0) {
                            $query = "INSERT INTO sale (date_start, date_end, discount) VALUES(?,?,?)";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([$startDate, $endDate, $discount]);
                        }
                    } else {
                        $fails++;
                        echo "Popust mora bit podan in večji od 0";
                    }
                } else {
                    $fails++;
                    echo "Končni datum mora biti podan";
                }
            } else {
                $fails++;
                echo "Začetni datum mora biti podan";
            }
        }
        if ($fails == 0) {
            header("Location: ../../sale?option=add&status=ok");
            exit();
        } else {
            header("Location: ../../sale?option=add&status=ok&warning=$fails");
            exit();
        }

    }
?>