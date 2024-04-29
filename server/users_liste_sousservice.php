<?php
require_once 'ldap_connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
$sousservice = trim(isset($_REQUEST['sousservice'])?$_REQUEST['sousservice']:'');
if ($sousservice != '' && strpos($sousservice, '*') === false) {
    $sousservice = rawurldecode($sousservice);
    $ass = explode('@', $sousservice);
    if (is_array($ass)) {
        if (count($ass) == 2) {
            $service = $ass[0];
            $uniteorg = $ass[1];
            $ad = ls_ldap_connect();
            if ($ad !== false) {
                $service = str_replace(')', '\)', str_replace('(', '\(', $service));
                $uniteorg = str_replace(')', '\)', str_replace('(', '\(', $uniteorg));
                $basedn = 'dc=lausanne,dc=ch';
                $filter = "(&(objectClass=user)(objectCategory=person)(department=$service)(extensionattribute3=$uniteorg))";
                $justthese = array("displayname", "mail", "samaccountname", "telephonenumber", "department", "company", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute10");
                $sr = ldap_search($ad, $basedn, $filter, $justthese);
                $result = array();
                if ($sr) {
                    $info = ldap_get_entries($ad, $sr);
                    ldap_unbind($ad);
                    foreach ($info as $item) {
                        if (((isset($item['samaccountname'])) ? $item['samaccountname'][0] : '') != '') {
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
                }
                sort($result);
                echo json_encode($result);
            }
        }
    }
}
