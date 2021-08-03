<!DOCTYPE html>
<html>
    <?php
        require './src/inc/session.php';
        require './functions.php';
        $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // pagination data
        $key="p";
        $url= $_SERVER['REQUEST_URI'];
        $page = preg_replace('~(\?|&)'.$key.'=[^&]*~', '', $url);
        if(parse_url($page, PHP_URL_QUERY)) {
            $page.="&p=";
        } else {
            $page.="?p=";
        }
    ?>
    <head>
        <meta charset="utf-8" content="text/html"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- JavaScript CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
            
        <!-- Bootstrap CDN -->
        <!-- CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <!-- JS -->
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script> -->
        <!-- JS Bundle -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>

        <!-- Font-Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />

        <script src="./src/js/active-menu.js" crossorigin="anonymous"></script>

        <script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>				

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="./src/css/style.css">

        <title>Otiosum</title>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <!-- <header>
            <div class="banner"><img src="https://via.placeholder.com/1900x250" alt="banner"></div>
        </header> -->

            <nav class="navbar navbar-expand-lg sticky-stop w-100">
                <div class="container-fluid">
                    <a class="navbar-brand" href="./index">Otiosum</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="main-nav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="./index">Domov</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="./products" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Izdelki</a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <?php foreach(rootCategories() as $category): ?>
                                        <a class="dropdown-item" href="./products?cid=<?=$category['id']?>"><?=$category['category']?></a>
                                    <?php endforeach; ?>
                                </div>
                            </li>

                            <?php foreach(main_menu_navigation("category") as $main_nav): ?>
                                <?php if(has_children('category', $main_nav['id'])>=1): ?>                 
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="./products?category=<?=$main_nav['id']?>" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$main_nav['category']?></a>
                                        
                                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                            <?php foreach(main_menu_navigation_sub("category", $main_nav['id']) as $sub_nav): ?>
                                                <a class="dropdown-item" href="./products?category=<?=$sub_nav['id']?>"><?=$sub_nav['category']?></a>
                                            <?php endforeach; ?>
                                        </div>

                                    </li>
                                <?php else: ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="./products?category=<?=$main_nav['id']?>"><?=$main_nav['category']?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <!-- check login status, and change login/logout buttons -->
                            <?php if (!isLogin()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./login">Prijava</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="./register">Registracija</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="./logout">Izpis</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            </nav>

<!-- Back to top button -->
<button type="button" class="btn btn-floating btn-lg rounded-circle" id="btn-back-to-top"><i class="fas fa-arrow-up"></i></button>


<script>
    //Get the button
    let mybutton = document.getElementById("btn-back-to-top");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
    scrollFunction();
    };

    function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
    }
    // When the user clicks on the button, scroll to the top of the document
    mybutton.addEventListener("click", backToTop);

    function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    }
</script>