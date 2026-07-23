<?php

if(!file_exists("vendor/autoload.php")) {
    die("execute composer install in lib/qrcode first");
}

include_once 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;


if(!isset($_GET['data']) || $_GET['data'] == "") {
    die("no data provided");
}
$data = $_GET['data'];

$builder = new Builder(
    writer: new PngWriter(),
    writerOptions: [],
    validateResult: false,
    data: $data,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    size: 300,
    margin: 0,
    roundBlockSizeMode: RoundBlockSizeMode::Margin,
    logoResizeToWidth: 50,
    logoPunchoutBackground: true,
    labelFont: new OpenSans(20),
    labelAlignment: LabelAlignment::Center
);

$result = $builder->build();
header('Content-Type: image/png');
echo $result->getString();