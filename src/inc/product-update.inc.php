<?php
    include_once './dbh.inc.php';
    $pdo = pdo_connect_mysql();
    // save POST array to new variable
    $data = $_POST;

    // check if categories data is empty and set it as a empty array
    if(empty($data['category'])) {
        $data['category'] = [];
    } 
    // check if sales data is empty and set is as an empty array
    if(empty($data['sale'])) {
        $data['sale'] = [];
    }
    // check if brand is empty and set is as an null
    if(empty($data['brand'])) {
        $data['brand'] = null;
    } 

    try {
        $pdo->beginTransaction();
        if (isset($data['pid']) && !empty($data['pid'])) {
            if (isset($data['title']) && !empty($data['title'])) {
                if (isset($data['sku']) && !empty($data['sku'])) {
                    // checks if there already exists a differante product with this SKU
                    $query = "SELECT * FROM product WHERE UPPER(sku) = UPPER(:sku) AND id NOT IN (:pid)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute(['sku'=>$data['sku'], "pid"=>$data['pid']]);
                    if ($stmt->rowCount() == 0) {

                        if (isset($data['price']) && !empty($data['price']) && $data['price'] > 0) {
                            if (isset($data['quantity']) && (int)$data['quantity']>=0) {

                                // check if a new file/s were selected
                                if(!empty($_FILES['img']['name'][0])) {
                                    // save images to a new variable
                                    $images = $_FILES['img'];

                                    // delete old images
                                    include_once './image-delete.inc.php';
                                    delete_image($data['pid']);
                            
                                    // rearange the images array
                                    function diverse_array($vector) {
                                        $result = array();
                                        foreach($vector as $key1 => $value1)
                                            foreach($value1 as $key2 => $value2)
                                                $result[$key2][$key1] = $value2;
                                        return $result;
                                    }
                                    $images = diverse_array($images);

                                    // insert new images
                                    $i = 1;
                                    include_once './image-upload.inc.php';
                                    foreach($images as $img) {
                                        $caption=$data['title']."-".$i;
                                        $query = "INSERT INTO product_image (product_id, caption, image) VALUES(?,?,?)";
                                        $stmt = $pdo->prepare($query);
                                        $image = upload_image($caption, $img);
                                        if (explode("=", $image)[0] != "error") {
                                            $stmt->execute([$data['pid'], $caption, $image]); 
                                        }
                                        $i++;
                                    }
                                }
                                

                                // get all curent product_category connection
                                $query = "SELECT category_id as cid FROM product_category WHERE product_id = :pid";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute(["pid"=>$data['pid']]);
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $result = array_values(array_column($result, 'cid'));
                                
                                // delete all product category connection that are no longer valid
                                foreach(array_diff($result, $data['category']) as $PCid) {
                                    $query = "DELETE FROM product_category WHERE product_id = :pid AND category_id = :cid";
                                    $pdo->prepare($query)->execute(["pid"=>$data['pid'], "cid"=>$PCid]);
                                }

                                // insert new categories to the product_category
                                foreach(array_diff($data['category'], $result) as $PCid) {
                                    $query = "INSERT INTO product_category(product_id, category_id) VALUES(:pid, :cid)";
                                    $stmt = $pdo->prepare($query);
                                     if(!$stmt->execute(["pid"=>$data['pid'], "cid"=>$PCid])) {
                                         throw new Exception("Napaka pri povezavi kategorij s izdelkom!");
                                     } 
                                }

                                // get all curent product_sale connection
                                $query = "SELECT sale_id as sid FROM product_sale WHERE product_id = :pid";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute(["pid"=>$data['pid']]);
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $result = array_values(array_column($result, 'sid'));
                                
                                // delete all product sale connection that are no longer valid
                                foreach(array_diff($result, $data['sale']) as $PSid) {
                                    $query = "DELETE FROM product_sale WHERE product_id = :pid AND sale_id = :sid";
                                    $pdo->prepare($query)->execute(["pid"=>$data['pid'], "sid"=>$PSid]);
                                }

                                // insert new sale to the product_sale
                                foreach(array_diff($data['sale'], $result) as $PSid) {
                                    $query = "INSERT INTO product_sale(product_id, sale_id) VALUES(:pid, :sid)";
                                    $stmt = $pdo->prepare($query);
                                     if(!$stmt->execute(["pid"=>$data['pid'], "sid"=>$PSid])) {
                                         throw new Exception("Napaka pri povezavi razprodaj s izdelkom!");
                                     } 
                                }

                                // update measurement
                                $sql = "UPDATE measurement SET height = :height, width = :width, length = :length, weight = :weight WHERE product_id = :pid";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(["height"=>$data['height'], "width"=>$data['width'], "length"=>$data['length'], "weight"=>$data['weight'], "pid"=>$data['pid']]);

                                // update product data
                                $sql = "UPDATE product SET title = :title, summary = :summary, description = :description, sku = UPPER(:sku), quantity = :quantity, price = :price, date_published = :publishDate, brand_id = :bid WHERE id = :pid";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(["title"=>$data['title'], "summary"=>$data['summary'], "description"=>$data['description'], "sku"=>$data['sku'], "quantity"=>$data['quantity'], "price"=>$data['price'], "publishDate"=>$data['publishDate'], "bid"=>$data['brand'], "pid"=>$data['pid']]); 

                            } else {
                                throw new Exception("Koli??ina mora biti podana!");
                            }
                        } else {
                            throw new Exception("Cena mora biti podana in ve??ja od 0!");
                        }
                    } else {
                        throw new Exception("SKU je ??e podan za drugi izdelek!");
                    }
                } else {
                    throw new Exception("SKU ??ifra/koda mora bit podana!");
                    
                }
            } else {
                throw new Exception("Ime izdelka mora biti podan!");                
            }
        } else {
            throw new Exception("Izdelek ni bil izbran!");
        }
        $pdo->commit();
        echo json_encode(array('success' => true, 'message' => "Izdelek uspe??no posodobljen!"));
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(array(
            'success' => false,
            'message' => $e->getMessage()
        ));
        exit();
    }
    echo json_encode(array(
        'success' => false,
        'message' => "Neznana napaka!"
    ));
    exit();

?>