<?php
require_once 'ldap_connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
$samaccountname = trim(isset($_REQUEST['samaccountname'])?$_REQUEST['samaccountname']:'');
$mode = trim(isset($_REQUEST['mode'])?$_REQUEST['mode']:'full');
if ($samaccountname != '' && strpos($samaccountname, '*') === false) {
    $ad = ls_ldap_connect();
    if ($ad !== false) {
        $basedn = 'dc=lausanne,dc=ch';
        $filter = "(&(objectClass=user)(objectCategory=person)(samaccountname=$samaccountname))";
        if ($mode == 'full') {
            $justthese = array("displayname", "title", "sn", "givenname", "mail", "samaccountname", "telephonenumber", "department", "company", "lastlogon", "pwdlastset", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute10", "description", "whencreated", "memberof");
        } elseif ($mode == 'light') {
            $justthese = array("displayname", "title", "sn", "givenname", "mail", "samaccountname", "telephonenumber", "department", "company", "lastlogon", "pwdlastset", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute10");
        } else {
            $justthese = array("displayname", "title", "sn", "givenname", "mail", "samaccountname", "telephonenumber", "department", "company", "lastlogon", "pwdlastset", "extensionattribute1", "extensionattribute2", "extensionattribute3", "extensionattribute10");
        }
        //$justthese = array("*");
        $sr = ldap_search($ad, $basedn, $filter, $justthese);
        $info = ldap_get_entries($ad, $sr);
        //var_dump($info);
        $jsoninfo = json_encode($info);
        ldap_unbind($ad);
        if (isset($info[0]['lastlogon'])) {
            $fileTime_lastlogon = $info[0]['lastlogon'][0];
            if (strlen($fileTime_lastlogon > 15)) {
                $winSecs = (int)($fileTime_lastlogon / 10000000); // divide by 10 000 000 to get seconds
                $unixTimestamp = ($winSecs - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
                $date_lastlogon = date("d.m.Y H:i", $unixTimestamp);
                $jsoninfo = str_replace($fileTime_lastlogon, $date_lastlogon, $jsoninfo);
            }
        }
        if (isset($info[0]['pwdlastset'])) {
            $fileTime_pwdlastset = $info[0]['pwdlastset'][0];
            if (strlen($fileTime_pwdlastset > 15)) {
                $winSecs = (int)($fileTime_pwdlastset / 10000000); // divide by 10 000 000 to get seconds
                $unixTimestamp = ($winSecs - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
                $date_pwdlastset = date("d.m.Y H:i", $unixTimestamp);
                $jsoninfo = str_replace($fileTime_pwdlastset, $date_pwdlastset, $jsoninfo);
            }
        }
        if (isset($info[0]['whencreated'])) {
            $strTime_whencreated = $info[0]['whencreated'][0];
            if (strlen($strTime_whencreated) >= 12 ) {
                $date_whencreated = substr($strTime_whencreated, 6, 2) . '.' . substr($strTime_whencreated, 4, 2) . '.' . substr($strTime_whencreated, 0, 4) . ' ' . substr($strTime_whencreated, 8, 2) . ':' . substr($strTime_whencreated, 10, 2);
                $jsoninfo = str_replace($strTime_whencreated, $date_whencreated, $jsoninfo);
            }
        }
        echo $jsoninfo;
    }
} else {
    echo '{"count":0}';
}
