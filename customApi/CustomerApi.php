
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
                'path' => array('customerInfo'),
                'pathVars' => array(''),
                'method' => 'getCustomerInfo',
                'shortHelp' => 'This method retrieves stock data.',
                'longHelp' => '',
        ));
    }

    public function getCustomerInfo($api, $args) {

        //return $this->withSugarQuery();
        return $this->withBean();
    }

    public function withSugarQuery() {
        // with sugar query

        $json_arr = array();

        $query = new SugarQuery();
        $query->select(array('id', 'name', 'lead_id'));
        $query->from(BeanFactory::getBean('Prospects'), array('team_security' => false));
        $targets_arr = $query->execute();
        $target_leads = new SplFixedArray(count($targets_arr));
        $target_lead_contact = new SplFixedArray(count($targets_arr));

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
                    $contactBean = BeanFactory::retrieveBean('Contacts', $target_leads[$i]['contact_id'], array('disable_row_level_security' => true));
                    $json_obj['contact_name'] = $contactBean->name;
                    $contactBeanData = array('id' => $contactBean->id, 'name' => $contactBean->name);
                    $target_lead_contact[$i] = $contactBeanData;
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
            if (!$this->find_obj($lead, $target_leads)) {
                $leads_arr[] = $lead;
            }
        }

        // return $leads_arr;
        $lead_to_contact_arr = new SplFixedArray(count($leads_arr));

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
            if (!$this->find_obj($contact, $lead_to_contact_arr) && !$this->find_obj($contact, $target_lead_contact)) {
                $contacts_arr[] = $contact;
            }
        }
        //return $lead_to_contact_arr;
        for ($i = 0; $i < count($contacts_arr); $i++) {
            $json_obj = array();
            $json_obj['contact_id'] = $contacts_arr[$i]['id'];
            $json_obj['contact_name'] = $contacts_arr[$i]['name__first_name'] . ' ' . $contacts_arr[$i]['name__last_name'];

            // $json_obj = json_encode($json_obj);
            $json_arr[] = $json_obj;
        }

        return $json_arr;
    }

    public function withBean() {
        // with sugar bean

        $opportunities_array = array();
        $json_arr = array();

        $targetBean = BeanFactory::newBean('Prospects');
        $targets_arr = $targetBean->get_full_list();

        $target_leads = new SplFixedArray(count($targets_arr));
        $target_lead_contact = new SplFixedArray(count($targets_arr));

        //   if($targets_arr[$i]->load_relationship('lead'))


        for ($i = 0; $i < count($targets_arr); $i++) {
            $json_obj = array();

            $json_obj['target_id'] = $targets_arr[$i]->id;
            $json_obj['target_name'] = $targets_arr[$i]->name;



            if ($targets_arr[$i]->lead_id != null) {
                $targets_arr[$i]->load_relationship('lead');
                $leadBean = $targets_arr[$i]->lead->getBeans();

                $leadBean = array_values($leadBean)[0];
                //$leadBean = BeanFactory::retrieveBean('Leads', $targets_arr[$i]->lead_id, array('disable_row_level_security' => true));
                $leadBeanData = array('id' => $leadBean->id, 'name' => $leadBean->name, 'opportunity_id' => $leadBean->opportunity_id, 'contact_id' => $leadBean->contact_id, 'account_id' => $leadBean->account_id);
                $target_leads[$i] = $leadBeanData;

                $json_obj['converted'] = true;
                $json_obj['lead_id'] = $target_leads[$i]['id'];
                $json_obj['lead_name'] = $target_leads[$i]['name'];


                if ($target_leads[$i]['contact_id'] != null) {
                    $leadBean->load_relationship('contacts');
                    $contactBean = $leadBean->contacts->getBeans();
                    $contactBean = array_values($contactBean)[0];
                    // $bean = BeanFactory::retrieveBean('Leads', $target_leads[$i]->id, array('disable_row_level_security' => true));
                    //$bean->load_relationship('contacts');
                    //$contactBean = $bean->contacts->getBeans();
                    //$GLOBALS['log']->fatal(print_r($contactBean,true));
                    // return;
                    // if(count($contactBean)>0){
                    // $contactBean = array_values($contactBean)[0];

                    $json_obj['contact_id'] = $target_leads[$i]['contact_id'];
                    //$contactBean = BeanFactory::retrieveBean('Contacts', $target_leads[$i]['contact_id'], array('disable_row_level_security' => true));
                    $json_obj['contact_name'] = $contactBean->name;

                    $contactBeanData = array('id' => $contactBean->id, 'name' => $contactBean->name);
                    $target_lead_contact[$i] = $contactBeanData;
                    //}
                }
                if ($target_leads[$i]['opportunity_id'] != null) {

                    $json_obj['opportunity_id'] = $target_leads[$i]['opportunity_id'];
                    $oppBean = BeanFactory::retrieveBean('Opportunities', $target_leads[$i]['opportunity_id'], array('disable_row_level_security' => true));
                    $json_obj['opportunity_name'] = $oppBean->name;
                    $opportunities_array[] = $oppBean->id;
                }

                if ($target_leads[$i]['account_id'] != null) {

                    $json_obj['account_id'] = $target_leads[$i]['account_id'];
                    $accountBean = BeanFactory::retrieveBean('Accounts', $target_leads[$i]['account_id'], array('disable_row_level_security' => true));
                    $json_obj['account_name'] = $accountBean->name;
                }
                //$target_leads[$i] = BeanFactory::retrieveBean('Leads', $target->lead_id, array('disable_row_level_security' => true));
            } else {
                $json_obj['converted'] = false;
            }
            $json_arr[] = $json_obj;
        }

        $leadBean = BeanFactory::newBean('Leads');
        $resLeads = $leadBean->get_full_list();
        $leads = new SplFixedArray(count($resLeads));
        for ($i = 0; $i < count($resLeads); $i++) {

            $leadBeanData = array('id' => $resLeads[$i]->id, 'name' => $resLeads[$i]->name, 'opportunity_id' => $resLeads[$i]->opportunity_id, 'contact_id' => $resLeads[$i]->contact_id, 'account_id' => $resLeads[$i]->account_id);
            $leads[$i] = $leadBeanData;
        }

        $leads_arr = array();
        foreach ($leads as $lead) {

            if (!$this->find_obj($lead, $target_leads)) {
                $leads_arr[] = $lead;
            }
        }

        $lead_to_contact_arr = new SplFixedArray(count($leads_arr));

        for ($i = 0; $i < count($leads_arr); $i++) {
            $json_obj = array();
            $json_obj['lead_id'] = $leads_arr[$i]['id'];
            $json_obj['lead_name'] = $leads_arr[$i]['name'];
            $b = BeanFactory::retrieveBean('Leads', $leads_arr[$i]['id'], array('disable_row_level_security' => true));
            if ($leads_arr[$i]['contact_id'] != null || $leads_arr[$i]['contact_id'] != "") {

                $json_obj['converted'] = true;
                $json_obj['contact_id'] = $leads_arr[$i]['contact_id'];

                $b->load_relationship('contacts');
                $contactBean = $b->contacts->getBeans();
                $contactBean = array_values($contactBean)[0];
                // $contactBean = BeanFactory::retrieveBean('Contacts', $leads_arr[$i]['contact_id'], array('disable_row_level_security' => true));
                $json_obj['contact_name'] = $contactBean->name;
                $contactBeanData = array('id' => $contactBean->id, 'name' => $contactBean->name);

                $lead_to_contact_arr[$i] = $contactBeanData;
            } else {
                $json_obj['converted'] = false;
            }
            if ($leads_arr[$i]['opportunity_id'] != null || $leads_arr[$i]['opportunity_id'] != "") {


                $json_obj['opportunity_id'] = $leads_arr[$i]['opportunity_id'];
                //  $b = BeanFactory::retrieveBean('Leads',$leads_arr[$i]['id'],array('disable_row_level_security' => true));
                $b->load_relationship('opportunity');
                $oppBean = $b->opportunity->getBeans();
                $oppBean = array_values($oppBean)[0];
                // $oppBean = BeanFactory::retrieveBean('Opportunities', $leads_arr[$i]['opportunity_id'], array('disable_row_level_security' => true));
                $json_obj['opportunity_name'] = $oppBean->name;
                $opportunities_array[] = $oppBean->id;
            }
            if ($leads_arr[$i]['account_id'] != null || $leads_arr[$i]['account_id'] != "") {

                $json_obj['account_id'] = $leads_arr[$i]['account_id'];
                // $b = BeanFactory::retrieveBean('Leads',$leads_arr[$i]['id'],array('disable_row_level_security' => true));
                $b->load_relationship('accounts');
                $accountBean = $b->accounts->getBeans();
                $accountBean = array_values($accountBean)[0];
                //  $accountBean = BeanFactory::retrieveBean('Accounts', $leads_arr[$i]['account_id'], array('disable_row_level_security' => true));
                $json_obj['account_name'] = $accountBean->name;
            }
            $json_arr[] = $json_obj;
        }

        $contactBean = BeanFactory::newBean('Contacts');
        $resContacts = $contactBean->get_full_list();
        $contacts = new SplFixedArray(count($resContacts));
        for ($i = 0; $i < count($resContacts); $i++) {

            $contactBeanData = array('id' => $resContacts[$i]->id, 'name' => $resContacts[$i]->name);
            $contacts[$i] = $contactBeanData;
        }

        $contacts_arr = array();
        foreach ($contacts as $contact) {

            if (!$this->find_obj($contact, $lead_to_contact_arr) && !$this->find_obj($contact, $target_lead_contact)) {
                $contacts_arr[] = $contact;
            }
        }


        for ($i = 0; $i < count($contacts_arr); $i++) {
            $json_obj = array();
            $json_obj['contact_id'] = $contacts_arr[$i]['id'];
            $json_obj['contact_name'] = $contacts_arr[$i]['name'];

            $json_arr[] = $json_obj;
        }

        $opps_arr = array();
        $oppoBean = BeanFactory::newBean('Opportunities');
        $resOppo = $oppoBean->get_full_list();
        for ($i = 0; $i < count($resOppo); $i++) {

            if (in_array($resOppo[$i]->id, $opportunities_array)) {
               
            }
            else{
                $opps_arr[] = $resOppo[$i];
            }
        }
        //return count($opps_arr);
        for($i = 0; $i<count($opps_arr);$i++){
            $json_obj = array();
            $json_obj['opportunity_id'] = $opps_arr[$i]->id;
            $json_obj['opportunity_name'] = $opps_arr[$i]->name;
            
             $opps_arr[$i]->load_relationship('accounts');
                $accountBean = $opps_arr[$i]->accounts->getBeans();
                $accountBean = array_values($accountBean)[0];
                // $contactBean = BeanFactory::retrieveBean('Contacts', $leads_arr[$i]['contact_id'], array('disable_row_level_security' => true));
                $json_obj['account_name'] = $accountBean->name;
                $json_obj['account_id'] = $accountBean->id;
               
                
             $opps_arr[$i]->load_relationship('contacts');
             $contactBean = $opps_arr[$i]->contacts->getBeans();
             if(count($contactBean) > 0){
                $contactBean = array_values($contactBean)[0];
                // $contactBean = BeanFactory::retrieveBean('Contacts', $leads_arr[$i]['contact_id'], array('disable_row_level_security' => true));
                $json_obj['contact_name'] = $contactBean->name;
                $json_obj['contact_id'] = $contactBean->id;
                
          
             }
               $json_arr[] = $json_obj;
        }

        return $json_arr;
    }

    public function find_obj($obj, &$arr) {


        for ($j = 0; $j < count($arr); $j++) {
            if ($arr[$j]['id'] == $obj['id']) {
                return true;
            }
        }
        return false;
    }

}

?>