<?php
    include_once './header.php';

    if (!isLogin() && !isAdmin() && !isMod()) {
        exit("<script>window.location.href='index'</script>");
    }

?>

<!-- <div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h2>Vnesi znamko</h2>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="custom-file">
                <input type="file" class="form-control-file" onchange="imagePreview(this)" id="customFile" accept="image/jpeg, image/png" placeholder="Išči">
                <!-- <label class="custom-file-label" for="customFile">Izberite slike</label> -->
                <!-- <img id="preview" class="rounded img-thumbnail">
            </div>
        </form>
    </div>
</div> -->

<div class="container">
    <div class="row align-items-center h-100">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="card-title">Vnesi znamko</h2>
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
                            <h3>Znamka uspešno vnesena!</h3>
                        </div>
                    <?php endif; ?>

                    <form method="post" id="image-form" enctype="multipart/form-data" action="./src/inc/brand-insert.inc.php">
                        <div class="form-group">
                            <label class="col-form-label" for="brand">Znamka:</label>
                            <input type="text" class="form-control" name="brand" id=brand placeholder="Znamka..." required>
                        </div>

                        <!-- <input type="file" name="img" class="file" accept="image/*" style="visibility: hidden;" required>
                        <div class="input-group my-1">
                            <input type="text" class="form-control" disabled placeholder="Upload File" id="file" required>
                            <div class="input-group-append">
                                <button type="button" class="browse btn btn-secondary">Išči...</button>
                            </div>
                        </div>
                        <div class="preview"></div> -->

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

                <!-- <div class="col-sm">
                    <img src="https://placehold.it/80x80" id="preview" class="img-thumbnail" style="max-width:250px; max-height:250px;">
                </div> -->
            </div>
        </div>
    </div>
</div>


<?php
    include_once './footer.php';
?>

<!-- <script>
    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result)
                    .width(200)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script> -->

<!-- <script>
    $(document).on("click", ".browse", function() {
        var file = $(this).parents().find(".file");
        file.trigger("click");
    });
    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $("#file").val(fileName);

        var reader = new FileReader();
        reader.onload = function(e) {
            // get loaded data and render thumbnail.
            document.getElementById("preview").src = e.target.result;
        };
        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    });
</script> -->

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