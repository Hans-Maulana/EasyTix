<?php

require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

try {
    $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([])
        ->data('Custom QR code contents')
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(300)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->build();

    // Directly save it
    $result->saveToFile(__DIR__.'/test_endroid_qr.png');
    echo "PNG generated successfully via endroid/qr-code\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
