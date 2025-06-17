<?php

namespace App\Services;

use App\Models\Kosan;
use App\Models\FotoKosan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KosanService
{
    /**
     * Create a new kosan with its relationships
     *
     * @param array $data
     * @param array $facilities
     * @param UploadedFile[] $photos
     * @param UploadedFile $mainPhoto
     * @return Kosan
     * @throws \Throwable
     */
    public function createKosan(array $data, array $facilities, array $photos, ?UploadedFile $mainPhoto = null): Kosan
    {
        return DB::transaction(function () use ($data, $facilities, $photos, $mainPhoto) {
            // Handle main photo upload
            $mainPhotoPath = $this->handleMainPhoto($mainPhoto, $photos);
            
            // Create kosan
            $kosan = Kosan::create(array_merge($data, [
                'foto_utama' => $mainPhotoPath,
                'UniqueID' => $this->generateUniqueId(),
            ]));
            
            // Handle additional photos
            $this->handleAdditionalPhotos($kosan, $photos, (bool)$mainPhoto);
            
            // Attach facilities with like counts and order
            $this->attachFacilities($kosan, $facilities);

            return $kosan;
        });
    }
    
    /**
     * Attach facilities to kosan with like counts and order
     */
    protected function attachFacilities(Kosan $kosan, array $facilities): void
    {
        // Attach kamar facilities with order based on selection order
        if (!empty($facilities['fasilitas_kamar'])) {
            $kamarAttach = [];
            $order = 1; // Start order from 1 for this kosan
            
            foreach ($facilities['fasilitas_kamar'] as $fasilitasId) {
                $kamarAttach[$fasilitasId] = [
                    'order' => $order++, // Increment order for each facility
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $kosan->fasilitas_kamar()->attach($kamarAttach);
        }
        
        // Attach kamar mandi facilities with order based on selection order
        if (!empty($facilities['fasilitas_kamar_mandi'])) {
            $kamarMandiAttach = [];
            $order = 1; // Start order from 1 for this kosan
            
            foreach ($facilities['fasilitas_kamar_mandi'] as $fasilitasId) {
                $kamarMandiAttach[$fasilitasId] = [
                    'order' => $order++, // Increment order for each facility
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $kosan->fasilitas_kamar_mandi()->attach($kamarMandiAttach);
        }
        
        // Attach umum facilities with order based on selection order
        if (!empty($facilities['fasilitas_umum'])) {
            $umumAttach = [];
            $order = 1; // Start order from 1 for this kosan
            
            foreach ($facilities['fasilitas_umum'] as $fasilitasId) {
                $umumAttach[$fasilitasId] = [
                    'order' => $order++, // Increment order for each facility
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $kosan->fasilitas_umum()->attach($umumAttach);
        }
    }

    /**
     * Handle main photo upload
     */
    protected function handleMainPhoto(?UploadedFile $mainPhoto, array $photos): string
    {
        if ($mainPhoto) {
            $path = $mainPhoto->store('kosan', 'public');
            return 'storage/' . $path;
        }
        
        if (count($photos) > 0) {
            $path = $photos[0]->store('kosan', 'public');
            return 'storage/' . $path;
        }
        
        // Use default image if no photos provided
        return $this->ensureDefaultImageExists();
    }

    /**
     * Handle additional photos upload
     */
    protected function handleAdditionalPhotos(Kosan $kosan, array $photos, bool $hasMainPhoto): void
    {
        // Skip the first photo if it was used as main photo
        $startIndex = $hasMainPhoto ? 0 : 1;
        
        for ($i = $startIndex; $i < count($photos); $i++) {
            $path = $photos[$i]->store('kosan', 'public');
            $fotoPath = 'storage/' . $path;
            
            $kosan->foto()->create([
                'path' => $fotoPath,
                'is_primary' => false,
            ]);
        }
    }

    /**
     * Generate a unique ID for kosan
     */
    protected function generateUniqueId(): string
    {
        return 'KSN' . now()->format('YmdHis') . rand(100, 999);
    }

    /**
     * Ensure default image exists and return its path
     */
    protected function ensureDefaultImageExists(): string
    {
        $defaultPath = 'storage/kosan/default.jpg';
        $fullPath = public_path($defaultPath);
        
        if (!file_exists($fullPath)) {
            if (!is_dir(public_path('storage/kosan'))) {
                Storage::disk('public')->makeDirectory('kosan');
            }
            
            if (file_exists(public_path('images/default-kosan.jpg'))) {
                copy(public_path('images/default-kosan.jpg'), $fullPath);
            } else {
                // Create a default image if none exists
                \Intervention\Image\ImageManager::imagick()
                    ->create(800, 600)
                    ->fill('#f3f4f6')
                    ->line(0, 0, 800, 600, function ($draw) {
                        $draw->color('#d1d5db');
                    })
                    ->text('No Image Available', 400, 300, function($font) {
                        $font->file(5);
                        $font->size(24);
                        $font->color('#6b7280');
                        $font->align('center');
                        $font->valign('middle');
                    })
                    ->save($fullPath);
            }
        }
        
        return $defaultPath;
    }
}
