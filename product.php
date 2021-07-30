<?php
    include_once './header.php';

    if (isset($_GET['pid']) && !empty($_GET['pid'])) {
        $pid = $_GET['pid'];
    } else {
        exit("<script>window.location.href='index'</script>");
    }

    $pdo = pdo_connect_mysql();

    $stmt = $pdo->query("SELECT * FROM product p LEFT JOIN measurement m ON p.id = m.product_id WHERE id = $pid");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    $discount = discountData($pid);
    if(!empty($discount)) {
        $price = retailPrice($product['price'], $discount['discount']);
    } else {
        $price = $product['price'];
    }
    $images = productImages($pid);
    $len = count($images);
?>

<div class="container">
    <div class="row my-2">
        <div class="col-md-6 p-image-window img-thumbnail">
            <div class="p-img d-flex align-items-center justify-content-center">
                <div id="carouselControls" class="carousel slide" data-ride="carousel">
                    <?php if($len > 1): ?>
                        <ol class="carousel-indicators">
                            <?php for ($i=0; $i < $len; $i++):
                                if($i == 0): ?>
                                    <li data-target="#carouselIndicators" data-slide-to="<?=$i?>" class="active"></li>
                                <?php else: ?>
                                    <li data-target="#carouselIndicators" data-slide-to="<?=$i?>"></li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ol>
                    <?php endif; ?>
                    <div class="carousel-inner">
                        <?php if($len > 0): ?>
                            <?php for ($i=0; $i < $len; $i++):
                                if($i == 0): ?>
                                    <div class="carousel-item active">
                                <?php else: ?>
                                    <div class="carousel-item">
                                <?php endif; ?>
                                <img class="d-block w-100 my-auto img-fluid img-thumbnail" src="<?=$images[$i]['image']?>" alt="<?=$image[$i]['caption']?>">
                                </div>
                            <?php endfor; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img class="d-block w-100 my-auto img-fluid img-thumbnail" src="https://via.placeholder.com/500x500" alt="<?=$product['title']?>">
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if($len > 0): ?>
                    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <di class="rating"></di>
            <?php for($i=0; $i<5; $i++):
                if($i<average_rating($product['id'])): ?>
                    <span class="fa fa-star checked text-success"></span>
                <?php else: ?>
                    <span class="fa fa-star"></span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <div class="col-md-6 p-content">
            <div class="card p-card">
                <div class="card-body">
                    <div class="small mb-1">
                        SKU: <?=$product['sku']?>
                    </div>
                    <div class="p-header">
                        <!-- <?php if (!empty($discount)): ?>
                        <div class="discount-tag position-absolute badge bg-success">
                                <span>-<?=$discount['discount']?>%</span>
                        </div> -->
                        <?php endif; ?>
                        <h3><?=$product['title']?></h3>
                    </div>
                    <div class="p-price pt-2">
                        <span class="old-price"><?php if(!empty($discount)): ?><?=$product['price']?>€<?php endif; ?></span>
                            <?php if (!empty($discount)): ?>
                                <span class="discount-tag position-absolute badge bg-success ml-3">-<?=$discount['discount']?>%</span>
                        <?php endif; ?>
                        <div class="new-price"><?=$price?>€</div>
                    </div>
                    <div class="sale-date">
                        <?php if(!empty($discount)): ?>
                            Razprodaja od <?=date('j.n', strtotime($discount['date_start']))?> do <?=date('j.n', strtotime($discount['date_end']))?>
                        <?php endif; ?>
                    </div>
                    <div class="summary py-3">
                        <?=$product['summary']?>
                    </div>
                </div>
                <div class="availability ml-3">
                    <?php if($product['quantity'] == 0): ?>
                        <span class="text-danger">Zmanjkalo zalog!</span>
                    <?php elseif($product['quantity'] <= 50): ?>
                        <span class="text-warning">Zadnji kosi!</span>
                    <?php else: ?>
                        <span class="text-success">Izdelek na zalogi!</span>
                    <?php endif; ?>
                </div>
                <div class="card-btn d-flex text-center mb-2 text-center">
                        <input type="number" class=" form-control hide-arrow text-center mx-3 rounded border-1" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" required style="max-width: 3rem">
                        <input type="hidden" name="pid" value="<?=$pid?>">
                        <button type="submit" class="insert-cart btn btn-primary" <?php if($product['quantity'] == 0){?> disabled <?php }?> style="width:225px;"><i class="fas fa-shopping-cart"></i> V košarico</button>
                </div><!-- card-btn -->
            </div>
        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>