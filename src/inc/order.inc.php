<?php
    include '../../functions.php';
    include './session.php';

    if(isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == "insertData") {
        if(isset($_SESSION['cart'])) {
            if(isset($_POST['paymentMethod']) && !empty($_POST['paymentMethod'])) {
                if(isset($_POST['deliveryMethod']) && !empty($_POST['deliveryMethod'])) {
                    $pmID = $_POST['paymentMethod'];
                    $dmID = $_POST['deliveryMethod'];
                    print_r($_POST);
                    var_dump($_SESSION);
                    if(isset($_POST['totalSum']) && !empty($_POST['totalSum'])) {
                        $cartItems = $_SESSION['cart'];
                        $totalSum = $_POST['totalSum'];
                        $aid = $_SESSION['id'];
                        $pdo = pdo_connect_mysql();
                        try {
                            $pdo->beginTransaction();

                            // create an order ()
                            $sql = "INSERT INTO otiosum.order(total, account_id, order_status_id, payment_type_id, delivery_type_id) VALUES(:total, :aid, :ordStatID, :payTypeID, :delTypeID)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(["total"=>$totalSum, 'aid'=>$aid, 'ordStatID'=>"1",'payTypeID'=>$pmID, 'delTypeID'=>$dmID]);
                            $oid = $pdo->lastInsertId();
                            // iterate for all elements in the cart
                            foreach($cartItems as $pid => $quantity) {
                                $query = "SELECT title, sku, price, quantity FROM product WHERE id = $pid ";
                                $product = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
                                // check if product is on sale and get new price
                                $discount = discountData($pid);
                                if(!empty($discount)) {
                                    $price = retailPrice($product['price'], $discount['discount']);
                                } else {
                                    $price = $product['price'];
                                }
                                $total = $price * $quantity;
                                
                                // add items to order (fill order_item table)
                                $sql = "INSERT INTO order_item(product_id, title, sku, quantity, price, total, order_id) VALUES(:pid, :title, :sku, :quantity, :price, :total, :orderID)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(['pid'=>$pid, 'title'=>$product['title'], 'sku'=>$product['sku'], 'quantity'=>$quantity, 'price'=>$price, 'total'=>$total, 'orderID'=>$oid]);

                                // subtracting order quantity from avliable quantity in product
                                $newQuantity = $product['quantity'] - $quantity;
                                $sql = "UPDATE product SET quantity = :quantity WHERE id = :pid";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(['quantity'=>$newQuantity, 'pid'=>$pdi]);

                            }
                            $pdo->commit();
                            unset($_SESSION['cart']);
                            header("Location: ../../cart?c=success&oid=$oid");
                            exit();
                        } catch (Exception $e) {
                            $pdo->rollBack();
                            echo $e->getMessage();
                            exit();
                        }
                    }
                }
            }
        } else {
            header("Location: ../../index");
            exit();
        }
    }

?>