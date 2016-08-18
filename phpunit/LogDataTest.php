<?php

/*
 * Copyright 2015 SugarCRM Inc.
 */

require_once 'custom/modules/Contacts/log_data.php';

/**
 *  Example tests for our custom Logic Hook.
 */
class LogDataTest extends Sugar_PHPUnit_Framework_TestCase {

    private $bean; //Accounts bean
    private $hooks; //Hooks class
    private $rel_arr;
    private $school;
    /**
     * Set up before each test
     */

    public function setUp() {
        parent::setUp();
        // $abc = BeanFactory::getBean('Contacts','8a77114c-c768-71ca-2305-57ac0055e914');

        $this->hooks = $this->getMockBuilder(logData::class)->getMock();
        //$this->g
        //$this->hooks = new logData();
        $this->hooks->method('loggingData')->willReturn('qwe');

        $this->bean = $this->getMock("Contact");
        $this->bean->id = '8a77114c-c768-71ca-2305-57ac0055e914';
        $this->bean->last_name = 'qwe';

        $this->school = $this->getMockBuilder(sch_schools::class)->setMethods(['testDependency'])->getMock();

        $this->rel_arr = array();
        foreach ($this->school->field_defs as $field => $def) {
            if (isset($def['relationship'])) {
                //return(array($def['name']));
                $this->rel_arr[] = $def['relationship'];
                // $GLOBALS['log']->fatal($def['relationship']);
            }

            //   $GLOBALS['log']->fatal(print_r($schoo->field_defs,true));
            /**
             * Use SugarTestHelper to set up only those Sugar global values that are needed.
             * Framework will tear these down automatically after each test.
             */
            // SugarTestHelper::setUp("beanList");
        }
        
        $dep_arr = array('teacher_role');
        $this->school->method('testDependency')->willReturn($dep_arr);
        
       // $GLOBALS['log']->fatal(print_r($schoo, true));
    }

    /**
     * Example that relies on SugarTestHelper
     */
    /*
      public function testBeanListLoaded(){
      global $beanList;
      $this->assertNotEmpty($beanList["Contacts"], "This test relies on Contacts bean.");
      }
     * 

     */

    /**
     * Verify that logic hook changes Industry value when necessary
     */
    public function testHook() {
        //   $id = $this->bean->fetched_row['id'];
        //  $bean = $this->bean;
        //  $GLOBALS['log']->fatal($this->bean->first_name);
        // if(isset($bean->id)){
        $this->hooks->loggingData($this->bean, "before_save", array());
        $this->assertEquals($this->bean->last_name, $this->hooks->loggingData($this->bean, "before_save", array()));
        //}
    }
    
    public function testRelationship(){
        
         $this->assertContains('sch_staff_sch_schools',$this->rel_arr);
    }

    public function testDependency(){
        
         $this->assertContains('teacher_role',$this->school->testDependency());
    }
}
