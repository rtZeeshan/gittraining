<?php

/*
 * Copyright 2015 SugarCRM Inc.
 */

require_once 'custom/modules/Contacts/log_data.php';
require_once 'tests/custom/modules/Contacts/HookTest.php';

/**
 *  Example tests for our custom Logic Hook.
 */
class LogDataTest extends Sugar_PHPUnit_Framework_TestCase {

    private $bean; //Accounts bean
    private $hooks; //Hooks class
    private $rel_arr;
    private $school;
    private $hookTest;
    private $schoolBean;

    /**
     * Set up before each test
     */
    public function setUp() {
        parent::setUp();
        // $abc = BeanFactory::getBean('Contacts','8a77114c-c768-71ca-2305-57ac0055e914');

        $this->hooks = $this->getMockBuilder(logData::class)->getMock();


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


        // testing my logic hook
        $this->schoolBean = BeanFactory::newBean('sch_schools');
        // $GLOBALS['log']->fatal(print_r($this->schoolBean,true));
        $map = array(
            array('6073fac1-2da5-954e-097c-57ac2a888290', 'SCHOOL4'),
            array('7ba6b065-364d-4c0a-01b3-57ac00d9fbed', 'SCHOOL3'),
            array('f1ac023b-40ec-a2fd-b85c-57ac006a746b', 'SCHOOL2')
        );

        $this->hookTest = $this->getMockBuilder(HookTest::class)->setMethods(array('getBeanNameCapitalized'))->getMock();

        $this->hookTest->method('getBeanName')->willReturn('abc');
        $this->hookTest->expects($this->any())->method('getBeanNameCapitalized')->will($this->returnValueMap($map));
        //$this->hookTest->method('changingData')->will($this->returnCallback('getBeanName'));
        //$GLOBALS['log']->fatal(method_exists($this->hookTest, 'getBeanName'));
    }

// simple testing
    public function testHook() {
        //   $id = $this->bean->fetched_row['id'];
        //  $bean = $this->bean;
        //  $GLOBALS['log']->fatal($this->bean->first_name);
        // if(isset($bean->id)){
        $this->hooks->loggingData($this->bean, "before_save", array());
        $this->assertEquals($this->bean->last_name, $this->hooks->loggingData($this->bean, "before_save", array()));
        //}
    }

    public function testRelationship() {

        $this->assertContains('sch_staff_sch_schools', $this->rel_arr);
    }

    public function testDependency() {

        $this->assertContains('teacher_role', $this->school->testDependency());
    }

    public function testMyHook() {
        $this->schoolBean->id = '7ba6b065-364d-4c0a-01b3-57ac00d9fbed';
        $this->hookTest->changingData($this->schoolBean, "before_save", array());
        $this->assertEquals('SCHOOL3', $this->schoolBean->name);
    }

}
