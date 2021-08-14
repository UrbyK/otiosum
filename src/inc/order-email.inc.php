<?php
    function send_order_email($to_email, $message, $orderStatus, $order, $oid) {
        $subject = "Stanje naročila: $oid";
        $body = '
    <!DOCTYPE html>

            <html>

                <head>
                    <title>Naročilo</title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
                    
                    <style type="text/css">
                        .title{
                            color: #0b61a4;
                        }
                        .wrapper {
                        padding: 20px;
                        color: #444;
                        font-size: 1.3em;
                        }
                        a {
                        text-decoration: none;
                        padding: 8px 15px;
                        border-radius: 5px;
                        }
                    </style>
                </head>

            <body>
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="card w-100 border-0 my-3">
                            <div clsas="row text-center">
                                <h2 class="title text-center">'.$message.'</h2>
                            </div>
                            <div class="row px-3">
                                <div class="col my-3">
                                    <div class="title">
                                        <div class="row">
                                            <div class="col">
                                                <h3>Naročilo: <b>'.$oid.'</b></h3>
                                            </div>
                                            <div class="col text-right">
                                                <h3>Stanje naročila: <b>'.$orderStatus.'</b></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row align-items-center border-bottom mx-2 my-2">
                                        <div class="col text-right "><h4>Skupna cena: <b><span class="total">'.$order['total'].'</span> &euro;</b></h4></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- container -->
            </div>
        </body>

    </html>';

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            
        // More headers
        $headers .= 'From: <no-reply@otiosum.com>' . "\r\n";

        if (mail($to_email, $subject, $body, $headers)) {
            echo "Email successfully sent to $to_email...";
        } else {
            echo "Email sending failed...";
        }

    }
?>