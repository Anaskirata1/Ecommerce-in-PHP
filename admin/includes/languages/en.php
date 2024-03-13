<?php

function lang($phrase) {
    static $lang = array(

        // navbar links
        'ADMIN'          => "Home" , 
        'categories'     => "sections " ,
        'ITEMS'          => "Items" ,
        'MEMBERS'        => "Members" ,
        'COMMENTS'     => "Comments" ,
        'STATISTICS'     => "Statistics" ,
        'LOGS'           => "Logs" 
    ) ;
    return $lang[$phrase];

}