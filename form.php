<?php

    //practice of $GET,$POST and $REQUEST super globals
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $name = $_POST['myfield'];
        if (empty($name)) {
            echo "Name is empty";
        } else {
            echo $name;
        }
    }
    else
    {
      $name = $_GET['myfield'];
        if (empty($name)) {
            echo "Name is empty";
        } else {
            echo $name. "GET";
        }
    }
 ?>
 <!DOCTYPE html>
 <html lang="en">
   <head>
     <meta char-set="utf-8">
     <title>Page title</title>
   </head>
   <body>
     <form action="form.php" method="GET">
       <input type="text" value="default value, you can edit it" name="myfield">
       <input type="submit" value = "check">
     </form>
   </body>
 </html>
