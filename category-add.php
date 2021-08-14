
<?php
    include_once "./header.php";

    if (!isLogin() && !isAdmin()) {
        exit("<script>window.location.href='index'</script>");
    }
?>

<div class="container">

    <!-- Notice for success/fail on insert -->
    <!-- check if error is set in url -->
    <?php if (isset($_GET['error'])):
        include_once './src/inc/error.inc.php';
        // check if given error exists in the error array 
        if (array_key_exists($_GET['error'], $errorList)): ?>
            <div class="alert w-100 mt-2 text-center alert-danger rounded">
                <h3><?=$errorList[$_GET['error']]?></h3>
            </div>
    
            <!-- if given error does not exist in the error array  -->
        <?php else: ?>
            <div class="alert w-100 mt-2 text-center alert-danger rounded">
                <h3>Zgodila se je neznana napaka. Prosim poskusite kasneje!</h3>
            </div>
        <?php endif; ?>
    <?php endif;

    // check if status is given in URL and if the status is success
    if (isset($_GET['status']) && isset($_GET['status']) == "success"):?>
        <div class="alert w-100 mt-2 text-center alert-success rounded">
            <h3>Kategorije uspešno vnesene!</h3>
        </div>
    <?php endif; ?>

    <div class="card mt-3">
        <div class="card-header text-center">
            <h2>Dodaj kategorije</h2>
        </div>
        <div class="card-body">
            <!-- add button -->
            <div class="d-flex justify-content-end mt-2">
                <button class="add_form_field btn btn-success float-right"><i class="fa fa-plus"></i></button>
            </div>

            <form id="ins-cat" method="post" enctype="multipart/form-data" action="./src/inc/category-insert.inc.php" >

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label class="col-form-label" for="category">Kategorija:</label>
                        <input type="text" class="form-control" name="category[]" id=category placeholder="Kategorija..." required>
                    </div>
                    <div class="form-group col-md-5">            
                        <label class="col-form-label" for="parent_category">Nadkategorija</label>
                        <select class="form-control" id="parent_category" name="parent_category[]">
                            <option selected value=""> ---N/A--- </option>
                            <?=categoryTree()?>
                        </select>
                    </div>
                </div> <!-- form-row -->
            </form> <!-- form -->

            <!-- submit form button -->
            <div class="d-flex justify-content-end mt-2">
                <button form="ins-cat" id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Shrani</button>
            </div>

        </div><!-- card-body -->
    </div> <!-- card -->
</div> <!-- container -->

<?php
    include_once "./footer.php";
?>

<script>
    $(document).ready(function() {

        var wrapper = $("form");
        var add_button = $(".add_form_field");

        $(add_button).click(function(e) {
            e.preventDefault();
            $(wrapper).append('<div class="form-row align-items-center">\
                                    <div class="form-group col-md-5">\
                                        <label class="col-form-label" for="category">Kategorija:</label>\
                                        <input type="text" class="form-control" name="category[]" id=category placeholder="Kategorija..." required>\
                                    </div>\
                                    <div class="form-group col-md-5">\
                                        <label class="col-form-label" for="parent_category">Nadkategorija</label>\
                                        <select class="form-control" id="parent_category" name="parent_category[]">\
                                            <option selected value="0"> ---N/A--- </option>\
                                            <?=categoryTree()?>\
                                        </select>\
                                    </div>\
                                    <div class="form-group col-md-1">\
                                        <a href="#" class="delete btn btn-danger"><i class="fa fa-minus"></i></a>\
                                    </div>\
                                </div>');
        });

        $(wrapper).on("click", ".delete", function(e) {
        e.preventDefault();
        $(this).closest('.form-row').remove();

        });
    });
</script>
