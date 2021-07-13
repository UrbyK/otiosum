<?php

    require_once './session.php';
    include_once './dbh.inc.php';
    include_once './image-upload.inc.php';

    if (!isLogin()) {
        header("Location: ../../login");
        exit();
    } elseif (!isAdmin() && !isMod()) {
        header("Location: ../../index");
        exit();
    }

    if (!isset($_POST['submit'])) {
        header("Location: ../../brand-add");
        exit();
    } else {
        $brand = $_POST['brand'];
        $img = $_FILES['img'];

        // careful at writing upload_image query, keep in mind that relative path is set in the
        // the function and not here, keep an exstra '?' to execute 'data'
        $query = "INSERT INTO brand (title, image) VALUES (?,?)";
        $data = [$brand];

        $stat = upload_image($query,$data, $brand, $img);
        
        header ("Location: ../../brand-add?$stat");
        exit();
    //     $root = "../../uploads";

    //     $year = date("Y");
    //     $month = date("m");
    //     $time = date("H").".".date("i").".".date("s");
    //     // check if directory exists
    //     if(!file_exists("$root/$year/$month")){
    //         // create directory in case it doesn't exist
    //         mkdir("$root/$year/$month", 0777, true);
    //     }

    //     // determine save location
    //     $target_dir = "$root/$year/$month/";
    //     $relative_dir = "./uploads/$year/$month/";
    //     // get file extention (.txt, .jpg, .png, .php, .html, ...)
    //     $imageFileType = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
    //     // rename saved image/file
    //     $change_name = "$brand-$year-$month";
    //     // save location
    //     $target_file = $target_dir . $change_name.".".$imageFileType;
    //     $relative_file = $relative_dir . $change_name.".".$imageFileType;

    //     $uploadOK = 1;
    //     // check if selected file is image
    //     $check = getimagesize($_FILES["img"]["tmp_name"]);
    //     if ($check == false) {
    //         $uploadOK = 0;
    //         header("Location: ../../brand-add?error=file-type");
    //         exit();
    //     }
    //     // check for valid image extensions
    //     if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
    //         $uploadOk = 0;
    //         header("Location: ../../brand-add?error=img-type");
    //         exit();
    //     }
    //     // check if image size is less then 1MB
    //     if ($_FILES['img']['size'] > 1048576){
    //         $UploadOK = 0;
    //         header("Location: ../../brand-add?error=size");
    //         exit();
    //     }
    //     // check if upload conditions are OK
    //     if ($uploadOK == 1) {
    //         if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
    //             $pdo = pdo_connect_mysql();
    //             $query = "INSERT INTO brand (title, image) VALUES (?,?)";
    //             $stmt = $pdo->prepare($query);
    //             $stmt->execute([$brand, $relative_file]);
    //             header("Location: ../../brand-add?status=ok");
    //             exit();
    //         }
    //     }
    //     header("Location: ../../brand-add?error=err");
    //     exit();
    // }
    }

?>