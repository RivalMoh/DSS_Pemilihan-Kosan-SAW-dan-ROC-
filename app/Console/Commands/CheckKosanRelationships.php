<?php

namespace App\Console\Commands;

use App\Models\Kosan;
use Illuminate\Console\Command;

class CheckKosanRelationships extends Command
{
    protected $signature = 'kosan:check-relationships';
    protected $description = 'Check kosan facility relationships';

    public function handle()
    {
        $kosans = Kosan::with(['fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_umum'])->take(3)->get();

        if ($kosans->isEmpty()) {
            $this->error('No kosan found in the database.');
            return 1;
        }

        foreach ($kosans as $kosan) {
            $this->info("\nKosan: {$kosan->nama} (ID: {$kosan->UniqueID})");
            
            $this->info('Fasilitas Kamar: ' . $kosan->fasilitas_kamar->pluck('nama_fasilitas')->implode(', '));
            $this->info('Fasilitas Kamar Mandi: ' . $kosan->fasilitas_kamar_mandi->pluck('nama_fasilitas')->implode(', '));
            $this->info('Fasilitas Umum: ' . $kosan->fasilitas_umum->pluck('nama_fasilitas')->implode(', '));
            $this->line(str_repeat('-', 50));
        }

        return 0;
    }
}
