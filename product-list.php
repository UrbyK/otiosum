<?php 
    include_once './header.php';

    if (!isLogin() && !isAdmin() && !isMod()) {
        exit("<script>window.location.href='index'</script>");
    }
    
    $pdo =pdo_connect_mysql();
    // number of list items per page
    $num_of_product_per_page = 5;
    // URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2 ...
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    // select products by latest
    $stmt = $pdo->prepare('SELECT * FROM product ORDER BY id DESC LIMIT ?,?');
    // bindValue allows us to use integer in SQL, need it for LIMIT
    $stmt->bindValue(1, ($current_page - 1) * $num_of_product_per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $num_of_product_per_page, PDO::PARAM_INT);
    $stmt->execute();
    // fetch and return products as ARRAY
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // total amount of products
    $total_products = $pdo->query('SELECT * FROM product')->rowCount();

    #$starts_from = ($current_page-1)*$num_of_product_per_page;

    $total_pages = ceil($total_products/$num_of_product_per_page);

?>


<div class="container">
    <div class="d-flex my-2 text-center">
        <!-- update next / previous product -->
        <div class="col-4 float-left">
            <a class="p-2 btn btn-info text-center" style="width:180px;" href="./product-add"><i class="fas fa-plus"></i> Dodaj izdelek</a>
        </div>
    </div> <!-- d-flex my-2 text-center -->
    <div class="card mb-3">
        <div class="card-header text-center">
            <h2 class="subtitle">Seznam izdelkov</h2>
        </div>
        <div class="row mx-2">
            <div class="col-md-12">     
                <div class="mt-3">
                    <ul class="list list-inline">
                        <?php foreach ($products as $product): ?>
                        <li class="d-flex justify-content-between border rounded mt-2">
                            <div class="d-flex flex-row align-items-center col-5">
                                <div class="ml-2 mt-2">
                                    <h5 class="mb-0"><b><?=$product['title']?></b></h5>
                                    <div class="d-flex ">
                                        <h6 class="ml-3">SKU: <?=$product['sku']?></h6>
                                        <h6 class="ml-3">CENA: <?=$product['price']?> €</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center col-5">
                                <?php foreach(productImages($product['id']) as $image): ?>
                                    <img src="<?=$image['image']?>" alt="<?=$image['caption']?>" class="col-3" style="height:75px;">
                                <?php endforeach;?>
                            </div>
                            <div class="d-flex flex-row align-items-center col-2">
                                <div class="d-flex flex-column mr-2">
                                    <!-- put buttons in line -->
                                    <div class="btn_group edit-button">
                                        <a href="./product-update.php?pid=<?=$product['id']?>" class="mx-1 btn btn-info">
                                            <i class="fas fa-wrench"></i>
                                        </a>
                                        <button id="delete" class="btn btn-danger" value="<?=$product['id']?>" name="pid">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div> 
    </div>
</div>

<?=pagination($page, $total_pages, $current_page)?>

<?php
    include_once './footer.php';
?>

<script>
    $(document).on('click', '#delete', function() {

        if (confirm("Želite odstraniti izdelek?")) {
            var pid = $(this).val();
            var ele = $(this).closest("li");
            $.ajax({
                type:'POST',
                data: {'pid':pid},
                url: './src/inc/product-delete.inc.php',
                success: function(data) {
                    if(data == "OK") {
                        alert("Izdelek uspešno odstranjen!");
                        ele.fadeOut().remove();
                    } else {
                        alert("Zgodila se je napaka pri odstranjevanju!");
                    }
                }
            });
        }
    });
</script>