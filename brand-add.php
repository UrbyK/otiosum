<?php
    include_once './header.php';

    if (!isLogin() && !isAdmin() && !isMod()) {
        exit("<script>window.location.href='index'</script>");
    }

?>

<div class="container">
    <div class="row align-items-center h-100">
        <div class="col-12">
        
        <!-- check if error is set in URL -->
            <?php if (isset($_GET['error'])):
                include_once './src/inc/error.inc.php';
                // check if given error exists as key in error array
                if (array_key_exists($_GET['error'], $errorList)): ?>
                    <div class="error w-100 mt-2 text-center alert-danger rounded">
                        <h3><?=$errorList[$_GET['error']]?></h3>
                    </div>
                <!-- if error key does not exist in error array display uknown erro -->
                <?php else: ?>
                    <div class="error w-100 mt-2 text-center alert-danger rounded">
                        <h3>Zgodila se je neznana napaka. Prosim poskusite kasneje!</h3>
                    </div>
                <?php endif; ?>
            <?php endif;
            // check if status is set and contains
            if (isset($_GET['status']) && isset($_GET['status']) == "success"):?>
                <div class="error w-100 mt-2 text-center alert-success rounded">
                    <h3>Znamka uspešno vnesena!</h3>
                </div>
            <?php endif; ?>
        
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="card-title">Vnesi znamko</h2>
                </div>
                <div class="card-body">
                    <form method="post" id="image-form" enctype="multipart/form-data" action="./src/inc/brand-insert.inc.php">
                        <div class="form-group">
                            <label class="col-form-label" for="brand">Znamka:</label>
                            <input type="text" class="form-control" name="brand" id=brand placeholder="Znamka..." required>
                        </div>

                        <div class="form-group">
                            <input type="file" id="img" name="img" class="file" accept="image/*" style="visibility: hidden;" required>
                            <div class="input-group my-1">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file" required>
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-secondary">Išči...</button>
                                </div>
                            </div>
                            <div id="preview"></div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button form="image-form" id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Shrani</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>

<!-- image preview script -->
<script src="./src/js/image-upload-preview.js" crossorigin="anonymous"></script>