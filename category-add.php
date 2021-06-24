
<?php
    include_once "./header.php";
    include_once "./functions.php";
?>

<div class="container">

        <!-- Notice for success/fail on insert -->
        <?php 
            if(isset($_GET['status'])):
                if ($_GET['status'] == "error"): ?>
                    <div class="error w-100 text-center alert-danger mt-3 rounded">
                        <p>Zgodila se je napaka pri vnosu podatkov. Prosimo poskusite kasneje!</p>
                    </div>
                
                <?php elseif ($_GET['status'] == "success"): ?>
                    <div class="error w-100 text-center alert-success mt-3 rounded">
                        <p>Kategorije so bile uspešno vnesene!</p>
                    </div>
                <?php endif;
            endif;
        ?>

    <div class="card mt-3">
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
                        <label class="col-form-label" for="parent_category">Nad kategorija</label>
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
            $(wrapper).append('<div class="form-row">\
                                    <div class="form-group col-md-5">\
                                        <label class="col-form-label" for="category">Kategorija:</label>\
                                        <input type="text" class="form-control" name="category[]" id=category placeholder="Kategorija..." required>\
                                    </div>\
                                    <div class="form-group col-md-5">\
                                        <label class="col-form-label" for="parent_category">Nad kategorija</label>\
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
