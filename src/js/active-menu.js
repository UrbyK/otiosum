$(function(){
    // this will get the full URL at the address bar
    var url = window.location.href; 

    // passes on every "a" tag 
    $(".navbar a").each(function() {
            // checks if its the same on the address bar
        if(url === (this.href)) { 
            $(this).closest("li").addClass("active");
        }
    });
});

// $(document).ready(function(){
//     $('a').click(function(){
//         $('a').removeClass("active");
//         $(this).addClass("active");
//     });
//   });


// $(document).ready(function () {
//     var url = window.location;
//     $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
//     $('ul.nav a').filter(function() {
//             return this.href == url;
//     }).parent().addClass('active');
// });
