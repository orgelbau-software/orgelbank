<?php
include_once 'odf.php';

$odf = new odf("tutoriel1.odt");



$odf->saveToDisk("tutoriel1_generated2.odt");