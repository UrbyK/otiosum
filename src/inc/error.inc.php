<?php  
    $errorList = [
    // universal error
    "err" => "Prišlo je do napake, poskusite kasneje!",
    "empty" => "Prosim, izpolnite vsa zahtevana polja!",

    // register error
    "user-exists" => "Uporabnik že obstaja, prosim, izberite drugo uporabniško ime!",
    "user-length" => "Uporabniško ime je prekratko, ime mora biti dolgo vsaj 4 znake!",
    "email-exists" => "Elektronski naslov že obstaja, prosim, uporabite drug elektronski naslov!",
    "email-invalid" => "Naslov je neveljaven!",
    "pass-length" => "Uporabljeno geslo je prekratko, geslo mora biti dolgo vsaj 8 znakov!",
    "pass-lower" => "Geslo mora vsebovati vsaj 1 mali znak!",
    "pass-upper" => "Geslo mora vsebovati vsaj 1 veliki znak!",
    "pass-digit" => "Geslo mora vsebovati vsaj 1 številko!",
    "pass-special" => "Geslo mora vsebovati vsaj 1 posebni znak!",
    "pass-match" => "Gesli se ne ujemata!",
    "wrong" => "Geslo je napačno!",

    // reset password errorin
    "email" => "Elektronski naslov ne obstaja!",
    "err-token" => "Prišlo je do napake pri menjavi žetona!",
    "validation" => "Prišlo je do napake. Če napaka vztraja, ponovno zahtevajte ponastavitev gesla in poskusite znova. Če se napaka ponavlja, vas prosim, da nas kontaktirate!",
    
    // img/file error
    "file-type" => "Izbrana datoteka ni slika!",
    "img-type" => "Slika ni v pravem formatu!",
    "size" => "Velikost slike/datoteke je prevelika!",
    
    // product error
        // main
    "prd-title" => "Izdelek mora imeti ime!",
    "sku-empty" => "SKU-koda mora biti podana!",
    "sku-exists" => "Izdelek z podano SKU-kodo že obstaja!",
    "quantity-empty" => "Količina mora biti podana!",
    "quantity-type" => "Količina ni pozitivno celo število!",
    "price-empty" => "Cena mora biti podana!",
    "price-type" => "Cena ni pozitivno decimalno število!",
    
        // inserts
    "prd-ins" => "Napaka pri vnosu izdelka!",
    "measure-ins" => "Napaka pri vnosu mer!",
    "prd-cat" => "Napaka pri povezavi izdelka s kategorijami!",
    "prd-sale" => "Napaka pri povezavi izdelka s popustom!",

    // brand
    "brand-empty" => "Znamka mora imeti podano ime!",
    "image-empty" => "Slika mora biti podana!",
    "brand-exists" => "Znamka s tem imenom že obstaja!",
    ];

?>