<?php 
    include_once './header.php';

    if (!isLogin() && !isAdmin()) {
        exit("<script>window.location.href='index'</script>");
    }

?>

<div class="container">

    <!-- check if error is set in URL -->
    <?php if (isset($_GET['error'])):
        include_once './src/inc/error.inc.php';

        // check if given error exists in the error array  
        if (array_key_exists($_GET['error'], $errorList)): ?>
            <div class="error w-100 mt-2 text-center alert-danger">
                <h3><?=$errorList[$_GET['error']]?></h3>
            </div>

        <!-- if given error does not exist in the error array  -->
        <?php else: ?>
            <div class="error w-100 mt-2 text-center alert-danger">
                <h3>Zgodila se je neznana napaka. Prosim poskusite kasneje!</h3>
            </div>
        <?php endif; ?>
    <?php endif;

    // check if status is given in URL and if the status is success
    if (isset($_GET['status']) && isset($_GET['status']) == "success"):?>
        <div class="error w-100 mt-2 text-center alert-success ">
            <h3>Izdelek uspešno vnesen!</h3>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center h-100">
        <div class="col-12">
            <div class="d-flex my-2 text-center">
                <div class="col-4 float-left">
                    <a class="p-2 btn btn-info text-center" style="width:180px;" href="./product-list"><i class="fas fa-list"></i> Seznam izdelkov</a>
                </div>
            </div> <!-- d-flex my-2 text-center -->
            <div class="card">
                <div class="card-header">
                    <h1>Vnesi nov izdelek</h1>
                </div>
                    
                <div class="card-body">

                    <form method="post" enctype="multipart/form-data" action="./src/inc/product-insert.inc.php">

                        <h3 class="subtitle">Osnovni podatki</h6>
                        <hr>
                        <!-- product table -->
                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="title">Naslov izdelka:</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Izdelek..." maxlength="150" required <?php if (isset($_GET['error'], $_GET['title']) && !empty($_GET['title'])): ?> value="<?=$_GET['title']?>" <?php endif; ?>>
                        </div>

                        <div class="form-group">
                            <input type="file" id="img" name="img[]" class="file" accept="image/*" style="visibility: hidden;" multiple>
                            <div class="input-group my-1">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-secondary">Išči...</button>
                                </div>
                            </div>
                            <div id="preview"></div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="summary">Povzetek:</label>
                            <textarea class="form-control" name="summary" id="summary" placeholder="Povzetek izdelka..." maxlength="255" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="description">Opis:</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Opis..." rows="5"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="quantity">Količina:</label>
                            <input type="number" class="form-control hide-arrow" name="quantity" id="quantity" placeholder="Količina..." min="0" required <?php if (isset($_GET['error'], $_GET['quantity']) && !empty($_GET['quantity'])): ?> value="<?=$_GET['quantity']?>" <?php endif; ?>>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="price">Cena:</label>
                            <div class="input-group">
                                <input type="number" class="form-control hide-arrow" name="price" id="price" placeholder="Cena..." step="0.01" min="0" required <?php if (isset($_GET['error'], $_GET['price']) && !empty($_GET['price'])): ?> value="<?=$_GET['price']?>" <?php endif; ?>>
                                <div class="input-group-append">
                                    <div class="input-group-text">€</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col" for="publishDate">Dan objave izdelka:</label>
                            <input type="date" class="form-control" name="publishDate" id="publishDate" <?php if (isset($_GET['error'], $_GET['date']) && !empty($_GET['date'])): ?> value="<?=$_GET['date']?>" <?php endif; ?>>
                        </div>

                        <h3 class="subtitle">Šifra izdelka</h6>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-4 col-form-label text-left" for="sku">SKU:</label>
                            <input type="text" class="form-control" name="sku" id="sku" placeholder="SKU..." maxlength="8" pattern="[A-z0-9]{1,}" required <?php if (isset($_GET['error'], $_GET['sku']) && !empty($_GET['sku'])): ?> value="<?=$_GET['sku']?>" <?php endif; ?>>
                        </div>

                        <h3 class="subtitle">Mere izdelka</h6>
                        <hr>
                        <!-- measurement -->
                        <div class="form-row">
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="height">Višina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="height" id="height" min=0 step="0.01" placeholder="Višina..." <?php if (isset($_GET['error'], $_GET['height']) && !empty($_GET['height'])): ?> value="<?=$_GET['height']?>" <?php endif; ?>>
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="length">Dolžina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="length" id="length" min=0 step="0.01" placeholder="Dolžina..." <?php if (isset($_GET['error'], $_GET['length']) && !empty($_GET['length'])): ?> value="<?=$_GET['length']?>" <?php endif; ?>>
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="width">Širina:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="width" id="width" min=0 step="0.01" placeholder="Širina..." <?php if (isset($_GET['error'], $_GET['width'])): ?> value="<?=$_GET['width']?>" <?php endif; ?>>
                                    <div class="input-group-append">
                                        <div class="input-group-text">cm</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-form-label text-left">
                                <label class="col" for="weight">Teža:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control text-center hide-arrow" name="weight" id="weight" min=0 step="0.01" placeholder="Teža..." <?php if (isset($_GET['error'], $_GET['weight'])): ?> value="<?=$_GET['weight']?>" <?php endif; ?>>
                                    <div class="input-group-append">
                                        <div class="input-group-text">kg</div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- form-row -->

                        <!-- category -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="col-md-4 col-form-label text-left" for="category[]">Kategorije:</label>
                                <select class="form-select custom-select" id="category" name="category[]" multiple size="8" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                                    <?php categoryTree(); ?>
                                </select>
                                <!-- select all / unselect all buttons -->
                                <div class="form-group">
                                    <button type="button" id="selectAll" name="selectAll" class="btn btn-secondary" >Izberi vse</button>
                                    <button type="button" id="unselectAll" name="deselectAll" class="btn btn-secondary">Prekliči izbiro</button>
                                </div>
                            </div>
                        </div>

                        <!-- brand -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="col-md-4 col-form-label text-left" for="brand">Znamka:</label>
                                <select class="form-select" id="brand" name="brand" size="5" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                                    <?php foreach(brands() as $brand):?>
                                        <option value="<?=$brand['id']?>"><?=$brand['title']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="col-12 col-form-label text-left" for="sale">Popusti:</label>
                            <select class="form-select" id="sale" name="sale[]" size="8" aria-label="multiple select size 3" style="width:100%" multiple>
                                <?php foreach(sales() as $sale):?>
                                    <option value="<?=$sale['id']?>"><?=format_date("d.m.Y", $sale['date_start'])?> / <?=format_date("d.m.Y", $sale['date_end'])?> | <?=$sale['discount']?>%</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div> <!-- form-row -->
                    <button id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Dodaj</button>
                    </form>
                </div>

                <div class="card-footer">
                    <p>FOOTER</p>
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