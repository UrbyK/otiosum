<?php
    include_once './header.php';
?>

<?php
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $items = $_SESSION['cart'];
    }
    $sum = 0;
?>

<div class="container">
    <div class="row">
        <div class="card w-100 border-0 mx-3">
            <?php if(isset($items)): ?>
            <div class="row px-3">
                <div class="col cart">
                    <div class="title">
                        <div class="row">
                            <div class="col">
                                <h1 style="font-family:'Cabin Sketch';"><b>Košarica</b></h1>
                            </div>
                            <div class="col align-self-center text-right" id="items-in-cart">
                                Število izdelkov: <?=array_sum($items)?>
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
                        }
                        $sum += $price * $quantity;?>
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
                            <div class="row text-muted"><a href="./product?pid=<?=$pid?>"><?=substr($product['title'], 0, 65).'...'?></a></div>
                        </div>
                        <div class="col values">
                            <input type="number" name="quantity" id="quantity" class="form-control hide-arrow text-center quanity" min="1" step="1" value="<?=$quantity?>" style="max-width:4rem;">
                            <input type="hidden" name="pid" id="pid" value="<?=$pid?>">
                        </div>
                        <div class="col"><span class="price" id="<?=$pid?>"> <?=$price*$quantity?> &euro;</span> <button class="close" value="<?=$pid?>">&#10005;</button></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <hr>
            <div class="row px-3 align-items-center">
                <div class="col text-right">Skupna cena <span class="total"><?=$sum?> &euro;</span></div>
            </div>
            <?php else: ?>
                <h1 class="text-center">Ni izdelkov v košarici</h1>
            <?php endif; ?>
        </div>
    </div>
</div><!-- container -->


<?php
    include_once './footer.php';
?>

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