<?php 
    $discount = discountData($product['id']);
        if(!empty($discount)) {
            $price = retailPrice($product['price'], $discount['discount']);
        } else {
            $price = $product['price'];
        }
?>
    <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-2 my-3">
        <div class="card product-item">
            <div class="card-body">
                <div class="cp-img">
                <?php if (!empty($discount)): ?>
                <div class="discount-tag position-absolute badge bg-success rounded-circle p-2 m-2">
                    <span>-<?=$discount['discount']?>%</span>
                </div>
                <?php endif; ?>
                    <div class="hvrbox">
                        <a href="./product?pid=<?=$product['id']?>">
                            <?php $images = productImages($product['id']);
                            if ($images):?>
                            <img src="<?=$images[0]['image']?>" alt="<?=$images[0]['caption']?>" class=" my-auto img-fluid img-thumbnail hvrbox-layer_bottom">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/180x250" alt="placeholder-image">
                            <?php endif; ?>
                            <div class="hvrbox-layer_top hvrbox-layer_slideup">
                                <p class="hvrbox-text">
                                    <?php if(strlen($product['summary']) > 80){ 
                                        echo substr($product['summary'], 0, 80) . '...';
                                    } else{
                                        echo $product['summary'];
                                    } ?>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="cp-rating small my-2">
                    <?php for($i=0; $i<5; $i++):
                        if($i<average_rating($product['id'])): ?>
                            <span class="fa fa-star checked text-success"></span>
                        <?php else: ?>
                            <span class="fa fa-star"></span>
                        <?php endif;
                    endfor; ?>
                    <?php $numRating = number_of_ratings($product['id']);
                    if ($numRating > 0):?>
                    <span class="small"><?=$numRating?>x</span>
                    <?php endif; ?>
                </div>
                <div class="cp-details">
                    <div class="cp-title">
                        <h5><a href="./product?pid=<?=$product['id']?>"><?php if(strlen($product['title']) > 65){ 
                                        echo substr($product['title'], 0, 65) . '...';
                                    } else{
                                        echo $product['title'];
                                    } ?>
                        </a></h5>
                    </div>
                    <span class="small">SKU: <?=$product['sku']?></span>
                    <div class="cp-price d-flex justify-content-center">
                        <div class="col-6 old-price"><?php if(!empty($discount)): ?><?=$product['price']?> €<?php endif; ?></div>
                        <div class="col-6 new-price"><?=$price?> €</div>
                    </div>
                    <div class="sale-date">
                        <?php if(!empty($discount)): ?>
                            Razprodaja od <?=date('j.n', strtotime($discount['date_start']))?> do <?=date('j.n', strtotime($discount['date_end']))?>
                        <?php endif; ?>
                    </div>
                    <div class="availability">
                        <?php if($product['quantity'] == 0): ?>
                            <span class="text-danger">Zmanjkalo zalog!</span>
                        <?php elseif($product['quantity'] <= 50): ?>
                            <span class="text-warning">Zadnji kosi!</span>
                        <?php else: ?>
                            <span class="text-success">Izdelek na zalogi!</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary cp-btn" <?php if($product['quantity'] == 0): ?>disabled<?php endif; ?>><b>Dodaj v košarico</b></button>
        </div>
    </div>