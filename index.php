<?php
    include_once './header.php';

    $token = md5("beseda");
    echo "<p>$token</p>";


    for ($i=0; $i <1000 ; $i++) { 
        $token = bin2hex(random_bytes(30));
        echo "<p>$token</p>";
    }

    include_once './footer.php';

?>