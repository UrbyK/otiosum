<?php
    include_once './header.php';
    if(isset($_GET['cid']) && !empty($_GET['cid'])) {
        $cid = $_GET['cid'];
    }
?>

<!-- slider inport js and css -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-2 col-md-3 filter-wrapper">
            <div class="filter sticky-top py-5">
                <div class="list-group my-2">
                    <h3 class="filter-title">Iskanje</h3>
                    <input type="text" class="form-control" id="search" autocomplete="off" placeholder="Išči..."/>
                </div>

                <div class="list-group">
                    <h3 class="filter-title">Cena</h3>
                    <input type="hidden" id="minPrice" value="0" />
                    <input type="hidden" id="maxPrice" value="350" />
                    <p id="show-price">0 - 350</p>
                    <div id="price-range"></div>
                </div>

                <div class="list-group list-group-flush pt-4">
                    <button class="btn btn-filter btn-secondary" type="button" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">Znamke</button>
                    <div class="section collapse" id="collapseBrand">
                        <?php
                        $brands = brands();
                        foreach($brands as $brand): ?>
                        <div class="list-group-item checkbox">
                            <label class="w-100"><input type="checkbox" class="common-selector brand ml-2" value="<?=$brand['id']?>"><?=$brand['title']?> / <?=numItemsPerBrand($brand['id'])?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php $src_arr = categories(); ?>
                <div class="list-group list-group-flush pt-4">
                    <button class="btn btn-filter btn-secondary" type="button" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">Kategorije</button>
                    <div class="section collapse" id="collapseCategory">
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
                                <div class="list-group-item hidden">
                                    <h3><label class="w-100"><input type="checkbox" class="common-selector category ml-2 original" value="<?=$category['id']?>" checked hidden disabled><?=$category['category']?></label></h3>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xl-10 col-md-9">
            <div class="row filter_data clearfix justify-content-center">

            </div>
            <div class="row filter_pagination justify-content-center text-center">

            </div>
        </div>
    </div>
</div>

<?php
    include_once './footer.php';
?>


<script>
$(document).ready(function() {

    filter_data();

    function filter_data()
    {
        $('.filter_data').html('<div id="loading" style="" ></div>');
        var action = 'fetch_data';
        var search = $('#search').val();
        var minPrice = $('#minPrice').val();
        var maxPrice = $('#maxPrice').val();
        var brand = get_filter('brand');
        var category = checkHidden(get_filter('category'), 'original');
        console.log(category);
        $.ajax({
            url:"fetch_data.php",
            method:"POST",
            data:{"action":action, "search":search, "brand":brand, "minPrice":minPrice, "maxPrice":maxPrice, "category":category},
            success:function(data) {
                $('.filter_data').html(data);
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
        filter_data();
    });

    $('#search').keyup(function() {
        filter_data();
    })

    $('#price-range').slider({
        range:true,
        min:0,
        max:350,
        values:[0, 350],
        step:5,
        stop:function(event, ui)
        {
            $('#show-price').html(ui.values[0] + ' - ' + ui.values[1]);
            $('#minPrice').val(ui.values[0]);
            $('#maxPrice').val(ui.values[1]);
            filter_data();
        }
    });

    $('.sort-selector').click(function() {
        filter_data();
    });

    function checkHidden(filter, class_name) {
        if('.'+class_name+':checked' && filter.length > 1) {
            console.log(filter);
            var removeItem = $('.'+class_name+':checked').val();
            filter = jQuery.grep(filter, function(value) {
                return value != removeItem;
            })
        }
        console.log(filter);
        return filter;
    }

});
</script>