<?php

//write data to a file
$myfile = fopen("/var/www/html/21stJuly/newFile.txt", "w") or die("Unable to open file!");
$txt = "Mickey Mouse\n";
fwrite($myfile, $txt);
$txt = "Minnie Mouse\n";
fwrite($myfile, $txt);
fclose($myfile);

//read complete data from a file
$myfile = fopen("/var/www/html/21stJuly/newFile.txt", "r") or die("Unable to open file!");
echo fread($myfile, filesize("/var/www/html/21stJuly/newFile.txt"));
fclose($myfile);
echo "<br>";

$myfile = fopen("/var/www/html/21stJuly/newFile.txt", "r") or die("Unable to open file!");
// Output one line until end-of-file
while (!feof($myfile)) {
    echo fgets($myfile) . "<br>";
}
fclose($myfile);
