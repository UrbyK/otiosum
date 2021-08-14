<?php
    include_once './header.php';
    $o = "";
    if(isset($_GET['o']) && $_GET['o'] == "list") {
        $o = $_GET['o'];
    }
?>
<div class="container">
    <input type="hidden" value="<?=$o?>" id="list">
    <div class="row my-2">
        <div class="col">
            <select name="numberOfItems" id="numberOfItems" class="btn ">
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="18">18</option>
                <option value="24">24</option>
                <option value="30">30</option>
            </select>
        </div>
        <div class="col">
            <select id="dateOrder" class="form-control" name="dateOrder"required style="width: 250px;">
                <option value="down" selected> Datum padajoče</option>
                <option value="up">Datum naraščujoče</option>
            </select>
        </div>
        <div class="col">
            <?php $pdo = pdo_connect_mysql();
            $orderStatus = $pdo->query("SELECT * FROM order_status")->fetchAll(PDO::FETCH_ASSOC);?>
            <select id="orderStatusFilter" class="form-control" name="orderStatusFilter"required style="width: 250px;">
                <option value="" selected>--N/A--</option>
                <?php foreach($orderStatus as $status): ?>
                <option value="<?=$status['id']?>"><?=$status['status']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <input type="text" class="form-control" id="search" autocomplete="off" placeholder="Šifra..."/>
        </div>
    </div>
    <div class="row filter-data clearfix justify-content-center">

    </div>  <!-- filter_data -->
    <div class="row filter-pagination justify-content-center text-center">

    </div>
</div>
<?php
    include_once './footer.php'
?>

<script>
    $(document).ready(function() {

        filter_data(1);
        function filter_data(page) {
            var sendData = {
                action: 'fetchData',
                limit: $('#numberOfItems').val(),
                page: page,
                search: $('#search').val(),
                orderStatus: $('#orderStatusFilter').val(),
                dateOrder: $('#dateOrder').val(),
                o: $('#list').val(),
            };
            console.log(sendData);
            $.ajax({
                url: "./fetch-order.inc.php",
                method: "POST",
                data: sendData,
                dataType: "JSON",
                success:function(response) {
                    $('.filter-data').html(response.output);
                    $('.filter-pagination').html(response.pagination);
                }
            });
        }

        $(document).on('click', '.page-link', function(){
            var page = $(this).data('page_number');
            // var query = $('#search_box').val();
            filter_data(page);
        });

        $('#numberOfItems').change(function() {
            filter_data(1);
        });

        $('#orderStatusFilter').change(function() {
            filter_data(1);
        });

        $('#dateOrder').change(function() {
            filter_data(1);
        });

        $('#search').keyup(function() {
        filter_data(1);
        });

        $(document).on('change', '#orderStatus', function() {
            var sendData = {
                action: 'updateOrder',
                orderStatus: $('#orderStatus').val(),
                oid: $(this).find(':selected').data('oid'),
            };
            console.log(sendData);
            $.ajax({
                url: "./fetch-order.inc.php",
                method: "POST",
                data: sendData,
                // dataType: "JSON",
                success:function(response) {
                    if(response == "err") {
                        alert("Zgodila se je napaka!");
                    } else {
                        alert("Stanje naročila " + sendData['oid'] + " uspešno spremenjeno!");
                        filter_data(1);
                    }

                }
            });
        });

    
    });

</script>