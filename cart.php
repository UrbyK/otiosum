<?php
    include_once './header.php';
?>

<?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])):
        $items = $_SESSION['cart'];
        $sum = 0;
        foreach($items as $pid => $quantity) {
            $product = product($pid);
            $discount = discountData($pid);
            if(!empty($discount)) {
                $price = retailPrice($product['price'], $discount['discount']);
            } else {
                $price = $product['price'];
            }
            $sum += $price * $quantity;
    } ?>

    <?php if (!isset($_GET['c']) && empty($_GET['c'])): ?>
    <div class="container">
        <div class="row">
            <div class="card w-100 border-0 my-3">
                <div class="row px-3">
                    <div class="col cart my-3">
                        <div class="title">
                            <div class="row">
                                <div class="col text-center">
                                    <h1 style="font-family:'Cabin Sketch';"><b>Košarica</b></h1>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col align-self-center text-right num-cart-items">
                                    Število izdelkov: <span id="items-in-cart"><?=array_sum($items)?></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?php foreach($items as $pid => $quantity):
                            $product = product($pid);
                            $images = productImages($pid); 
                            $discount = discountData($pid);
                            if(!empty($discount)) {
                                $price = retailPrice($product['price'], $discount['discount']);
                            } else {
                                $price = $product['price'];
                            } ?>
                        <div class="row align-items-center border-bottom border-top my-2 item">
                            <div class="col">
                                <?php if(isset($images) && !empty($images)): ?>
                                <img src="<?=$images[0]['image']?>" alt="<?=$images[0]['caption']?>" class="img-fluid" style="max-height:100px; ">
                                <?php else: ?>
                                <img src="./src/img/missing-image.png" alt="<?=$product['title']?>" class="img-fluid" style="max-height:100px; max-width:200px;">
                                <?php endif; ?>

                                <?php if(!empty($discount)): ?>
                                    <span class="badge bg-success" style="font-size:1.2rem; color:white;">-<?=$discount['discount']?>%</span>
                                <?php endif; ?>

                            </div>

                            <div class="col">
                                <div class="row cart-title"><a href="./product?pid=<?=$pid?>"><?=substr($product['title'], 0, 65).'...'?></a></div>
                            </div>
                            <div class="col values">
                                <input type="number" name="quantity" id="quantity" class="form-control hide-arrow text-center quanity" min="1" step="1" value="<?=$quantity?>" style="max-width:4rem;">
                                <input type="hidden" name="pid" id="pid" value="<?=$pid?>">
                            </div>
                            <div class="col"><span class="price" id="<?=$pid?>"> <?=$price*$quantity?></span><b> &euro;</b> <button class="close" value="<?=$pid?>">&#10005;</button></div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="row align-items-center border-bottom mx-2 my-2">
                            <div class="col text-right ">Skupna cena: <span class="total"><?=$sum?></span><b> &euro;</b></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-right my-2">
                        <a class="btn btn-primary btn-next" href="./cart?c=order">Naslednji korak</a>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- container -->
    <script>
        $(document).on('change', '#quantity', function(){ 
            var action = 'updateValues';
            var pid = $(this).closest('.values').find('input[type=hidden]').val();
            var quantity = $(this).val();
            
                $.ajax({
                // contentType: "application/json",
                url: "./src/inc/cart.inc.php",
                method: "POST",
                data: {'action':action, 'pid':pid, 'quantity':quantity},
                dataType: "json",
                // cache: false,
                success: function(response) {
                    // $('.price').html(response.price);
                    $("#"+pid).text(response.price);
                    $("#items-in-cart").html(response.totalItems);
                    loadNumberOfCartItems();
                    totalPriceSum();
                }
            });
        });

        function totalPriceSum() {
            var totalSum = 0;
            $('.price').each(function(){
                totalSum += parseFloat($(this).text());
            });
            $('.total').text(totalSum.toFixed(2));
        }

        $(document).on('click', '.close', function() {
            var action = "deleteItem";
            var pid = $(this).val();
            var ele = $(this).closest(".item");
            console.log(action, pid, ele);
            $.ajax({
                type:'POST',
                data: {'action':action, 'pid':pid},
                url: "./src/inc/cart.inc.php",
                dataType: "json",
                success: function(response) {
                    ele.fadeOut().remove();
                    $("#items-in-cart").html(response.totalItems);
                    loadNumberOfCartItems();
                    totalPriceSum();
                }
            });
        });
    </script>

    <?php elseif(isset($_GET['c'])&& !empty($_GET['c']) && $_GET['c'] == 'order'): ?>
        <?php if(isLogin()):
            $aid = $_SESSION['id'];
            $user = user($aid);
            $city = city($user['city_id']);
            $countries = countries();?>
            <div class="container">
                <div class="alert w-100 text-center alert-danger rounded my-3" hidden>
                    <h4 class="alert-error"></h4>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <input type="hidden" id="aid" value="<?=$aid?>" disabled>
                        <div class="row justify-content-center my-3 filter-data h-100">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row justify-content-center my-3 h-100">
                            <div class="card w-100 summary">
                                <div class="card-body">
                                    <form id="deliver-summary" method="POST" action="./src/inc/order.inc.php">
                                        <input type="text" name="action" value="insertData" hidden>
                                        <div class="form-group">
                                            <label for="paymentMethod">Plačilo</label>
                                            <select id="paymentMethod" class="form-control" name="paymentMethod"required>
                                                <?php $paymentMethods = paymentMethod();?>
                                                <?php foreach($paymentMethods as $paymentMethod): ?>
                                                    <option value="<?=$paymentMethod['id']?>"><?=$paymentMethod['type']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="paymentMethod">Dostava</label>
                                            <?php $deliveryTypes = getDeliveryType(); ?>
                                            <select id="deliveryMethod" class="form-control" name="deliveryMethod"required>
                                                <?php foreach($deliveryTypes as $delivery): ?>
                                                    <option value="<?=$delivery['id']?>" data-price="<?=$delivery['price']?>"><?=$delivery['type']?> - <?=$delivery['price']?> €</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="row my-2 border-bottom mx-2">
                                            <input id="originalSum" name="originalSum" type="number" value="<?=$sum?>" hidden disabled>
                                            <input id="totalSum" name="totalSum" type="number" step="0.01" value="<?=$sum?>"hidden>
                                            <h4>Skupna cena: <span class="price"><?=$sum?></span> <b>€</b></h4>
                                        </div>
                                        <div class="row my-2">
                                            <button type="submit" id="order" class="btn btn-primary btn-next w-100">Naroči</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-left my-4">
                        <a class="btn btn-primary btn-next" href="./cart">Prejšnji korak</a>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="alert w-100 my-3 text-center alert-info rounded">
                        <h2>Za nadaljevanje vas prosimo da se vpišete v svoj račun!</h2>
                    </div>
                </div>
            </div>
        <?php endif; ?>
            
    <script src="./src/js/ajax-account.js" crossorigin="anonymous"></script>

    <script>
        // live update to total price since delivery methods/type have differante prices
        $('#deliveryMethod').on('change', function() {
            var sum = parseFloat($('#originalSum').val());
            var price = parseFloat($(this).find(':selected').data('price'));
            sum += price;
            $('.price').html(sum.toFixed(2));
            $('#totalSum').val(sum);
        });

        // $(document).on('click', '#order', function() {
        //     var data = {
        //         action = 
        //     }
        // });


    </script>
    <?php endif;?>

<?php elseif (isset($_GET['c'])&& !empty($_GET['c']) && $_GET['c'] == 'success' && isset($_GET['oid']) && !empty($_GET['oid'])): ?>
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="alert w-100 my-3 text-center alert-info rounded">
                <h2><b>Hvala za nakup!</b></h2>
                <br>
                <h2>Vaše naročilo <b><?=$_GET['oid']?></b> smo uspešno prejeli! Poslali smo vam email s potrditvijo naročila. Ko bomo naročilo odposlali vas bomo obvestili preko email-a!</h2>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="alert w-100 my-3 text-center alert-info rounded">
                <h2>V košarici nimate izdelkov!</h2>
            </div>
        </div>
    </div>
<?php endif;?> 

<?php
    include_once './footer.php';
?>