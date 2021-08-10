<?php
    // include_once './dbh.inc.php';
    include_once './session.php';
    include_once '../../functions.php';

    $quantitySum = 0;

    if (isset($_POST['action']) && $_POST['action'] == "fetchItem") {
    
        // If the user clicked the add to cart button on the product page we can check for the form data
        if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {
            $pdo = pdo_connect_mysql();

            // Set the post variables so we easily identify them, also make sure they are integer
            $pid = (int)$_POST['pid'];
            if(isset($_POST['quantity']) && !empty($_POST['quantity']) && is_numeric($_POST['quantity'])) {
                $quantity = (int)$_POST['quantity'];
            } else {
                $quantity = 1;
            }

            // Prepare the SQL statement, we basically are checking if the product exists in our database
            $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
            $stmt->execute([$_POST['pid']]);
            // Fetch the product from the database and return the result as an Array
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            // Check if the product exists (array is not empty)
            if ($product && $quantity > 0) {
                // Product exists in database, now we can create/update the session variable for the cart
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    if (array_key_exists($pid, $_SESSION['cart'])) {
                        // Product exists in cart so just update the quantity
                        $_SESSION['cart'][$pid] += $quantity;
                    } else {
                        // Product is not in cart so add it
                        $_SESSION['cart'][$pid] = $quantity;
                    }
                } else {
                    // There are no products in cart, this will add the first product to cart
                    $_SESSION['cart'] = array($pid => $quantity);
                }
            }
        }

    }

    if(isset($_POST['action']) && $_POST['action'] == "updateValues") {
        if(isset($_POST['quantity']) && !empty($_POST['quantity']) && isset($_POST['pid']) && !empty($_POST['pid'])) {
            $pdo = pdo_connect_mysql();
            $pid = $_POST['pid'];
            $quantity = $_POST['quantity'];
            // Prepare the SQL statement, we basically are checking if the product exists in our database
            $stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
            $stmt->execute([$_POST['pid']]);
            // Fetch the product from the database and return the result as an Array
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            // Check if the product exists (array is not empty)
            if ($product && $quantity > 0) {
                // Product exists in database, now we can create/update the session variable for the cart
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    if (array_key_exists($pid, $_SESSION['cart'])) {
                        // Product exists in cart so just update the quantity
                        $_SESSION['cart'][$pid] = $quantity;
                    }
                }
                $discount = discountData($pid);
                if(!empty($discount)) {
                    $price = retailPrice($product['price'], $discount['discount']);
                } else {
                    $price = $product['price'];
                }
                $price = number_format((float)$quantity*$price,2,'.','');
                $totalItems = array_sum($_SESSION['cart']);
                $response = ['price'=>$price, 'totalItems'=>$totalItems];
                echo json_encode($response);

            }
        }
    }

    if(isset($_POST['action']) && $_POST['action'] == "deleteItem") {
        if(isset($_POST['pid']) && !empty($_POST['pid'])) {
            // remove key and value from session cart
            $pid = $_POST['pid'];
            unset($_SESSION['cart'][$pid]);

            $totalItems = array_sum($_SESSION['cart']);
            $response = ['totalItems'=>$totalItems];
            echo json_encode($response);
        }
    }


    if (isset($_POST['action']) && $_POST['action'] == "fetchCartNum") {
        $quantitySum = array_sum($_SESSION['cart']);
        $response = ['itemCount'=>$quantitySum];
        echo json_encode($response);
    }

?>