<?php
function xss_cleaner($input_str) {
    if (!empty($input_str)) {
        $return_str = str_replace(array('<', ';', '|', '&', '>', "'", '"', ')', '('), array('&lt;', '&#58;', '&#124;', '&#38;', '&gt;', '&apos;', '&#x22;', '&#x29;', '&#x28;'), $input_str);
        $return_str = str_ireplace('%3Cscript', '', $return_str);
        return $return_str;
    } else {
        return "";
    }
}

?>