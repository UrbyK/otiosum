<?php
    include_once './header.php';

    echo "This is a products page";
    
    // echo category ID if category is set in the URL
    if (isset($_GET['category'])) {
        echo "<p>".$_GET['category']."</p>";
    }

    include_once './footer.php';
?>