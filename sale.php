<?php
    include_once './header.php';
    if (!isLogin() && !isAdmin() && !isMod()) {
        exit("<script>window.location.href='index'</script>");
    }
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
    <?php
        if (!isset($_GET['option']) || $_GET['option'] != "add" && $_GET['option'] != "modify" || empty($_GET['option'])):
    ?>
        <div class="text-center">
            <h2>Izberite kaj želite storiti:</h2>
            <div class="btn-group mb-2" style="width:180px">
                <a type="button" class="btn btn-info" href="./sale?option=add"><i class="fas fa-plus"></i> Dodaj popuste</a>
            </div>
            <div class="btn-group mb-2" style="width:180px">
                <a type="button" class="btn btn-info" href="./sale?option=modify"><i class="fas fa-wrench"></i> Spremeni popuste</a>
            </div>
        </div>
    <?php
        endif;

        if (isset($_GET['option']) && $_GET['option'] == "add"):
    ?>

    <div class="d-flex my-2 text-center">
        <!-- update next / previous product -->
        <div class="col-4 float-left">
            <a class="p-2 btn btn-info text-center" style="width:180px;" href="./sale"><i class="fas fa-list"></i> Nazaj</a>
        </div>

        <div class="col-4 text-center">
            <a class="p-2 btn btn-info text-center" style="width:180px;" href="./sale?option=modify"><i class="fas fa-wrench"></i> Spremeni popuste</a>
        </div>
    </div> <!-- d-flex my-2 text-center -->

    <div class="card">
        <div class="card-header">
            <h2>Dodaj obdobja razprodaje</h2>
        </div>
        <div class="card-body">
            <form id="sales" method="post" enctype="multipart/form-data" action="./src/inc/sale-insert.inc.php" >
                <!-- add more button -->
                <div class="d-flex justify-content-end mt-2">
                    <button class="add_form_field btn btn-success float-right"><i class="fa fa-plus"></i></button>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="start_date">Začetek popusta:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="start_date[]" id=start_date placeholder="Začetek...">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="end_date">Konec popusta:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="end_date[]" id=end_date placeholder="Konec...">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="discount">Popust:</label>
                        <div class="input-group">
                            <input type="number" class="form-control text-center hide-arrow" name="discount[]" id="discount" min=0 step="1" placeholder="Popust..." required>
                            <div class="input-group-append">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                </div> <!-- form-row -->
            </form> <!-- form -->
            <div class="d-flex justify-content-end mt-2">
                <button form="sales" id="submit-btn" type="submit" name="submit" class="btn btn-primary float-right">Shrani</button>
            </div>
        </div><!-- card-body -->
    </div> <!-- card -->
<script>
    $(document).ready(function() {

        var wrapper = $("form");
        var add_button = $(".add_form_field");

        $(add_button).click(function(e) {
            e.preventDefault();
            $(wrapper).append('<div class="form-row">\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="start_date">Začetek popusta:</label>\
                                        <div class="input-group">\
                                            <input type="date" class="form-control" name="start_date[]" id="start_date" placeholder="Začetek...">\
                                            <div class="input-group-append">\
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="end_date">Konec popusta:</label>\
                                        <div class="input-group">\
                                            <input type="date" class="form-control" name="end_date[]" id="end_date" placeholder="Konec...">\
                                            <div class="input-group-append">\
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="form-group col-md-3">\
                                        <label class="col-form-label" for="discount">Popust:</label>\
                                        <div class="input-group">\
                                            <input type="number" class="form-control text-center hide-arrow" name="discount[]" id="discount" min=0 step="1" placeholder="Popust..." required>\
                                            <div class="input-group-append">\
                                                <div class="input-group-text">%</div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="form-group col-md-1 align-self-center">\
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

<?php
    endif;
    if (isset($_GET['option']) && $_GET['option'] == "modify"):

        $pdo = pdo_connect_mysql();
        // number of list items per page
        $num_of_product_per_page = 10;
        // URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2 ...
        $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
        // select products by latest
        $stmt = $pdo->prepare('SELECT * FROM sale LIMIT ?,?');
        // bindValue allows us to use integer in SQL, need it for LIMIT
        $stmt->bindValue(1, ($current_page - 1) * $num_of_product_per_page, PDO::PARAM_INT);
        $stmt->bindValue(2, $num_of_product_per_page, PDO::PARAM_INT);
        $stmt->execute();
        // fetch and return products as ARRAY
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // total amount of products
        $total_sales = $pdo->query('SELECT * FROM sale')->rowCount();
    
        #$starts_from = ($current_page-1)*$num_of_product_per_page;
    
        $total_pages = ceil($total_sales/$num_of_product_per_page);

        // $pdo = pdo_connect_mysql();
        // $query = "SELECT * FROM sale ORDER BY discount ASC, date_start ASC, date_end ASC";
        // $stmt = $pdo->query($query);
        // $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="d-flex my-2 text-center">
        <!-- update next / previous product -->
        <div class="col-4 float-left">
            <a class="p-2 btn btn-info text-center" style="width:180px;" href="./sale"><i class="fas fa-list"></i> Nazaj</a>
        </div>

        <div class="col-4 text-center">
            <a class="p-2 btn btn-info text-center" style="width:180px;" href="./sale?option=add"><i class="fas fa-plus"></i> Dodaj popuste</a>
        </div>
    </div> <!-- d-flex my-2 text-center -->
    <div class="card">
        <div class="card-header text-center">
            <h2>Posodobi popuste</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">     
                    <div class="mt-3">
                        <ul class="list list-inline">
                        <?php foreach ($sales as $sale): ?>
                            <li class="d-flex justify-content-between border rounded mt-2">
    
                                <div class="d-flex flex-row align-items-center col-4">
                                    <div class="form-group col">
                                        <label class="col-form-label" for="startDate">Začetek popusta:</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="startDate" id="startDate" data-original-value="<?=$sale['date_start']?>" value="<?=$sale['date_start']?>" disabled>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="d-flex flex-row align-items-center col-4">
                                    <div class="form-group col">
                                        <label class="col-form-label" for="endDate">Konec popusta:</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="endDate" id="endDate" data-original-value="<?=$sale['date_end']?>" value="<?=$sale['date_end']?>" disabled>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="d-flex flex-row align-items-center col-2">
                                    <div class="form-group col">
                                        <label class="col-form-label" for="discount">Popust:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control hide-arrow" name="discount" id="discount" data-original-value="<?=$sale['discount']?>" value="<?=$sale['discount']?>" disabled required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-percentage"></i></div>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                <div class="d-flex flex-row align-items-center col-2">
                                    <div class="d-flex flex-column mr-2">
                                        <!-- put buttons in line -->
                                        <div class="btn_group edit-button">
                                            <button id="update" class="btn btn-info" value="<?=$sale['id']?>" name="sid">
                                                    <i class="fas fa-wrench"></i>
                                            </button>
                                            <button id="save" class="btn btn-success" value="<?=$sale['id']?>" name="sid" hidden disabled>
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button id="delete" class="btn btn-danger" value="<?=$sale['id']?>" name="sid">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    <?=pagination($page, $total_pages, $current_page)?>

    <!-- script to delete sales -->
<script>
    $(document).on('click', '#delete', function() {

        if (confirm("Želite odstraniti izdelek?")) {
            var sid = $(this).val();
            var ele = $(this).closest("li");
            $.ajax({
                type:'POST',
                data: {'sid':sid},
                url: './src/inc/sale-delete.inc.php',
                success: function(data) {
                    if(data == "OK") {
                        alert("Popust uspešno odstranjen!");
                        ele.fadeOut().remove();
                        location.reload(true);
                    } else {
                        alert("Zgodila se je napaka pri odstranjevanju!");
                    }
                }
            });
        } else {

        }
    });
</script>

<!-- script to update sales -->
<script>
    $(document).on('click', '#update', function() {
            // enable input fields
            $( "#discount" ).prop( "disabled", false );
            $( "#startDate" ).prop( "disabled", false );
            $( "#endDate" ).prop( "disabled", false );
            $( "#save" ).prop({"disabled": false, "hidden":false});
            $( "#update" ).prop({"disabled": true, "hidden":true});
    });

    $(document).on('click', '#save', function() {
        if (confirm("Želite shraniti popust?")) {
            // get current input fields values
            
            var sid = $(this).val();
            var discount = $("#discount").val();
            var startDate = $("#startDate").val();
            var endDate = $("#endDate").val();
            console.log(sid, discount, startDate, endDate);

            $.ajax({
                type:'POST',
                data: {'sid':sid, 'discount':discount, 'startDate':startDate, 'endDate':endDate},
                url: './src/inc/sale-update.inc.php',
                success: function(data) {
                    if(data == "OK") {
                        alert("Izdelek uspešno posodobljen!");
                        location.reload();
                    } else {
                        alert("Zgodila se je napaka pri odstranjevanju!");
                    }
                }
            });
            
        } else {
            $("#discount").val($("#discount").data("original-value"));
            $("#startDate").val($("#startDate").data("original-value"));
            $("#endDate").val($("#endDate").data("original-value"));
        }
        // disable input fields
        $( "#discount" ).prop( "disabled", true );
        $( "#startDate" ).prop( "disabled", true );
        $( "#endDate" ).prop( "disabled", true );
        $( "#save" ).prop({"disabled":true, "hidden":true});
        $( "#update" ).prop({"disabled":false, "hidden":false});
    });
</script>

<?php endif; ?>

</div> <!-- container -->
<?php
    include_once './footer.php';
?>
