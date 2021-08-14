<?php 
    include_once './functions.php';
    include_once './src/inc/session.php';

    if (isset($_POST['action']) && $_POST['action'] == "fetchData") {
        $select = "SELECT * FROM otiosum.order";
        $where = " WHERE date_created <= current_timestamp()";
        $limit = "";
        $orderBy = "";
        $pdo = pdo_connect_mysql();
        
        if (isset($_POST['search']) && !empty($_POST['search'])) {
            $search = $_POST['search'];
            $where .= " AND id LIKE '%$search%'";
        }

        if (isset($_POST['orderStatus']) && !empty($_POST['orderStatus'])) {
            $status = $_POST['orderStatus'];
            $where .= " AND order_status_id = $status";
        }
        
        if (isset($_POST['dateOrder']) && !empty($_POST['dateOrder'])) {
            $dateOrder = $_POST['dateOrder'];
            if($dateOrder == "up") {
                $orderBy .= " ORDER BY date_created ASC";
            } else {
                $orderBy .= " ORDER BY date_created DESC";
            }
        }

        if((isAdmin() || isMod()) && isset($_POST['o']) && $_POST['o'] == "list"){
            $isPrivlage = true;
        } else {
            $aid = $_SESSION['id'];
            $where .= " AND account_id = $aid";
            $isPrivlage = false;
        }

        // limit per page
        if (isset($_POST['limit']) && !empty($_POST['limit'])) {
            $lim = $_POST['limit'];
            if (isset($_POST['page']) >= 1) {
                $start = (($_POST['page'])-1) * $lim;
                $page = ($_POST['page']);
            } else {
                $start = 0;
            }
            $limit = " LIMIT $start, $lim";
        }

        $orders = $pdo->query($select.$where.$orderBy.$limit)->fetchAll(PDO::FETCH_ASSOC);
        $output = orderList($orders, $isPrivlage, $pdo);

        $totalOrders = $pdo->query($select.$where)->rowCount();
        $totalPages = ceil($totalOrders/(int)$lim);
        $pagination = ajaxPagination($page, $totalPages);

        echo json_encode(['output'=>$output, 'pagination'=>$pagination]);
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] == "updateOrder") {
        if(isset($_POST['orderStatus'], $_POST['oid']) && !empty($_POST['orderStatus']) && !empty($_POST['oid'])) {
            $pdo = pdo_connect_mysql();
            $osID = $_POST['orderStatus'];
            $oid = $_POST['oid'];

            $stmt = $pdo->prepare("UPDATE otiosum.order SET order_status_id = :osID WHERE id = :oid");
            $stmt->execute(['osID'=>$osID, 'oid'=>$oid]);
            $stmtErr = $stmt->errorInfo();
            if ($stmtErr[0] != 0) {
                echo "err";
                exit;
            }

            $orderMessage = [
                'Naročeno'=>"Prejeli smo vaše naročilo! Hvala za nakup!",
                'Odposlano'=>"Obdelali smo vaše naročilo in ga odposlali!",
                'Dostavljeno'=>"Naročilo smo dostavili k vam!",
                'Ne moremo dostaviti'=>"Naročila ne moremo dostaviti!",
                'Preklicano'=>"Naročilo je bilo preklicano!"
            ];
            $status = $pdo->query("SELECT * FROM order_status WHERE id = $osID")->fetch(PDO::FETCH_ASSOC);
            $orderStatus = $status['status'];
            foreach($orderMessage as $key => $value) {
                if ($key == $orderStatus) {
                    $message = $value;
                }
            }
            $order = $pdo->query("SELECT * FROM otiosum.order WHERE id = $oid")->fetch(PDO::FETCH_ASSOC);
            $to_email = $pdo->query("SELECT email FROM account a INNER JOIN otiosum.order o ON a.id = o.account_id WHERE o.id=$oid")->fetch(PDO::FETCH_COLUMN);

            include_once "./src/inc/order-email.inc.php";
            send_order_email($to_email, $message, $orderStatus, $order, $oid);

            echo "success";
            exit();
        }
    }

?>