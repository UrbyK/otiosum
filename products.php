<?php
    include_once './header.php';
    if(isset($_GET['cid']) && !empty($_GET['cid'])) {
        $cid = $_GET['cid'];
    } else {
        $cid = null;
    }
    $prices = minMaxPrice($cid);
?>

<!-- slider inport js and css -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  
<div class="container-fluid">
    <div class="row min-vh-100">
        <div class="col-xl-2 col-md-3 filter-wrapper text-center">
            
            <button class="btn btn-filter w-100" type="button" data-toggle="collapse" data-target="#side-nav" aria-expanded="true" aria-controls="side-nav">Filtriranje</button>
            <div class="filter sticky-top py-5 collapse show" id="side-nav">
                <div class="list-group my-2">
                    <h3 class="filter-title">Iskanje</h3>
                    <input type="text" class="form-control" id="search" autocomplete="off" placeholder="Išči..."/>
                </div>

                <div class="list-group">
                    <h3 class="filter-title">Cena</h3>
                    <input type="hidden" id="minPrice" value="<?=$prices['minPrice']-0.01?>" />
                    <input type="hidden" id="maxPrice" value="<?=$prices['maxPrice']+0.01?>" />
                    <input type="hidden" id="originalMinPrice" value="<?=$prices['minPrice']-0.01?>" />
                    <input type="hidden" id="originalMaxPrice" value="<?=$prices['maxPrice']+0.01?>" />
                    <p id="show-price"><?=$prices['minPrice']-0.01?> € - <?=$prices['maxPrice']+0.01?> €</p>
                    <div id="price-range"></div>
                </div>

                <div class="list-group list-group-flush pt-4">
                    <button class="btn btn-filter btn-info" type="button" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">Znamke</button>
                    <div class="section collapse show" id="collapseBrand">
                        <?php
                        $brands = validBrands($cid);
                        foreach($brands as $brand): ?>
                        <div class="list-group-item checkbox">
                            <label class="w-100"><input type="checkbox" class="common-selector brand ml-2" value="<?=$brand['id']?>"><?=$brand['title']?> <span class="badge badge-info badge-pill"><?=numItemsPerBrand($brand['id'], $cid)?></span></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php $src_arr = categories(); ?>
                <div class="list-group list-group-flush pt-4">
                    <button class="btn btn-filter btn-info" type="button" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">Kategorije</button>
                    <div class="section collapse show" id="collapseCategory">
                        <?php if(!isset($cid) && empty($cid)): ?>
                            <?php foreach(rootCategories() as $root):
                                $cid = $root['id'];
                                $categories = fetch_recursive($src_arr, $cid); 
                                foreach($categories as $category): ?>
                                    <?php if($category['parent_id'] == null): ?>
                                        <div class="list-group-item checkbox" style="border-top: 3px solid var(--link-color);">
                                    <?php else: ?>
                                <div class="list-group-item checkbox">
                                    <?php endif; ?>
                                    <label class="w-100"><input type="checkbox" class="common-selector category ml-2" value="<?=$category['id']?>"><?=$category['category']?></label>
                                </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else:
                            $categories = fetch_recursive($src_arr, $cid); 
                            foreach($categories as $category): ?>
                            <?php if($cid != $category['id']): ?>
                                <div class="list-group-item checkbox">
                                    <label class="w-100"><input type="checkbox" class="common-selector category ml-2" value="<?=$category['id']?>"><?=$category['category']?></label>
                                </div>
                            <?php else: ?>
                                <div class="list-group-item" hidden>
                                    <h3><label class="w-100"><input type="checkbox" class="common-selector category ml-2 original" value="<?=$category['id']?>" checked hidden disabled><?=$category['category']?></label></h3>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div> <!-- filter -->
        </div>
        <div class="col-xl-10 col-md-9">
            <select name="numberOfItems" id="numberOfItems" class="btn ">
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="18">18</option>
                <option value="24">24</option>
                <option value="30">30</option>
            </select>
            <div class="row filter-data clearfix justify-content-center">

            </div>  <!-- filter_data -->
            <div class="row filter-pagination justify-content-center text-center">

            </div>
        </div> <!-- first-column filter-wrapper -->
    </div> <!-- row -->
</div> <!-- container-fluid -->

<?php
    include_once './footer.php';
?>


<script>
$(document).ready(function() {

    filter_data(1);

    function filter_data(page)
    {
        $('.filter-data').html('<div id="loading" style=""></div>');
        var sendData = {
            action: 'fetch_data',
            search: $('#search').val(),
            minPrice: $('#minPrice').val(),
            maxPrice: $('#maxPrice').val(),
            brand: get_filter('brand'),
            category: checkHidden(get_filter('category'), 'original'),
            limit: $('#numberOfItems').val(),
            page: page,
        };
        console.log(sendData);
        $.ajax({
            // contentType: "application/json",
            url: "./fetch_data.php",
            method: "POST",
            data: sendData,
            dataType: "JSON",
            // cache: false,
            success:function(response) {
                $('.filter-data').html(response.output);
                $('.filter-pagination').html(response.pagination);
            }
            
        });
    }

    function get_filter(class_name) {
        var filter = [];
        $('.'+class_name+':checked').each(function() {
            filter.push($(this).val());
        });
        return filter;
    }

    $('.common-selector').click(function() {
        filter_data(1);
    });

    $('#numberOfItems').change(function() {
        filter_data(1);
    })

    $('#search').keyup(function() {
        filter_data(1);
    })
    var min = parseFloat($('#originalMinPrice').val()),
        max = parseFloat($('#originalMaxPrice').val());

    $('#price-range').slider({

        range:true,
        min: min,
        max: max,
        values:[min, max],
        step:0.01,
        stop:function(event, ui)
        {
            $('#show-price').html(ui.values[0] + ' € - ' + ui.values[1] +' €');
            $('#minPrice').val(ui.values[0]);
            $('#maxPrice').val(ui.values[1]);
            filter_data(1);
        }
    });

    $('.sort-selector').click(function() {
        filter_data(1);
    });

    function checkHidden(filter, class_name) {
        if('.'+class_name+':checked' && filter.length > 1) {
            var removeItem = $('.'+class_name+':checked').val();
            filter = jQuery.grep(filter, function(value) {
                return value != removeItem;
            })
        }
        return filter;
    }

    $(document).on('click', '.page-link', function(){
        var page = $(this).data('page_number');
        // var query = $('#search_box').val();
        filter_data(page);
    });

});
</script>