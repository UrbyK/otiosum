<?php
    include_once './header.php';
?>

<div class="container">
    <!-- Notice for success/fail on insert -->
    <?php 
        if (isset($_GET['error'])):
            include_once './src/inc/error.inc.php';
            if (array_key_exists($_GET['error'], $errorList)): ?>
                <div class="error w-100 mt-3 text-center alert-danger rounded">
                    <h3><?=$errorList[$_GET['error']]?></h3>
                </div>
            <?php endif; 
        endif;    
        ?>
        <?php if (isset($_GET['status']) && isset($_GET['status']) == "success"):?>
            <div class="error w-100 mt-3 text-center alert-success rounded">
                <h3>Popust uspešno vnesen!</h3>
            </div>        
        <?php endif;    
        if (isset($_GET['warning'])): ?>
            <div class="error w-100 mt-3 text-center alert-warning rounded">
                <p><?=$_GET['warning']?> popustov ni bilo možno vnesti!</p>
            </div>
        <?php endif;
    ?>

<div class="card">
        <div class="card-header">
            <h2>Dodaj obdobja razprodaje</h2>
        </div>

        <div class="card-body">




            <form id="sales" method="post" enctype="multipart/form-data" action="./src/inc/sale-ins.inc.php" >
                <!-- add more button -->
                <div class="d-flex justify-content-end mt-2">
                    <button class="add_form_field btn btn-success float-right"><i class="fa fa-plus"></i></button>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="start_date">Začetek popusta:</label>
                        <input type="date" class="form-control" name="start_date[]" id=start_date placeholder="Začetek...">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="end_date">Konec popusta:</label>
                        <input type="date" class="form-control" name="end_date[]" id=end_date placeholder="Konec...">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="discount">Popusta:</label>
                        <input type="number" class="form-control" name="discount[]" id=discount placeholder="Popust..." required>
                    </div>
                </div> <!-- form-row -->
            </form> <!-- form -->
            <div class="d-flex justify-content-end mt-2">
                <button form="sales" id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Shrani</button>
            </div>

        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>


<script>
    $(document).ready(function() {

        var wrapper = $("form");
        var add_button = $(".add_form_field");

        $(add_button).click(function(e) {
            e.preventDefault();
            $(wrapper).append('<div class="form-row align-items-center">\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="start_date">Začetek popusta:</label>\
                                        <input type="date" class="form-control" name="start_date[]" id=start_date placeholder="Začetek..." >\
                                    </div>\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="end_date">Konec popusta:</label>\
                                        <input type="date" class="form-control" name="end_date[]" id=end_date placeholder="Konec..." >\
                                    </div>\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="discount">Popusta:</label>\
                                        <input type="number" class="form-control" name="discount[]" id=discount placeholder="Popust..." >\
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
