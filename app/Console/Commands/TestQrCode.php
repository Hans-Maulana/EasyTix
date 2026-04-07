<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestQrCode extends Command
{
    protected $signature = 'test:qrcode';
    protected $description = 'Test QR Code generation';

    public function handle()
    {
        try {
            // Test SVG
            QrCode::size(100)->generate('test', storage_path('app/test.svg'));
            $this->info('SVG generated successfully.');
            
            // Test PNG
            QrCode::format('png')->size(100)->generate('test', storage_path('app/test.png'));
            $this->info('PNG generated successfully.');
        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
        }
    }
}
