<?php 
    include_once './header.php';
    if (!isLogin() && !isAdmin() && !isMod()) {
        exit("<script>window.location.href='index'</script>");
    }
?>

<?php if (isset($_GET['pid']) && !empty($_GET['pid'])) {
        $pdo = pdo_connect_mysql();
        $pid = $_GET['pid'];
        // get all relevant data 
        $sql = "SELECT * FROM product p LEFT JOIN measurement m ON p.id = m.product_id WHERE p.id = $pid";
        $stmt = $pdo->query($sql);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount() == 0) {
            exit("<script>window.location.href='product-list'</script>");
        }
    } else {
        exit("<script>window.location.href='product-list'</script>");
    }
?>


<div class="container">

    <!-- check if error is set in URL -->
    <?php if (isset($_GET['error'])):
        include_once './src/inc/error.inc.php';

        // check if given error exists in the error array  
        if (array_key_exists($_GET['error'], $errorList)): ?>
            <div class="alert w-100 mt-2 text-center alert-danger">
                <h3><?=$errorList[$_GET['error']]?></h3>
            </div>

        <!-- if given error does not exist in the error array  -->
        <?php else: ?>
            <div class="alert w-100 mt-2 text-center alert-danger">
                <h3>Zgodila se je neznana napaka. Prosim poskusite kasneje!</h3>
            </div>
        <?php endif; ?>
    <?php endif;

    // check if status is given in URL and if the status is success
    if (isset($_GET['status']) && isset($_GET['status']) == "success"):?>
        <div class="alert w-100 mt-2 text-center alert-success ">
            <h3>Izdelek uspešno vnesen!</h3>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center h-100">
        <div class="col-12">
            <div class="d-flex my-2 text-center">
                <!-- update next / previous product -->
                <div class="col-4 float-left">
                    <?php
                        // sql check if there is a previus recored id to current id
                        $sql = "SELECT id FROM product WHERE id = (SELECT max(id) FROM product WHERE id < $pid)";
                        $stmt = $pdo->query($sql);
                        if ($stmt->rowCount() != 0):
                            $prev = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <a class="p-2 btn btn-info text-center" style="width:180px;" href="./product-update?pid=<?=$prev['id']?>"><i class="fas fa-arrow-left"></i> Prejšnji izdelek</a>
                    <?php endif; ?>
                </div>

                <div class="col-4 text-center">
                    <a class="p-2 btn btn-info text-center" style="width:180px;" href="./product-list"><i class="fas fa-list"></i> Seznam izdelkov</a>
                </div>
                
                <div class="col-4 text-center">
                    <?php
                        // sql check if there is a next recored id to current id
                        $sql = "SELECT id FROM product WHERE id = (SELECT min(id) FROM product WHERE id > $pid)";
                        $stmt = $pdo->query($sql);
                        if ($stmt->rowCount() != 0):
                            $next = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                        <a class="p-2 btn btn-info text-center" style="width:180px;" href="./product-update?pid=<?=$next['id']?>">Naslednji izdelek <i class="fas fa-arrow-right"></i></a>
                    <?php endif; ?>
                </div>
            </div> <!-- d-flex my-2 text-center -->
            <div class="card my-2">
                <div class="card-header">
                    <h1>Posodobi izdelek</h1>
                </div>
                    
                <div class="card-body">

                <!--  action="./src/inc/product-update.inc.php" -->
                    <form method="post" enctype="multipart/form-data" action="" id="product-form">

                        <h3 class="subtitle">Osnovni podatki</h6>
                        <hr>
                        <!-- product table -->
                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="title">Naslov izdelka:</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Izdelek..." maxlength="150" required value="<?=$product['title']?>">
                            <input type="text" name="pid" id="pid" value="<?=$pid?>" hidden>
                        </div>

                        <!-- get all images for the given prtoduct ID -->
                        <?php
                            $sql = "SELECT * FROM product_image WHERE product_id = $pid";
                            $images = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="form-group">
                            <input type="file" id="img" name="img[]" class="file" accept="image/*" style="visibility: hidden;" multiple>
                            <div class="input-group my-1">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file" value="<?php foreach($images as $image): $data = explode('/', $image['image']);
                            $imageName = end($data);?><?=$imageName?> <?php endforeach; ?>">
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-secondary">Išči...</button>
                                </div>
                            </div>
                            <div id="preview">
                                <?php foreach($images as $image): ?>
                                    <img src="<?=$image['image']?>" style="height: 150px;">
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="summary">Povzetek:</label>
                            <textarea class="form-control" name="summary" id="summary" placeholder="Povzetek izdelka..." maxlength="255" rows="3"><?=$product['summary']?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="description">Opis:</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Opis..." rows="20"><?=$product['description']?></textarea>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="quantity">Količina:</label>
                            <input type="number" class="form-control hide-arrow" name="quantity" id="quantity" placeholder="Količina..." min="0" required value="<?=$product['quantity']?>">
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="price">Cena:</label>
                            <div class="input-group">
                                <input type="number" class="form-control hide-arrow" name="price" id="price" placeholder="Cena..." step="0.01" min="0" required value="<?=$product['price']?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">€</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col" for="publishDate">Dan objave izdelka:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="publishDate" id="publishDate" value="<?=$product['date_published']?>">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>

                        <h3 class="subtitle">Šifra izdelka</h6>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="sku">SKU:</label>
                            <input type="text" class="form-control" name="sku" id="sku" placeholder="SKU..." maxlength="8" pattern="[A-z0-9]{1,}" required value="<?=$product['sku']?>">
                        </div>

                        <h3 class="subtitle">Mere izdelka</h6>
                        <hr>
                        <!-- measurement -->
                        <div class="form-row">
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="height">Višina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="height" id="height" min=0 step="0.01" placeholder="Višina..." value="<?=$product['height']?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="length">Dolžina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="length" id="length" min=0 step="0.01" placeholder="Dolžina..." value="<?=$product['length']?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="width">Širina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="width" id="width" min=0 step="0.01" placeholder="Širina..." value="<?=$product['width']?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="weight">Teža:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="weight" id="weight" min=0 step="0.01" placeholder="Teža..." value="<?=$product['weight']?>">
                                    <div class="input-group-append">
                                        <div class="input-group-text">kg</div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- form-row -->

                        <?php
                            // get all sales id that are active for the product
                            $sql = "SELECT * FROM product_category pc INNER JOIN category c ON pc.category_id = c.id WHERE pc.product_id = $pid";
                            $categories = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                            // convert the 2d array to 1d array
                            $categories = array_values(array_column($categories, 'id'));
                        ?>

                        <!-- category -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="col-md-4 col-form-label text-left" for="category[]">Kategorije:</label>
                                <select class="form-select custom-select dropdown" id="category" name="category[]" multiple size="8" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                                    <?php categoryTree($cid = $categories); ?>
                                </select>
                                <!-- select all / unselect all buttons -->
                                <div class="form-group text-center my-2">
                                    <button type="button" id="selectAll" name="selectAll" class="btn btn-secondary" >Izberi vse</button>
                                    <button type="button" id="unselectAll" name="deselectAll" class="btn btn-secondary">Prekliči izbiro</button>
                                    <a type="button" class="btn btn-info" href="./category-add" target="_blank">Dodaj kategorijo</a>
                                </div>
                            </div>
                        </div>

                        <!-- brand -->
                        <div class="form-row col-4">
                            <div class="form-group">
                                <label class="col-md-4 col-form-label text-left" for="brand">Znamka:</label>
                                <select class="form-select" id="brand" name="brand" size="5" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                                    <?php foreach(brands() as $brand):
                                        if ($brand['id'] == $product['brand_id']): ?>
                                            <option value="<?=$brand['id']?>" selected><?=$brand['title']?></option>
                                        <?php else: ?>
                                            <option value="<?=$brand['id']?>"><?=$brand['title']?></option>
                                        <?php endif ?>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-group text-center my-2">
                                    <a type="button" class="btn btn-info my-2" href="./brand-add" target="_blank">Dodaj znamko</a>
                                </div>
                            </div>
                        </div>

                        <?php
                            // get all sales id that are active for the product
                            $sql = "SELECT s.id FROM product_sale ps INNER JOIN sale s ON ps.sale_id = s.id WHERE ps.product_id = $pid";
                            $discounts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                            // convert the 2d array to 1d array
                            $discounts = array_values(array_column($discounts, 'id'));
                        ?>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="col-12 col-form-label text-left" for="sale">Popusti:</label>
                                <select class="form-select" id="sale" name="sale[]" size="8" aria-label="multiple select size 3" style="width:100%" multiple>
                                    <?php foreach(sales() as $sale): ?>
                                        <?php if(in_array($sale['id'], $discounts)): ?> 
                                            <option value="<?=$sale['id']?>" selected><?=format_date("d.m.Y",$sale['date_start'])?> / <?=format_date("d.m.Y",$sale['date_end'])?> | <?=$sale['discount']?>%</option> 
                                        <?php else: ?>
                                            <option value="<?=$sale['id']?>"><?=format_date("d.m.Y",$sale['date_start'])?> / <?=format_date("d.m.Y",$sale['date_end'])?> | <?=$sale['discount']?>%</option>
                                        <?php endif; ?>                           
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-group text-center my-2">
                                <a type="button" class="btn btn-info" href="./sale" target="_blank">Dodaj popuste</a>
                                </div>
                            </div>
                        </div> <!-- form-row -->
                        <button id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Posodobi</button>
                        <!-- <input type="button" id="submit-btn" name="update" class="btn btn-primary float-right" value="Posodobi">                         -->
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>


<?php
    include_once './footer.php';
?>

<script>
    $("option").mousedown(function(e) {
        e.preventDefault();
        $(this).prop('selected', !$(this).prop('selected'));
    });
</script>

<script>
    $('#selectAll').click(function() {
    $('#category option').prop('selected', true);
    });

    $('#unselectAll').click(function() {
    $('#category option').prop('selected', false);
    });
</script>

<!-- image preview script -->
<script src="./src/js/image-upload-preview.js" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $("#product-form").submit(function(e) {
            e.preventDefault();

            if (confirm("Želite posodobiti podatke?")) {

                $.ajax({
                    type:'POST',
                    data: new FormData(this),
                    url: './src/inc/product-update.inc.php',
                    //dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        if (json.success == false) {
                            alert(json.message);
                        } else {
                            alert("Podatki uspešno spremenjeni!");
                        }
                        // move to top of page
                        window.scrollTo(0,0)
                    },
                });
            } else {
                alert("Niste posodobili!");
            }
        });
    });
</script>

<script type="text/javascript">
            bkLib.onDomLoaded(function() {
                new nicEditor({buttonList : ['bold',
      'italic',
      'underline',
      'left',
      'center',
      'right',
      'justify',
      'ol',
      'ul',
      'strikethrough',
      'removeformat',
      'hr',
      'forecolor',
      'bgcolor']}).panelInstance('description')
            });
</script>