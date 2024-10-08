<?php declare(strict_types=1);

//require_once __DIR__.'/class/D3LanguageManager.class.php' ;
//$langman = D3LanguageManager::getInstance() ;
//$langman->read( 'modinfo.php' , 'altsys' , 'altsys' ) ;

if (file_exists(__DIR__ . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php')) {
    require_once __DIR__ . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php';
} elseif (file_exists(__DIR__ . '/language/english/modinfo.php')) {
    require_once __DIR__ . '/language/english/modinfo.php';
}

$controllers = [
    'myblocksadmin',
    'compilehookadmin',
    'get_templates',
    'get_tplsvarsinfo',
    'mypreferences',
    'mytplsadmin',
    'mytplsform',
    'put_templates',
    'mylangadmin',
];
