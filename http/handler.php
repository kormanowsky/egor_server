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
  Database::query("
    CREATE TABLE IF NOT EXISTS `places` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` mediumtext NOT NULL,
  `link` mediumtext NOT NULL,
  `item` mediumtext NOT NULL,
  `location` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1
  ");
  unset($_POST['new_place_submit']);
  Database::insert([
    'table' => 'places',
    'data' => $_POST,
  ]);
  ?>
  <!DOCTYPE html>
  <html>
    <body>
      <h1>Thanks for submission!</h1>
    </body>
  </html>
  <?php
  }else{
    header(“Location: /submit.php”);
  }
?>
