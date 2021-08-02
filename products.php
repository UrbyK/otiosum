<?php
    include_once './header.php';
?>
<!-- <script src="js/jquery-ui.js"></script> -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 filter-wrapper">
            <div class="list-group">
                <h3>Iskanje</h3>
                <input type="text" class="form-control" id="search" autocomplete="off" placeholder="Išči..."/>
            </div>
            <div class="list-group">
                <h3>Cena</h3>	
                <div class="list-group-item">
                    <input id="priceSlider" data-slider-id='ex1Slider' type="range" data-slider-min="1" data-slider-max="1000" data-slider-step="200" data-slider-value="1"/>
                    <div class="priceRange">1 - 1000</div>
                    <input type="hidden" id="minPrice" value="1" />
                    <input type="hidden" id="maxPrice" value="1000" />
                </div>
            </div>
            <div class="list-group list-group-flush">
                <h3>Znamke</h3>
                <div class="section">
                    <?php
                    $brands = brands();
                    foreach($brands as $brand): ?>
                    <div class="list-group-item checkbox">
                        <label><input type="checkbox" class="common-selector brand"  value="<?=$brand['id']?>"><?=$brand['title']?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row filter_data clearfix justify-content-center">

            </div>
        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>


<script>
$(document).ready(function(){

    filter_data();

    function filter_data()
    {
        $('.filter_data').html('<div id="loading" style="" ></div>');
        var action = 'fetch_data';
        var search = $('#search').val();
        var minimum_price = $('#minPrice').val();
        var maximum_price = $('#maxPrice').val();
        var brand = get_filter('brand');
        console.log(brand);
        $.ajax({
            url:"fetch_data.php",
            method:"POST",
            data:{action:action, search:search, brand:brand},
            success:function(data){
                $('.filter_data').html(data);
            }
        });
    }

    function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $('.common-selector').click(function(){
        filter_data();
    });

    $('#search').keyup(function(){
        filter_data();
    })

    // $('#priceRange').slider({
    //     range:true,
    //     min:1,
    //     max:1000,
    //     values:[1, 1000],
    //     step:10,
    //     stop:function(event, ui)
    //     {
    //         $('#priceRange').html(ui.values[0] + ' - ' + ui.values[1]);
    //         $('#minPrice').val(ui.values[0]);
    //         $('#maxPrice').val(ui.values[1]);
    //         filter_data();
    //     }
    // });

});
</script>