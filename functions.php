<?php
    include './src/inc/dbh.inc.php';

    /* Template header */
    function template_header($title){
        include_once('./header.php');
    }

    /* Template footer */
    function template_footer(){
        include_once('./footer.php');
    }

?>