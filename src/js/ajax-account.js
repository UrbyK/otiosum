
    // CDN for form validation plugin
    var jQueryScript = document.createElement('script');  
    jQueryScript.setAttribute('src','./src/js/jquery.validate.js');
    document.head.appendChild(jQueryScript);

    // fire once when page loads
    $(document).ready(function() {
        fetchData();
    });

    // function that gets data response
    function fetchData() {
        // var aid = $("#aid").val();
        $.ajax({
            url: "./account.inc.php",
            method: "POST",
            data: ({"action":"fetchData"}),
            dataType: "JSON",
            success: function(response) {
                $('.filter-data').html(response.output);
            }
        });
    }

    // enable all account form fields
    function toggleEnabled() {
        $("#account-form input, #account-form  select").prop("disabled", false);
        $("#modify").prop({"disabled":true, "hidden":true});
        $("#save").prop({"disabled":false, "hidden":false});
        $("#cancel").prop({"disabled":false, "hidden":false});
    }
    
    // disable all account form fields
    function toggleDisabled() {
        $("#account-form input, #account-form select").prop("disabled", true);
        $("#modify").prop({"disabled":false, "hidden":false});
        $("#save").prop({"disabled":true, "hidden":true});
        $("#cancel").prop({"disabled":true, "hidden":true});
    }

    $(document).on("click", "#modify", function(e) {
        e.preventDefault();
        toggleEnabled();
    });

    $(document).on("click", "#cancel", function(e) {
        e.preventDefault();
        toggleDisabled();
        fetchData();
    });

    // on save click first validate the form to see if there are any discrepencies and then update the account information
    $(document).on("click", "#save", function(e) {
        e.preventDefault();
        $("#account-form").validate({
            rules: {
                fname: "required",
                lname: "required",
                address: "required",
                city: "required",
                postalCode: {
                    required: true,
                    minlength: 4,
                }
            },
            messages: {
                fname: "Prosim izpolnite polje!",
                lname: "Prosim izpolnite polje!",
                address: "Prosim izpolnite polje!",
                city: "Prosim izpolnite polje!",
                postalCode: {
                    required: "Prosim izpolnite polje!",
                    minlength: "Poštna številka mora biti dolga vsaj 4 znake!"
                }
            }
        });
    
        if( $("#account-form").valid()) {
            updateAccount();
            toggleDisabled();
        }
    });

    // update account address information 
    function updateAccount() {
        var data = {
            'action': "update",
            'fname': $('#fname').val(),
            'lname': $('#lname').val(),
            'phoneNumber': $('#phoneNumber').val(),
            'address': $('#address').val(),
            'addressTwo': $('#addressTwo').val(),
            'city': $('#city').val(),
            'postalCode': $('#postalCode').val(),
            'country': $('#country').val(),
        }
        console.log(data);
        $.ajax({
            url: "./account.inc.php",
            method: "POST",
            data: data,
            dataType: "JSON",
            success: function(response) {
                if(response.error == "") {
                    fetchData();
                    toggleDisabled();
                    $(".alert").prop("hidden", true);
                } else {
                    $(".alert-error").html(response.error);
                    $(".alert").prop("hidden", false);
                }
            }
        });
    }