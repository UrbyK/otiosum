$(document).on('click', '#addToCart', function() {

        var sendData = {
            action: 'fetchItem',
            pid: $(this).val(),
            quantity: $('#quantity').val(),
        };
        console.log(sendData);
        $.ajax({
            // contentType: "application/json",
            url: "./src/inc/cart.inc.php",
            method: "POST",
            data: sendData,
            // cache: false,
            success :function() {
                loadNumberOfCartItems();
            }
            
        });
});

$(document).ready(function() {
    loadNumberOfCartItems();
});

function loadNumberOfCartItems() {
    $.ajax({
        url: "./src/inc/cart.inc.php",
        method: "POST",
        data: {'action':"fetchCartNum"},
        dataType: "json",
        // cache: false,
        success:function(response) {
            $('.cartItemCount ').html(response.itemCount);
        }
    })
}
