<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
$direction_servicesSousService = file_get_contents ( '/data/lsldaporganisation/organigramme.json');
$o_direction_servicesSousService = json_decode($direction_servicesSousService);
$directions = array();
$iDir = 0;
foreach ($o_direction_servicesSousService as $direction) {
    $directions[$iDir]['libelle'] = $direction->niv1->libelle;
    $services = array();
    $iSer = 0;
    foreach ($direction->niv2 as $service) {
        $services[$iSer]['libelle'] = $direction->niv2[$iSer]->libelle;
        $sousServices = array();
        $iSSer = 0;
        foreach ($direction->niv2[$iSer]->niv3 as $sousService) {
            $sousServices[$iSSer]['libelle'] = $sousService->libelle;
            $iSSer++;
        }
        $services[$iSer]['sousservices'] = $sousServices;
        unset($sousServices);
        $iSer++;
    }
    $directions[$iDir]['services'] = $services;
    unset($services);
    $iDir++;
}
echo json_encode($directions);
unset($directions);
