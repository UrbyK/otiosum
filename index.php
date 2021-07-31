<?php
    include_once './header.php';

    $images = productImages(221);
    echo "<pre>";
    print_r($images);
    echo "</pre>";
    $len = count($images);
?>
<div class="container">
    <div id="carouselControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <?php for ($i=0; $i < $len; $i++):?>
            <?php if($i==0): ?>
            <div class="carousel-item active">
                <img src="<?=$images[$i]["image"]?>" class="d-block w-100" alt="...">
            </div>
            <?php else: ?>
            <div class="carousel-item">
                <img src="<?=$images[$i]["image"]?>" class="d-block w-100" alt="...">
            </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    </div>
</div>
<?php
    include_once './footer.php';
?>
