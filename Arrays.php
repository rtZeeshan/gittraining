<?php

//PHP arrays can contain integer and string keys at 
//the same time as PHP does not distinguish between 
//indexed and associative arrays. 

/**
*Test different functionalities of an array
*/
function checkArray()
{
    $array = array(
        "foo" => "bar",
        "bar" => "foo",
        100   => -100,
        -100  => 100,
    );
    var_dump($array);
    echo "</br>";

    //Array elements can be accessed using the array[key] syntax.
    $array = array(
        "foo" => "bar",
        42    => 24,
        "multi" => array(
             "dimensional" => array(
                 "array" => "foo"
             )
        )
    );

    var_dump($array["foo"]);
    var_dump($array[42]);
    var_dump($array["multi"]["dimensional"]["array"]);

    /**
    *Return an array
    */
    function getArray()
    {
        return array(1, 2, 3);
    }


    /**
    *will produce an array that would have been defined as
    *$a = array(1 => 'one', 3 => 'three');
    *and NOT
    *$a = array(1 => 'one', 2 =>'three');
    */
    $a = array(1 => 'one', 2 => 'two', 3 => 'three');
    unset($a[2]);

    foreach ($a as $key => $value) {
        echo "</br>". $key." has the value ". $value;
    }


    $fruits = array ( "fruits"  => array ( "a" => "orange",
                                           "b" => "banana",
                                           "c" => "apple"
                                         ),
                      "numbers" => array ( 1,
                                           2,
                                           3,
                                           4,
                                           5,
                                           6
                                         ),
                      "holes"   => array (      "first",
                                           5 => "second",
                                                "third"
                                         )
                    );

    // Some examples to address values in the array above
    echo  "</br>".  $fruits["holes"][5];    // prints "second"
    echo  "</br>". $fruits["fruits"]["a"]; // prints "orange"
    unset($fruits["holes"][0]);  // remove "first"

    // Create a new multi-dimensional array
    $juices["apple"]["green"] = "good";
}

    checkArray();
