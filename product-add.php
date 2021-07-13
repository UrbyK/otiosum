<?php

// function categoryTree($parent_id = 0, $sub_mark = '') {
//     $pdo = pdo_connect_mysql();
//     $stmt = $pdo->query("SELECT * FROM category WHERE parent_id = $parent_id ORDER BY category ASC");
//     if ($stmt->rowCount() > 0) {
//         $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         foreach ($row as $item) {
//             echo "<option value=".$item['id'].">".$sub_mark.$item['category']."</option>";
//             categoryTree($item['id'], $sub_mark.'-');
//         }
//     }

// }

?>

<?php 
    include_once './header.php';
?>

<div class="container">
    <div class="row justify-content-center h-100">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Vnesi nov izdelek</h1>
            </div>
                
            <div class="card-body">

                <?php if (isset($_GET['error'])):
                    include_once './src/inc/error.inc.php'; 
                    if (array_key_exists($_GET['error'], $errorList)): ?>
                        <div class="error w-100 text-center alert-danger">
                            <h3><?=$errorList[$_GET['error']]?></h3>
                        </div>
                    <?php else: ?>
                        <div class="error w-100 text-center alert-danger">
                            <h3>Zgodila se je neznana napaka. Prosim poskusite kasneje!</h3>
                        </div>
                    <?php endif; ?>
                <?php endif;
                if (isset($_GET['status']) && isset($_GET['status']) == "ok"):?>
                    <div class="error w-100 text-center alert-success ">
                        <h3>Izdelek uspešno vnesen!</h3>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" action="./src/inc/product-insert.inc.php">

                    <!-- product table -->
                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="title">Naslov izdelka:</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Izdelek..." maxlenght="150" required>
                    </div>

                    <!-- <div class="form-group">
                        <input type="file" id="img" name="img[]" class="file" accept="image/*" style="visibility: hidden;" multiple>
                        <div class="input-group my-1">
                            <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                            <div class="input-group-append">
                                <button type="button" class="browse btn btn-secondary">Išči...</button>
                            </div>
                        </div>
                        <div class="preview">
                        </div>
                    </div>
                     -->
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
                        <textarea class="form-control" name="summary" id="summary" placeholder="Povzetek izdelka..." maxlenght="255" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="description">Opis:</label>
                        <textarea class="form-control" name="description" id="description" placeholder="Opis..." rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="sku">SKU:</label>
                        <input type="text" class="form-control" name="sku" id="sku" placeholder="SKU..." maxlength="8" pattern="[A-z0-9]{8}" required>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="quantity">Količina:</label>
                        <input type="number" class="form-control hide-arrow" name="quantity" id="quantity" placeholder="Količina..." min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="price">Cena:</label>
                        <div class="input-group">
                            <input type="number" class="form-control hide-arrow" name="price" id="price" placeholder="Cena..." step="0.01" min="0" required>
                            <div class="input-group-append">
                                <div class="input-group-text">€</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col" for="publishDate">Dan objave izdelka:</label>
                        <input type="date" class="form-control" name="publishDate" id="publishDate">
                    </div>
                    <!-- measurement -->
                    <div class="form-row">
                        
                        <div class="form-group col-md-3 col-form-label text-left">
                            <label class="col" for="height">Višina:</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-center hide-arrow" name="height" id="height" min=0 step="0.01" placeholder="Višina...">
                                <div class="input-group-append">
                                    <div class="input-group-text">cm</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3 col-form-label text-left">
                            <label class="col" for="lenght">Dolžina:</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-center hide-arrow" name="lenght" id="lenght" min=0 step="0.01" placeholder="Dolžina...">
                                <div class="input-group-append">
                                    <div class="input-group-text">cm</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-form-label text-left">
                            <label class="col" for="width">Širina:</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-center hide-arrow" name="width" id="width" min=0 step="0.01" placeholder="Širina...">
                                <div class="input-group-append">
                                    <div class="input-group-text">cm</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-form-label text-left">
                            <label class="col" for="weight">Teža:</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-center hide-arrow" name="weight" id="weight" min=0 step="0.01" placeholder="Teža...">
                                <div class="input-group-append">
                                    <div class="input-group-text">kg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- category -->
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

                    <!-- brand -->
                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="Brand">Znamka:</label>
                        <select class="form-select" id="brand" name="brand" size="5" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                            <?php foreach(brands() as $brand):?>
                                <option value="<?=$brand['id']?>"><?=$brand['title']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

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

<script>
    $(document).on("click", ".browse", function() {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });
    $('#img').change(function(e) {
        // on a new insert reset "Upload here" text
        $("#file").val("");
        // empty preview div
        $("#preview").empty();
        // move through all the selected files
        for (let i = 0; i < e.target.files.length; i++) {

            var fileName = e.target.files[i].name;
            $("#file").val($("#file").val() + fileName + " ");

            var reader = new FileReader();
            reader.addEventListener("load", function() {
                var image = new Image();
                image.height = 150;
                image.title = fileName;
                image.src = this.result;
                preview.appendChild(image);
            });
            reader.readAsDataURL(e.target.files[i]);
        }
    });
</script>