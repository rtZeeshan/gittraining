<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

//    require_once('data/SugarBean.php');

class HookTest {

    public function changingData($bean, $event, $arguments) {

        //  $GLOBALS['log']->fatal('jhasjdhasdhj');
        $bean->name = $this->getBeanNameCapitalized($bean->id);
    }

    public function getBeanNameCapitalized($id) {

        $principal = BeanFactory::getBean('sch_schools', $id);
        return strtoupper($principal->name);
    }

}
?>
