<?php

$GLOBALS['config'] = array(
    "db" => array(
        "host" => "localhost",
        "database" => "school",
        "user" => "root",
        "password" => ""
    ),
    "session" => array(
        "name" => "admin"
    )
);

class config {
    public static function get($path){
        $path = explode("/", $path);
        $data = $GLOBALS['config'];

        foreach ($path as $val) {
            if(isset($data[$val])){
                $data = $data[$val];
            }else{
                $data = false;
            }
        }
        return $data;
    }
}
ini_set("session.cookie_domain", ".giis.com");
session_name("giis_session");
session_start();

spl_autoload_register(function($class){
    require_once "class/" .$class . ".php";
});