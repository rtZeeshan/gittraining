<?php

namespace Math;

require 'calculator.php';

 /**
 * CalculatorTester
 *
 * Controls all functions to test Calculator class
 * @package    Math
 * @category	Practice
 * @author  	Nimra
 */
 
class CalculatorTests extends \PHPUnit_Framework_TestCase
{
    /**
    *local variable to hold object
    */
    private $calculator;
 
    
    /**
    * initialize object
    */
    protected function setUp()
    {
        $this->calculator = new Calculator();
    }
 
    /**
    * remove object at end
    */
    protected function tearDown()
    {
        $this->calculator = null;
    }
 
    /**
    *test function having name add
    */
    public function testAdd()
    {
        $result = $this->calculator->add(1, 2);
        $this->assertEquals(3, $result);
    }

    /**
    *test function having name sub
    */
    public function testSub()
    {
        $result = $this->calculator->sub(1, 2);
        $this->assertEquals(-1, $result);
    }
}
