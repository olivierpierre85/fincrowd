<?php
$documentFileName = getcwd().'\convention_de_pret_modele_v2.pdf';
echo $documentFileName ;
echo file_get_contents($documentFileName);
echo base64_encode(file_get_contents($documentFileName));
