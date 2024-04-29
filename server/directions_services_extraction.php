<?php
function array_iunique($topics) {
    $ltopics = array_map('strtolower', $topics);
    $cleanedTopics = array_unique($ltopics);

    foreach($topics as $key => $value) {
        if(!isset($cleanedTopics[$key])) {
            unset($topics[$key]);
        }
    }
    return $topics;
}

require_once 'ldap_connect.php';
header('Content-type: text/html; charset=utf-8');
$ad = ls_ldap_connect();
if ($ad !== false) {
    echo("\nLogin correct\n");
    $basedn = 'dc=lausanne,dc=ch';

    //Liste des départements (c'est à dire les services)
    $jsonDepartment = '';
    $justthese = array("department");
    $aalpha = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
    for ($i=0; $i<count($aalpha); $i++) {
        $alpha = $aalpha[$i];
        $continue = true;
        $nbrPassage = 0;
        $filterDyn = "(&(objectClass=user)(objectCategory=person)(department=$alpha*)";
        $filter = "$filterDyn)";
        $departement_prec = '';
        while ($continue) {
            $nbrPassage++;
            $departement = '';
            echo "ldap_search: $filter\n";
            if ($alpha == "t") {
                $filterDyn = "$filterDyn(!(department=TEST*))";
            }
            $sr = ldap_search($ad, $basedn, $filter, $justthese, 0, 900);
            $info = ldap_get_entries($ad, $sr);
            foreach ($info as $item) {
                if (isset($item['department'])) {
                    $departement = trim($item['department'][0]);
                    if ($departement != '' && $departement != $departement_prec) {
                        $aDepartement[] = $departement;
                        echo "<br>Services: AJOUT $departement<br>";
                        $departement_prec = $departement;
                        break;
                    }
                }
            }
            if ($departement == '' || $nbrPassage > 100) {
                $continue = false;
            } else {
                $sDummy = "(!(department=" . str_replace(')', '\)', str_replace('(', '\(', $departement)) . "))";
                if (strpos($filterDyn, $sDummy) === false) {
                    $filterDyn .= "(!(department=" . str_replace(')', '\)', str_replace('(', '\(', $departement)) . "))";
                }
                $filter = "$filterDyn)";
            }
         }
    }
    echo "\n################################################" . json_encode($aDepartement) . "\n" ;

    //Liste des extensionattribute1 (c'est à dire les directions)
    $nItem = 0;
    $justthese = array("extensionattribute1");
    foreach ($aDepartement as $departement) {
        $filter = "(&(objectClass=user)(objectCategory=person)(department=" . str_replace(')', '\)', str_replace('(', '\(', $departement)) . ")(extensionattribute1=*))";
        $continue = true;
        $nbrPassage = 0;
        while ($continue) {
            $nbrPassage++;
            $direction = '';
            $sr = ldap_search($ad, $basedn, $filter, $justthese, 0, 900);
            if ($sr) {
                $info = ldap_get_entries($ad, $sr);
                foreach ($info as $item) {
                    if (isset($item['extensionattribute1'])) {
                        $direction = trim($item['extensionattribute1'][0]);
                        if ($direction != '') {
                            $aDirection[] = "$direction";
                            //echo "$direction / $departement<br>";
                            $aDD = array("direction" => $direction, "service" => $departement);
                            $aDirDep[] = $aDD;
                            $continue = false;
                            break;
                        }
                    }
                }
            }
            if ($direction == '' || $nbrPassage > 10) {
                $continue = false;
            }
        }
    }

    //Liste des extensionattribute3 (c'est à dire des sous-services)
    $nItem = 0;
    $justthese = array("extensionattribute3");
    foreach ($aDepartement as $departement) {
        $aSS = array();
        $filter = "(&(objectClass=user)(objectCategory=person)(department=" . str_replace(')', '\)', str_replace('(', '\(', $departement)) . ")(extensionattribute3=*))";
        $sr = ldap_search($ad, $basedn, $filter, $justthese);
        if ($sr) {
            $info = ldap_get_entries($ad, $sr);
            foreach ($info as $item) {
                if (isset($item['extensionattribute3'])) {
                    $sousservice = trim($item['extensionattribute3'][0]);
                    if ($sousservice != '') {
                        $aSS[] = $sousservice;
                    }
                }
            }
        }
        //Ici, on a des saisies identiques exepté majuscule minuscule, on utilise notre fonction pour éliminer les doublons sans tenir compte de la case minuscule majuscule
        $aSS = array_iunique($aSS);
        sort($aSS);
        $aSousService = array();
        for($iss=0; $iss<count($aSS); $iss++) {
            $aSousS = array("libelle" => $aSS[$iss], "id" => $iss+1);
            $aSousService[] = $aSousS;
        }
        $aSSS = array("libelle" => $departement, "niv3" => $aSousService);
        $aServiceSousService[] = $aSSS;
    }
    echo "\n################################################" . json_encode($aServiceSousService) . "\n" ;

    $aDirection = array_unique($aDirection);
    $aDirectionOrder = array('d1', 'd2', 'd3', 'd4', 'd5', 'd6', 'd7');
    foreach ($aDirection as $adir) {
        switch ($adir) {
            case "Culture et développement urbain":
                $aDirectionOrder[0] = $adir;
                break;
            case "Sécurité et économie":
                $aDirectionOrder[1] = $adir;
                break;
            case "Sports et cohésion sociale":
                $aDirectionOrder[2] = $adir;
                break;
            case "Logement, environnement et architecture":
                $aDirectionOrder[3] = $adir;
                break;
            case "Enfance, jeunesse et quartiers":
                $aDirectionOrder[4] = $adir;
                break;
            case "Finances et mobilité":
                $aDirectionOrder[5] = $adir;
                break;
            case "Services industriels":
                $aDirectionOrder[6] = $adir;
                break;
            default:
                $aDirectionOrder[] = $adir;
                break;
        }
    }
    echo "\n################################################\n" . json_encode($aDirectionOrder);
    echo "\n################################################\n" . json_encode($aDirDep);

    //Ici on fait le array des directions qui contiennent chacune le array de leur services
    for ($idir = 0; $idir < count($aDirectionOrder); $idir++) {
        $libelleDir = $aDirectionOrder[$idir];
        $aNiv1 = array("libelle" => $libelleDir, "id" => $idir+1);
        $aService = array();
        for ($idirdep = 0; $idirdep < count($aDirDep); $idirdep++) {
            if ($aDirDep[$idirdep]['direction'] == $libelleDir) {
                $libelleService = $aDirDep[$idirdep]['service'];
                for ($iserv=0; $iserv<count($aServiceSousService); $iserv++) {
                    if ($libelleService == $aServiceSousService[$iserv]['libelle']) {
                        $aNiv3 = $aServiceSousService[$iserv]['niv3'];
                        break;
                    }
                }
                $aNiv2 = array("libelle" => $aDirDep[$idirdep]['service'], "id" => $idirdep+1, "niv3" => $aNiv3);
                $aService[] = $aNiv2;
            }
        }
        sort($aService);
        $aDirectionService = array("niv1" => $aNiv1, "niv2" => $aService);
        unset($aService);
        $aResult[] = $aDirectionService;
    }
    echo "\n################################################\n" . json_encode($aResult);
    file_put_contents('/data/lsldaporganisation/organigramme.json', json_encode($aResult));
    ldap_unbind($ad);
}
