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
        <div class="col-12 card">

            <div class="card-header">
                <h1>Vnesi nov izdelek</h1>
            </div>
                
            <div class="card-body">
                <form method="post" action="./src/inc/product-insert.inc.php">

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="title">Naslov izdelka:</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Izdelek..." maxlenght="150" required>
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
                        <input type="text" class="form-control" name="sku" id="sku" placeholder="SKU..." maxlength="8" pattern="[a-Z0-9]{8}">
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="quantity">Količina:</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Količina..." min="0" value="0">
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 col-form-label text-left" for="price">Cena:</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="Cena..." step="0.01" min="0" value="0">
                    </div>
                    <div class="form-group">
                        <select class="form-select" id="category" name="category[]" multiple size="10" aria-label="multiple select size 3" style="min-width:300px;width:100%;">
                            <?php categoryTree(); ?>
                        </select>
                        <div class="form-group">
                            <button id="select-all" name="select-all" class="btn btn-secondary">Izberi vse</button>
                            <button id="deselect-all" name="deselect-all" class="btn btn-secondary">Prekliči izbiro</button>
                         </div>
                    </div>

                    <button id="submit-btn" type="submit" name="submit" class="btn btn-primary">Dodaj</button>
                    
                </form>
            </div>

            <div class="card-footer">
                <p>FOOTER</p>
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