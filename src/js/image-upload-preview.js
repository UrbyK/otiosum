
$(document).on("click", ".browse", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});
$('#img').change(function(e) {
    // check if number of selected images is less then 4
    if ($("#img")[0].files.length > 4) {
        alert("Naložite lahko samo 4 slike!");
    } else {
        // on a new insert reset "Upload here" text
        $("#file").val("");
        // empty preview div
        $("#preview").empty();

        // error message and error status
        var errorMsg = "";
        var statusOK = 1;
        
        // valid extentions for image upload
        var validExt = ['jpg', 'png', 'webp', 'jepg'];
        // move through all the selected files
        for (let i = 0; i < e.target.files.length; i++) {
            var fileName = e.target.files[i].name;

            // check file extention
            var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
            if($.inArray(fileNameExt, validExt) != -1) {

                // check if given image size is less then 1MB (1048576 Byte)
                if (e.target.files[i].size <= (1024*1024)) {

                    // display file name in input field
                    $("#file").val($("#file").val() + fileName + " ");
                    // display images in preview window/div
                    var reader = new FileReader();
                    reader.addEventListener("load", function() {
                        var image = new Image();
                        image.height = 150;
                        image.title = fileName;
                        image.src = this.result;
                        preview.appendChild(image);
                    });
                    reader.readAsDataURL(e.target.files[i]);
                } else {
                    statusOK = 0;
                    errorMsg += fileName + " je večja od 1MB!\n";
                }
            } else {
                errorMsg += fileName + " ni formata jpg, jepg, png, webp!\n";
                statusOK = 0;
            }
        }

        // check if there were any errors in image selection and show alert
        if (statusOK == 0) {
            alert(errorMsg);
        }
        console.log(statusOK);
    }
    
});