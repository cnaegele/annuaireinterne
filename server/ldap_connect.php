<?php
function ls_ldap_connect() {
    $a_config = parse_ini_file('/data/config/goeland.ini', true);
    $host = $a_config['production']['ldap.host'];
    $port = $a_config['production']['ldap.port'];;
    $user = $a_config['production']['ldap.user'];;
    $password = $a_config['production']['ldap.password'];;
    $ad = ldap_connect($host, $port) or die('Could not connect to LDAP server.');
    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
    if (!@ldap_bind($ad, $user, $password)) {
        //echo "<p>Error:" . ldap_error($ad) . "</p>";
        //echo "<p>Error number:" . ldap_errno($ad) . "</p>";
        //echo "<p>Error:" . ldap_err2str(ldap_errno($ad)) . "</p>";
        return false;
    } else {
        return $ad;
    }
}
