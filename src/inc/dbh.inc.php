<?php
    function pdo_connect_mysql(){
        $dbHost = 'localhost';
        $dbUser = 'root';
        $dbPass = '';
        $dbName = 'otiosum';

        try{
            return new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8', $dbUser, $dbPass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        } catch (PDOException $exception) {
            // If there is an error with the connection, stop the script and display the error.
            die ('Failed to connect to database!'. $exception->getMessage());
        }
    }
?>