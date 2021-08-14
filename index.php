<?php
    include_once './header.php';
    $pdo = pdo_connect_mysql();
?>

<div class="container-fluid">
    <?php
        $query = "SELECT * FROM product WHERE date_published <= CURDATE() ORDER BY date_published DESC, id DESC LIMIT 6";
        $newProducts = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="product-group card my-3">
        <h2 class="align-items-center mx-3 border-bottom">Novi izdelki</h2>
        <div class="row">
            <?php foreach($newProducts as $product) {
                echo productCard($product);
            } ?>
        </div>
    </div>

    <?php
        $query = "SELECT p.*, AVG(r.rating) AS ratings FROM product p INNER JOIN review r ON p.id = r.product_id GROUP BY p.id ORDER BY ratings DESC LIMIT 6";
        $bestRated = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        if($bestRated):
    ?>
    <div class="product-group card my-3">
        <h2 class="align-items-center mx-3 border-bottom">Najbolje ocenjeni</h2>
        <div class="row">
            <?php foreach($bestRated as $product) {
                echo productCard($product);
            } ?>
        </div>
    </div>
    <?php endif; ?>

    <?php
        $query = "SELECT  DISTINCT p.* FROM product p INNER JOIN product_sale ps ON p.id = ps.product_id INNER JOIN sale s ON ps.sale_id = s.id WHERE s.date_start <= CURDATE() AND s.date_end >= CURDATE() ORDER BY s.date_start DESC LIMIT 6";
        $productsOnSale = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        if($productsOnSale):
    ?>
    <div class="product-group card my-3">
        <h2 class="align-items-center mx-3 border-bottom">Novo na razprodaji</h2>
        <div class="row">
            <?php foreach($productsOnSale as $product) {
                echo productCard($product);
            } ?>
        </div>
    </div>
    <?php endif; ?>


</div>

<?php
    include_once './footer.php';
?>
