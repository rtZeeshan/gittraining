<?php

namespace Prac;

/**
*Practice of concepts of global Variables
*/
$a=2;
$b=5;

class GlobalVar
{
    /**
    *method of accessing global variables using $GLOBALS
    */
    public function checkGlobal()
    {
        return $GLOBALS['a']+ $GLOBALS['b'];
    }

    /**
    *functionality of global variable $_SERVER
    */
    public function checkServer()
    {
        echo $_SERVER['PHP_SELF'];
        echo "<br>";
        echo $_SERVER['SERVER_NAME'];
        echo "<br>";
        echo $_SERVER['HTTP_HOST'];
        echo "<br>";
        echo $_SERVER['HTTP_REFERER'];
        echo "<br>";
        echo $_SERVER['HTTP_USER_AGENT'];
        echo "<br>";
        echo $_SERVER['SCRIPT_NAME'];
    }
}

$obj=new GlobalVar();
echo ($obj->checkGlobal());
echo "<br>";
echo ($obj->checkServer());
echo "<br>";
