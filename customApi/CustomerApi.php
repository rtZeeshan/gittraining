
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('include/SugarQuery/SugarQuery.php');
require_once('data/BeanFactory.php');

class CustomerApi extends SugarApi {

    public function registerApiRest() {
        return array(
            'getCustomerInfo' => array(
                'reqType' => 'GET',
                'noLoginRequired' => true,
                'path' => array('customerInfo'),
                'pathVars' => array(''),
                'method' => 'getCustomerInfo',
                'shortHelp' => 'This method retrieves stock data.',
                'longHelp' => '',
        ));
    }

    public function getCustomerInfo($api, $args) {
        $json_arr = array();

        $query = new SugarQuery();
        $query->select(array('id', 'name', 'lead_id'));
        $query->from(BeanFactory::getBean('Prospects'), array('team_security' => false));
        $targets_arr = $query->execute();
        $target_leads = new SplFixedArray(count($targets_arr));

        for ($i = 0; $i < count($targets_arr); $i++) {
            $json_obj = array();

            $json_obj['target_id'] = $targets_arr[$i]['id'];
            $json_obj['target_name'] = $targets_arr[$i]['name__first_name'] . ' ' . $targets_arr[$i]['name__last_name'];
            if ($targets_arr[$i]['lead_id'] != null) {
                $queryLead = new SugarQuery();
                $queryLead->select(array('id', 'name', 'opportunity_id', 'contact_id', 'account_id'));
                $queryLead->from(BeanFactory::getBean('Leads'), array('team_security' => false));
                $queryLead->where()->equals('id', $targets_arr[$i]['lead_id']);
                $res = $queryLead->execute();
                $target_leads[$i] = $res[0];
                $json_obj['converted'] = true;
                $json_obj['lead_id'] = $target_leads[$i]['id'];
                $json_obj['lead_name'] = $target_leads[$i]['name__first_name'] . ' ' . $target_leads[$i]['name__last_name'];

                if ($target_leads[$i]['contact_id'] != null) {

                    $json_obj['contact_id'] = $target_leads[$i]['contact_id'];
                }
                if ($target_leads[$i]['opportunity_id'] != null) {

                    $json_obj['opportunity_id'] = $target_leads[$i]['opportunity_id'];
                }

                if ($target_leads[$i]['account_id'] != null) {

                    $json_obj['account_id'] = $target_leads[$i]['account_id'];
                }
//$target_leads[$i] = BeanFactory::retrieveBean('Leads', $target->lead_id, array('disable_row_level_security' => true));
            } else {
                $json_obj['converted'] = false;
            }
            $json_arr[] = $json_obj;
        }
        $query2 = new SugarQuery();
        $query2->select(array('id', 'name', 'opportunity_id', 'contact_id', 'account_id'));
        $query2->from(BeanFactory::getBean('Leads'), array('team_security' => false));
        $resLeads = $query2->execute();
        $leads_arr = array();
        foreach ($resLeads as $lead) {
            if (!in_array($lead, $target_leads)) {
                $leads_arr[] = $lead;
            }
        }

        for ($i = 0; $i < count($leads_arr); $i++) {
            $json_obj = array();
            $json_obj['lead_id'] = $leads_arr[$i]['id'];
            $json_obj['lead_name'] = $leads_arr[$i]['name__first_name'] . ' ' . $leads_arr[$i]['name__last_name'];

            if ($leads_arr[$i]['contact_id'] != null) {
                $queryContact = new SugarQuery();
                $queryContact->select(array('id', 'name'));
                $queryContact->from(BeanFactory::getBean('Contacts'), array('team_security' => false));
                $queryContact->where()->equals('id', $leads_arr[$i]['contact_id']);
                $res = $queryContact->execute();

                $lead_to_contact_arr[$i] = $res[0];
                $json_obj['converted'] = true;
                $json_obj['contact_id'] = $leads_arr[$i]['contact_id'];
                $json_obj['contact_name'] = $lead_to_contact_arr[$i]['name__first_name'] . ' ' . $lead_to_contact_arr[$i]['name__last_name'];
            } else {
                $json_obj['converted'] = false;
            }
            if ($leads_arr[$i]['opportunity_id'] != null) {

                $json_obj['opportunity_id'] = $leads_arr[$i]['opportunity_id'];
            }
            if ($leads_arr[$i]['account_id'] != null) {

                $json_obj['account_id'] = $leads_arr[$i]['account_id'];
            }
            $json_arr[] = $json_obj;
        }

        $query3 = new SugarQuery();
        $query3->select(array('id', 'name'));
        $query3->from(BeanFactory::getBean('Contacts'), array('team_security' => false));
        $resContacts = $query3->execute();
        $contacts_arr = array();
        foreach ($resContacts as $contact) {
            if (!in_array($contact, $lead_to_contact_arr)) {
                $contacts_arr[] = $contact;
            }
        }
        for ($i = 0; $i < count($contacts_arr); $i++) {
            $json_obj = array();
            $json_obj['contact_id'] = $contacts_arr[$i]['id'];
            $json_obj['contact_name'] = $contacts_arr[$i]['name__first_name'] . ' ' . $leads_arr[$i]['name__last_name'];

            // $json_obj = json_encode($json_obj);
            $json_arr[] = $json_obj;
        }

        return $json_arr;
    }

}

?>