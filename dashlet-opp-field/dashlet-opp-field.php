<?php

    $viewdefs['base']['view']['dashlet-opp-field'] = array(
        'dashlets' => array(
            array(
                'label'=>'Opportuinity Field',
                'description'=>'Opportuinity Field Dependency',
                'config'=>array(),
                'preview'=>array(),
                'filter'=>array(
                    'module' => array('Opportunities',),
                    'view'=>'record',
                ),
            ),
        ),
    );
?>