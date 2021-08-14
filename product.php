<?php
    include_once './header.php';

    if (isset($_GET['pid']) && !empty($_GET['pid'])) {
        $pid = $_GET['pid'];
    } else {
        exit("<script>window.location.href='index'</script>");
    }

    $pdo = pdo_connect_mysql();

    $stmt = $pdo->query("SELECT * FROM product WHERE id = $pid");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // check if product exists/isset
    if ($product):
        $discount = discountData($pid);
        if(!empty($discount)) {
            $price = retailPrice($product['price'], $discount['discount']);
        } else {
            $price = $product['price'];
        }
        $images = productImages($pid);
        $len = count($images);
        $measurement = measurement($pid);
        if (isLogin()) {
            $aid = $_SESSION['id'];
        }


?>

<div class="container">
    <div class="row my-2">
        <div class="col-md-6 p-image-window img-thumbnail">
            <div class="p-img d-flex align-items-center justify-content-center">
                <div id="carouselControls" class="carousel slide carousel-fade" data-ride="carousel">

                    <div class="carousel-inner">
                        <?php if($len > 0): ?>
                            <?php for ($i=0; $i < $len; $i++):
                                if($i == 0): ?>
                                    <div class="carousel-item active">
                                <?php else: ?>
                                    <div class="carousel-item">
                                <?php endif; ?>
                                <img class="d-block w-100 my-auto img-fluid " src="<?=$images[$i]['image']?>" alt="<?=$images[$i]['caption']?>">
                                </div>
                            <?php endfor; ?>
                        <?php else: ?>
                            <div class="carousel-item active">
                                <img class="d-block w-100 my-auto img-fluid img-thumbnail" src="https://via.placeholder.com/500x500" alt="<?=$product['title']?>">
                            </div>
                        <?php endif; ?>
                    </div>
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
        <div class="col-md-6 p-content">
            <div class="card p-card">
                <div class="card-body">
                    <div class="small mb-1">
                        SKU: <?=strtoupper($product['sku'])?>
                    </div>
                    <div class="p-header">
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
                    <input type="hidden" name="pid"id="pid" value="<?=$pid?>">
                    <input type="number" class="form-control hide-arrow text-center mx-3 rounded border-1" id="quantity" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" required style="max-width: 3rem" <?php if($product['quantity'] == 0): ?>disabled<?php endif; ?>>
                    <button type="submit" class="btn btn-primary cp-btn" id="addToCart" name="addToCart" value="<?=$pid?>" <?php if($product['quantity'] == 0): ?>disabled<?php endif; ?> style="width:225px;"><i class="fas fa-shopping-cart"></i> <b>Dodaj v košarico</b></button>
                </div><!-- card-btn -->
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col-12">
            <div class="product-detail">
                <ul class="nav nav-tabs nav-fill justify-content-center d-flex" id="detailTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="p-description-tab" data-toggle="tab" href="#p-description" role="tab" aria-controls="p-description" aria-selected="true">Opis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="measurement-tab" data-toggle="tab" href="#measurement" role="tab" aria-controls="measurement" aria-selected="false">Mere</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Mnenja</a>
                    </li>
                </ul>
                <div class="tab-content" id="detailTabContent">
                    <div class="tab-pane fade show active whitespace " id="p-description" role="tabpanel" aria-labelledby="p-description-tab"><h2 class="tab-content-header text-center">Opis</h2><?=$product['description']?></div>
                    <div class="tab-pane fade" id="measurement" role="tabpanel" aria-labelledby="measurement-tab">
                        <h2 class="tab-content-header text-center">Mere</h2>
                            <table class="w-75">
                                <tbody>
                                    <tr>
                                        <td class="text-left">Višina</td>
                                        <td class="text-right"><?php if(!empty($measurement['height'])):?><?=$measurement['height']?> cm<?php else: ?> N/A <?php endif; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Širina</td>
                                        <td class="text-right"><?php if(!empty($measurement['width'])):?><?=$measurement['width']?> cm<?php else: ?> N/A <?php endif; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Dolžina</td>
                                        <td class="text-right"><?php if(!empty($measurement['length'])):?><?=$measurement['length']?> cm<?php else: ?> N/A <?php endif; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Teža</td>
                                        <td class="text-right"><?php if(!empty($measurement['weight'])):?><?=$measurement['weight']?> Kg<?php else: ?> N/A <?php endif; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                        <h2 class="tab-content-header text-center">Mnenja</h2>

                        <div class="reviews-list">

                            <div id="review-insert">
                                <div class="comment-box ml-2">
                                <?php if (isLogin()): ?>
                                    <h4>Napišite komentar</h4>
                                    <div class="rating">
                                        <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                                        <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                                        <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                                        <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                                        <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                    </div>
                                    <div class="comment-area">
                                        <textarea class="form-control" id="comment" name="comment" placeholder="Komentar..." rows="4"></textarea>
                                        <input type="text" name="aid" id="aid" value="<?=$aid?>" hidden>
                                    </div>
                                    <div class="comment-bt mt-2 text-right">
                                        <button class="btn btn-primary send" id="review-ins" value="<?=$pid?>">Komentiraj <i class="fa fa-arrow-right"></i></button>
                                    </div>
                                <?php else: ?>
                                    Če želite napisati komentar, se prijavite v svoj račun!
                                <?php endif; ?>
                                </div>
                            </div>
                            <div class="comment">
                            </div>
                            <div class="row review-pagination justify-content-center text-center mt-3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="alert w-100 my-3 text-center alert-info rounded">
                <h2>Izdelek ne obstaja</h2>
                <h2>ali pa je bil odstranjen!</h2>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
    include_once './footer.php';
?>

<!-- remove a review -->
<script>
    $(document).on('click', '#delete', function() {

        if (confirm("Želite odstraniti komentar?")) {
            var rid = $(this).val();
            var ele = $(this).closest(".review");
            $.ajax({
                type:'POST',
                data: {'rid':rid},
                url: './src/inc/review-delete.inc.php',
                success: function(data) {
                    if(data == "OK") {
                        fetchReview(1);
                        alert("Komantar uspešno odstranjen!");
                    } else {
                        alert("Zgodila se je napaka pri odstranjevanju!");
                    }
                }
            });
        }
    });
</script>

<script>
    
    fetchReview(1);

    $(document).on('click', '#review-ins', function() {
            var pid = $(this).val();
            var rating= $("input:checked").val();
            var aid = $("#aid").val();
            var comment = $("#comment").val();
            $.ajax({
                type:'POST',
                data: {'pid':pid, 'aid':aid, 'rating':rating, 'comment':comment},
                url: './src/inc/review-insert.inc.php',
                success: function(data) {
                    if(data == "OK") {
                        fetchReview(1);
                        $('.rating').prop("checked", false);
                        $('#comment').val("");
                    } else {
                        alert("Zgodila se je napaka pri dodajanju komentarja!");
                    }
                }
            });
    });

    function fetchReview(page) {
        var action = 'fetchReview';
        var pid = $('#pid').val();
        var page = page;
        $.ajax({
            type: 'POST',
            data: {'action':action, 'pid':pid, 'page':page},
            url: "./fetch-review.inc.php",
            dataType: 'json',
            success: function(response) {
                $('.comment').html(response.output);
                $('.review-pagination').html(response.pagination);
            }

        });
    }

    $(document).on('click', '.page-link', function(){
        var page = $(this).data('page_number');
        fetchReview(page);
    });

</script>
