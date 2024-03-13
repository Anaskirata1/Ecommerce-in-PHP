<?php

function lang($phrase) {
    static $lang = array(
        'MESSAGE' => "اهلا و سهلا" , 
        'ADMIN' => "هدمن عربي " 
    ) ;
    return $lang[$phrase];

}