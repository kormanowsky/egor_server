<?php
require_once "../config.php";
require_once "../libs/storage.php";
require_once "../libs/events.php";
require_once "../libs/database.php";
Database::connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
?>
<!DOCTYPE html>
<html>
  <body>
    <h1>List of places</h1>
      <?php
      $places = Database::select(["table"=>"places"]);
      if($places): ?>
      <table>
      <tr>
        <th>Name</th>
        <th>Item</th>
        <th>Location</th>
      </tr>
        <?php 
        foreach($places as $place):
        ?>
        <tr>
          <td><a target="_blank" href="<?php echo $place->link; ?>"><?php echo $place->name;?></a></td>
          <td><?php echo $place->item;?></td>
          <td><?php echo $place->location;?></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php else: ?>
        <h3>There are no places</h3>
      <?php endif; ?>
    <p><a href="submit.php">Submit your place</a></p>
  </body>
</html>
