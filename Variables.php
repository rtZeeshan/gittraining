<?php

/** 
* check simple variable 
*/

function check()
{
    $a=5;
    echo $a."</br>";
}

/**
*A variable variable takes the value of a variable and treats that as the name of a variable
*/
function derefernce()
{
 
    $a='hello';
    $$a = 'world';
    echo "$a.</br>";
    echo "$$a";
    echo "$a $hello";

    //You can even add more Dollar Signs
    $Bar = "a";
    $Foo = "Bar";
    $World = "Foo";
    $Hello = "World";
    $a = "Hello";

    echo $a; //Returns Hello
    echo $$a; //Returns World
    echo $$$a; //Returns Foo
    echo $$$$a; //Returns Bar
    echo $$$$$a; //Returns a

    echo $$$$$$a; //Returns Hello
    echo $$$$$$$a; //Returns World

}

    $a = 1;
    $b = 2;
/**
*global variables
*/
function checkGlobal()
{
    $GLOBALS['b'] = $GLOBALS['a'] + $GLOBALS['b'];
}
    
    echo "</br>". $b;

/**
*static variables
*/
function testStatic()
{
    static $a = 0;
    $a++;
}

for ($i=0; $i<5; $i++) {
    echo "</br>". testStatic();
}

//static $int = 1+2; wrong  (as it is an expression)

/**
* Global with Ref
*/
function testGlobalRef()
{
    global $obj;
    $obj = &new stdclass;
}

/**
* Global with Non Ref
*/

function testGlobalNoref()
{
    global $obj;
    $obj = new stdclass;
}

   testGlobalRef();
   var_dump($obj);
   echo "</br>";
   testGlobalNoref();
   var_dump($obj);

// $var1 is not declared in the global scope
/**
* scope of nested function
*@param $var1 any type variable
*/
function a($var1)
{

    function b()
    {
        global $var1;
        echo $var1; // there is no var1 in the global scope so nothing to echo
   
    }

    b();

}

   echo (a('hello'));
   check();
   derefernce();
   checkGlobal();
