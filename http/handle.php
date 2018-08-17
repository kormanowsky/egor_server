<?php
require_once "../config.php";
require_once "../libs/storage.php";
require_once "../libs/events.php";
require_once "../libs/database.php";
if(isset($_POST['new_place_submit'])){
  foreach($_POST as $k=>$v){
    $_POST[$k]=htmlspecialchars($v);
  }
  Database::connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  Database::insert([
    'table' => 'places',
    'data => $_POST,
  ]);
}
?>
