<?php  
    $errorList = [
    // universal error
    "err" => "Prišlo je do napake, poskusite kasneje!",
    "empty" => "Prosim izpolnite vsa zahtevana polja!",

    // register error
    "user-exists" => "Uporabnik že obstaja, prosim izberite drugo uporabniško ime!",
    "user-length" => "Uporabniško ime je prekratko, ime mora biti dolgo vsaj 4 znake!",
    "email-exists" => "Email že obstaja, prosim uporabite drugačno elektronsko pošto!",
    "email-invalid" => "Naslov je neveljaven!",
    "pass-length" => "Uporabljeno geslo je prekratko, geslo mora biti dolgo vsaj 8 znakov!",
    "pass-lower" => "Geslo mora vsebovati vsaj 1 mali znak!",
    "pass-upper" => "Geslo mora vsebovati vsaj 1 veliki znak!",
    "pass-digit" => "Geslo mora vsebovati vsaj 1 številko!",
    "pass-special" => "Geslo mora vsebovati vsaj 1 posebni znak!",
    "pass-match" => "Gesla se ne ujemata!",

    // img/file error
    "file-type" => "Izbrani file ni slika!",
    "img-type" => "Slika ni shranjena v pravem formatu!",
    "size" => "Velikost slike/datoteke je prevelika!",
    
    // product error
        // main
    "prd-title" => "Izdelek mora imeti ime!",
    "sku-empty" => "SKU širfa mora biti podana!",
    "sku-exists" => "Izdelek z podano šifro že obstaja!",
    "quantity-empty" => "Količina mora biti podana!",
    "quantity-type" => "Količina ni pozitivno celo število!",
    "price-empty" => "Cena mora biti podana!",
    "price-type" => "Cena ni pozitivno deciamlno število!",
    
        // inserts
    "prd-ins" => "Napaka pri vnosu izdelka!",
    "measure-ins" => "Napaka pri vnosu meril!",
    "prd-cat" => "Napaka pri povezavi izdelka s kategorijami!",
    "prd-sale" => "Napaka pri povezavi izdelka s popustom!",

    // brand
    "brand-empty" => "Znamka mora imeti podano ime!",
    "image-empty" => "Slika mora biti podana!",
    "brand-exists" => "Znamka s tem imenom že obstaja",
    ];

?>