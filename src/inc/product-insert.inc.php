<?php
 
    if (!isset($_POST['submit'])) {
        header("Location: ../../product-add.php");
        exit();
    } else {
        // import DB connection
        include_once "./dbh.inc.php";
        $pdo = pdo_connect_mysql();

        // import xss_cleaner function
        include_once "./xss_cleaner.inc.php";

        $title = xss_cleaner($_POST['title']);
        $images = $_FILES['img'];
        $summary = xss_cleaner($_POST['summary']);
        $description = xss_cleaner($_POST['description']);
        $sku = xss_cleaner($_POST['sku']);
        $quantity = xss_cleaner($_POST['quantity']);
        $price = xss_cleaner($_POST['price']);

        // set date from input, if empty set current date
        $publishDate = date('Y-m-d', strtotime($_POST['publishDate']));
        if (empty($_POST['publishDate'])) {
            $publishDate = date('Y-m-d');
        }

        $height = $_POST['height'];
        $length = $_POST['lenght'];
        $width = $_POST['width'];
        $weight = $_POST['weight'];
        $categories = "";
        $brand = null;

        //last inserted product id
        $product_id="";

        // return status/error of insert;
        $status = "";
        // check if categories were selected and save to new array
        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $categories = $_POST['category'];
        }

        // check if brand was selected and is not empty, save value to brand
        if (isset($_POST['brand']) && !empty($_POST['brand'])) {
            $brand = $_POST['brand'];
        }

        // check if product title is not empty
        if (!empty($title)) {
            // check if SKU is given
            if (!empty($sku)) {
                $stmt = $pdo->prepare("SELECT * FROM product WHERE sku LIKE ?");
                $stmt->execute([$sku]);
            // check if SKU is unique
                if ($stmt->rowCount() == 0) {
                    // check if quantity is given
                    if (!empty($quantity)) {
                        // check if quantity value is a whole number
                        if (is_int($quantity) && $quantity >= 0) {
                            // check if price is given
                            if (!empty($price)) {
                                // check if price is a positive decimal number
                                if (is_float($price) && $price >= 0) {

                                    // try catch statement to make sure either everything inserts or nothing
                                    try {
                                        $pdo->beginTransaction();
                                        // insert into product
                                        $stmt = $pdo->prepare("INSERT INTO product (title, summary, description, sku, quantity, price, date_published, brand_id) VALUES(?,?,?,?,?,?,?,?)");
                                        $stmt->execute([$title, $summary, $description, $sku, $quantity, $price, $publishDate, $brand]);
                                        $stmtErr = $stmt->errorInfo();
                                        if ($stmtErr[0] != 0) {
                                            throw new Exception("error=prd-ins"); 
                                        } else {
                                           $product_id = $pdo->lastInsertId();
                                        }
                            
                                        // check if any measurement is not empty

                                        if (!empty($height) || !empty($width) || !empty($length) || !empty($weight)) {
                                            // insert into product measurment
                                            $stmt = $pdo->prepare("INSERT INTO `measurement` (`product_id`, `height`, `width`, `lenght`, `weight`) VALUES (?,?,?,?,?)");
                                            $stmt->execute([$product_id, $height, $width, $length, $weight]);
                                            $stmtErr = $stmt->errorInfo();
                                            if ($stmtErr[0] != 0) {
                                                throw new Exception("error=measure-ins"); 
                                            }
                                        }
                                        
                                        // create releationship between product and category
                                        if(!empty($categories)){
                                            foreach ($categories as $category_id) {
                                                $stmt = $pdo->prepare("INSERT INTO product_category (product_id, category_id) VALUES(?,?)");
                                                $stmt->execute([$product_id,$category_id]);
                                                $stmtErr = $stmt->errorInfo();
                                                if ($stmtErr[0] != 0) {
                                                    throw new Exception("error=prd-cat");
                                                }
                                            }
                                        }
                            
                                        // check if product has any images
                                        if(!empty($_FILES['img']['name'][0])) {        
                                            // import image upload funtion
                                            include_once "./image-upload.inc.php";
                                            // rearange the array
                                            function diverse_array($vector) {
                                                $result = array();
                                                foreach($vector as $key1 => $value1)
                                                    foreach($value1 as $key2 => $value2)
                                                        $result[$key2][$key1] = $value2;
                                                return $result;
                                            }
                            
                                            $images = diverse_array($images);
                            
                                            $i=1;
                                            foreach($images as $img) {
                                                $caption=$title."-".$i;
                                                $query = "INSERT INTO product_image (product_id, caption, image) VALUES(?,?,?)";
                                                $stmt = $pdo->prepare($query);
                                                $status = upload_image($caption, $img);
                            
                                                if (explode("=", $status)[0] != "error") {
                                                    $stmt->execute([$product_id, $caption, $status]); 
                                                }
                                                $i++;
                                            }
                                        }
                                        
                                        $pdo->commit();
                                        header("Location: ../../product-add?status=ok");
                                        exit();
                                    } catch (Exception $e) {
                                        $pdo->rollBack();
                                        $error = $e->getMessage();
                                        header("Location: ../../product-add?$error");
                                        exit();
                                    }

                                } else {
                                    header("Location: ../../product-add?error=price-type");
                                    exit();
                                }
                                
                            } else {
                                header("Location: ../../product-add?error=price-empty");
                                exit();
                            }

                        } else {
                            header("Location: ../../product-add?error=quantity-type");
                            exit();
                        }

                    } else {
                        header("Location: ../../product-add?error=quantity-empty");
                        exit();
                    }
                } else {
                    header("Location: ../../product-add?error=sku-duplicate");
                    exit();
                }
            } else {
                header("Location: ../../product-add?error=sku-empty");
                exit();
            }
        } else {
            header("Location: ../../product-add?error=prd-title");
            exit();
        }

        
    }

?>