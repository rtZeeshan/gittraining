<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$viewdefs['base']['view']['dashlet-life-cycle'] = array(
    'dashlets' => array(
        array(
            'label' => 'Lifecycle',
            'description' => 'conversion lifecycle from targets to contacts',
            'config' => array(),
            'preview' => array(),
            'filter' => array(
                'module' => array('Prospects','Leads','Contacts','Opportunities','Accounts'),
                'view' => 'record',
            ),
        ),
    ),
);
