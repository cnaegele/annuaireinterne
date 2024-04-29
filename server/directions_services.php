<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
echo file_get_contents ( '/data/lsldaporganisation/organigramme.json');
