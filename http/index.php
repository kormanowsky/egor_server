<!DOCTYPE html>
<html>
  <body>
    <h1>List of places</h1>
    <table>
      <tr>
        <th>Name</th>
        <th>Item</th>
        <th>Location</th>
      </tr>
      <?php
      $places = Database::select(["table"=>"places"]);
      if($places):
        foreach($places as $place):
        ?>
        <tr>
          <td><a target="_blank" href="<?php echo $place->link; ?>"><?php echo $place->name;?></a></td>
          <td></td>
          <td></td>
        </tr>
        <?php enforeach; ?>
      </table>
      <?php else: ?>
        <h3>There are no places</h3>
      <?php endif; ?>
