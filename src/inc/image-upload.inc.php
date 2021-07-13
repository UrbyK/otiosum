<?php 
    function upload_image($title, $image) {
        $root = "../../uploads";
        $year = date("Y");
        $month = date("m");
        /* Converts a string to slug */
        $title = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

        // check if directory exists
        if(!file_exists("$root/$year/$month")){
            // create directory in case it doesn't exist
            mkdir("$root/$year/$month", 0777, true);
        }

        // determine save location
        $target_dir = "$root/$year/$month/";
        $relative_dir = "./uploads/$year/$month/";
        // get file extention (.txt, .jpg, .png, .php, .html, ...)
        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        // rename saved image/file
        $change_name = "$year-$month-$title";
        // save location
        $target_file = $target_dir . $change_name.".".$imageFileType;
        $relative_file = $relative_dir . $change_name.".".$imageFileType;

        $uploadOK = 1;

        $check = getimagesize($image["tmp_name"]);
        if ($check == false) {
            $uploadOK = 0;
            return "error=file-type";
            exit();
        }

        // check for valid image extensions
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
            $uploadOK = 0;
            return "error=img-type";
            exit();
        }

        // check if image size is less then 1MB
        if ($image['size'] > 1048576){
            $uploadOK = 0;
            return "error=size";
            exit();
        }

        // check if upload conditions are OK
        if ($uploadOK == 1) {
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                return $relative_file;
                exit();
            }
             else {
                return "error=err";
                exit();
            }
        }
        // return "error=err";
        // exit();
    }

?>
