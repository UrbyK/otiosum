
<?php 
    include_once './header.php';
?>

<div class="container">
        <div class="row mt-3">
            <div class="col-md-12">     
                <div class="mt-3">
                    <ul class="list list-inline">
                    <?php foreach (products() as $product): ?>
                        <li class="d-flex justify-content-between border rounded mt-2">
                            <div class="d-flex flex-row align-items-center">
                                <div class="ml-2">
                                    <h5 class="mb-0"><?=$product['title']?></h5>
                                    <h6 class="ml-3">SKU: <?=$product['sku']?></h6>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <?php foreach(productImages($product['id']) as $image): ?>
                                    <img src="<?=$image['image']?>" alt="<?=$image['caption']?>" class="rounded-circle" style="height:75px;">
                                <?php endforeach;?>
                                <div class="ml-2">
                                    <h6 class="mb-0"><?=$product['title']?></h6>
                                    <div class="d-flex flex-row mt-1 text-black-50 date-time">
                                        <div>
                                            <i class="fa fa-calendar-o"></i><span class="ml-2">22 May 2020 11:30 PM</span>
                                        </div>
                                        <div class="ml-3">
                                            <i class="fa fa-clock-o"></i><span class="ml-2">6h</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row align-items-center">
                                <div class="d-flex flex-column mr-2">
                                    <!-- put buttons in line -->
                                    <div class="btn_group edit-button">
                                        <a href="./product-add.php?pid=<?=$product['id']?>" class="mx-1">
                                            <button class="btn btn-info">
                                                <i class="fas fa-wrench"></i>
                                            </button>
                                        </a>
                                        <button id="delete" class="btn btn-danger" value="<?=$product['id']?>" name="pid ">
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
        } else {

        }
    });
</script>