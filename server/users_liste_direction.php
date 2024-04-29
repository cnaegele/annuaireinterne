<?php
require_once 'ldap_connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
$direction = trim(isset($_REQUEST['direction'])?$_REQUEST['direction']:'');
if ($direction != '' && strpos($direction, '*') === false) {
    $direction = rawurldecode($direction);
    $organigramme = json_decode(file_get_contents ( '/data/lsldaporganisation/organigramme.json'));
    $filterServices = "";
    for ($i=0; $i<count($organigramme); $i++) {
        if ($organigramme[$i]->niv1->libelle == $direction) {
            $services = $organigramme[$i]->niv2;
            $filterServices = "(|";
                for ($j=0; $j<count($services); $j++) {
                    $filterServices .= "(department=" . $services[$j]->libelle . ")";
                }
            $filterServices .= ")";
            break;
        }
    }
    if ($filterServices != "") {
        $ad = ls_ldap_connect();
        if ($ad !== false) {
            $basedn = 'dc=lausanne,dc=ch';
            $filter = "(&(objectClass=user)(objectCategory=person)$filterServices)";
            $justthese = array("displayname", "mail", "samaccountname", "telephonenumber", "department", "company", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute10");
            $sr = ldap_search($ad, $basedn, $filter, $justthese);
            $info = ldap_get_entries($ad, $sr);
            ldap_unbind($ad);
            $result = array();
            foreach ($info as $item) {
                if (((isset($item['samaccountname']))? $item['samaccountname'][0] : '') != '') {
                    $userdata = array(
                        "displayname" => (isset($item['displayname'])) ? $item['displayname'][0] : '',
                        //"telephonenumber" => (isset($item['telephonenumber'])) ? str_replace('+41 21 315 ', '', $item['telephonenumber'][0]) : '',
                        "telephonenumber" => (isset($item['telephonenumber'])) ? $item['telephonenumber'][0] : '',
                        "samaccountname" => (isset($item['samaccountname'])) ? $item['samaccountname'][0] : '',
                        "company" => (isset($item['company'])) ? $item['company'][0] : '',
                        "department" => (isset($item['department'])) ? $item['department'][0] : '',
                        "mail" => (isset($item['mail'])) ? $item['mail'][0] : '',
                        "extensionattribute1" => (isset($item['extensionattribute1'])) ? $item['extensionattribute1'][0] : '',
                        "extensionattribute2" => (isset($item['extensionattribute2'])) ? $item['extensionattribute2'][0] : '',
                        "extensionattribute3" => (isset($item['extensionattribute3'])) ? $item['extensionattribute3'][0] : '',
                        "extensionattribute10" => (isset($item['extensionattribute10'])) ? $item['extensionattribute10'][0] : '');
                    $user = array("user" => $userdata);
                    $result[] = $user;
                }
            }
            sort($result);
            echo json_encode($result);
        }
    }
}
