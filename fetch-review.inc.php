<?php
    include_once './functions.php';
    include_once './src/inc/session.php';

    if (isset($_POST['action'])) {
        $limit = 10;
        $output = "";
        $pagintion = "";

        if (isset($_POST['pid']) && !empty($_POST['pid'])) {
            $pdo = pdo_connect_mysql();

            $pid = $_POST['pid'];

            if (isset($_POST['page']) >= 1) {
                $start = (($_POST['page'])-1) * $limit;
                $page = $_POST['page'];
            } else {
                $start = 0;
            }

            $query = "SELECT * FROM review WHERE product_id = $pid ORDER BY id DESC LIMIT $start, $limit";
            $stmt = $pdo->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $totalRow = $stmt->rowCount();

            if($totalRow > 0) {
                foreach ($result as $review) {
                    $aid = $review['account_id'];
                    $user = user($aid);
                    $output .= reviewThemplate($review, $user);
                }
                $sql = "SELECT * FROM review WHERE product_id = $pid";
                $stmt = $pdo->query($sql);
                $totalReviews = $stmt->rowCount();
                $totalPages = ceil($totalReviews/$limit);

                $pagination = ajaxPagination($page, $totalPages);
            }
        }

        $response = ['output'=>$output, 'pagination'=>$pagination];
        echo json_encode($response);
        exit();
    }

?>