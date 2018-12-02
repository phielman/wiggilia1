<?php
return array(
    '_db' => array(
        'dsn' => 'mysql:host=wiggor-production.mysql.database.azure.com;dbname=wiggor_wigilia',
         'user' => 'wiggor@wiggor-production',
        'password' => 'Dlaczegotoniedziala!',
    ),
    'title' => array('Losowanie | Wiggilia'),
);



function printr ( $object , $name = '' ) {

    print ( '\'' . $name . '\' : ' ) ;

    if ( is_array ( $object ) ) {
        print ( '<pre>' )  ;
        print_r ( $object ) ;
        print ( '</pre>' ) ;
    } else {
        var_dump ( $object ) ;
    }
}

function curPageURL() {
 $pageURL = 'http://';
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>