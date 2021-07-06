<?php

    require_once './session.php';
    include_once './dbh.inc.php';

    if (!isLogin()) {
        echo "Login " . isLogin();
    } elseif (!isAdmin() || !isMod()) {
        echo " Admin " . isAdmin() . " Mod " . isMod();
    }

    if (!isset($_POST['submit'])) {
        header("Location: ../../brand-add");
        exit();
    } else {
        $brand = $_POST['brand'];
        $img = $_FILES['img'];
        $root = "../../uploads";

        $year = date("Y");
        $month = date("m");
        $time = date("H").".".date("i").".".date("s");
        // check if directory exists
        if(!file_exists("$root/$year/$month")){
            // create directory in case it doesn't exist
            mkdir("$root/$year/$month", 0777, true);
        }
        //fopen("$root/$year/$month/test-$year-$month-$time.txt", "w");

        // determine save location
        $target_dir = "$root/$year/$month/";
        $relative_dir = "$root/$year/$month/";

        // rename saved image/file
        $change_name = "$brand-$year-$month";
        
        $target_file = $target_dir . $change_name;
        $relative_file = $relative_dir . $change_name;

        $uploadOK = 1;
        // get file extention (.txt, .jpg, .png, .php, .html, ...)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // check if selected file is image
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if ($check == false) {
            $uploadOK = 0;
            header("Location: ../../brand-add?error=file-type");
            exit();
        }

        // check for valid image extensions
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
            header("Location: ../../brand-add?error=image-type");
            exit();
        }

        // check if image size is less then 1MB
        if ($_FILES['img']['size'] > 1000000){
            $UploadOK = 0;
            header("Location: ../../brand-add?error=size");
            exit();
        }


        // check if upload conditions are OK
        if ($uploadOK == 1) {
            if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                // $pdo = pdo_connect_mysql();
                // $query = "INSERT INTO brand (title, image) VALUES (?,?)";
                // $stmt = $pdo->prepare($query);
                // $stmt->execute([$brand, $relative_file]);
                // echo " File: $relative_file";
                // echo $_FILES['img']['tmp_name'];
                header("Location: ../../brand-add?status=ok");
                exit();
            }
        }
        header("Location: ../../brand-add?error=err");
        exit();
    }

?>
