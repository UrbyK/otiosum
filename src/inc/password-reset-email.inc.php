<?php
    function send_passReset_email($to_email, $token, $host) {
    $subject = "Ponastavitev gesla";
    $body = '
        <!DOCTYPE html>
            
        <html>

            <head>
                <title>Email validacija</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                
            <style type="text/css">
                .wrapper {
                padding: 20px;
                color: #444;
                font-size: 1.3em;
                }
                a {
                background: #1b9cff;
                text-decoration: none;
                padding: 8px 15px;
                border-radius: 5px;
                color: #fff;
                }
            </style>
            </head>

        <body>
        <div class="wrapper">
            <p>Pritisnite na spodnjo povezavo, da boste lahko ponastavili geslo:</p>
            <a href="'.$host.'/password-reset?status=verify&token='.$token.'&email='. $to_email .'" style="color:white;font-weight:600;};">Ponastavitev gesla!</a>
            <p>Če povezava ne deluje, kopirajte povezavo v naslovno vrstico.</p>
        </div>
        </body>

        </html>
    ';
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