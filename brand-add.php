<?php
    include_once './header.php';
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
                <div class="col-sm">
                        <form method="post" id="image-form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-form-label" for="brand">Znamka:</label>
                                <input type="text" class="form-control" name="brand" id=brand placeholder="Znamka..." required>
                            </div>

                            <input type="file" name="img" class="file" accept="image/*" style="visibility: hidden;">
                            <div class="input-group my-1">
                                <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                                <div class="input-group-append">
                                    <button type="button" class="browse btn btn-primary">Išči...</button>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="col-sm">
                    <img src="https://placehold.it/80x80" id="preview" class="img-thumbnail" style="max-width:250px; max-height:250px;">
                </div>
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

<script>
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
</script>