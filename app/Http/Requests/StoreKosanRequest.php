<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKosanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update this based on your auth requirements
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic information
            'nama_kosan' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'description' => ['required', 'string', 'max:1000'],
            
            // Numeric fields - match database column names
            'harga' => [
                'required',
                'numeric',
                'min:100000',
            ],
            'jarak_kampus' => [  // Changed from distance_to_campus to match DB
                'required',
                'numeric',
                'min:0.1',
                'max:20',
            ],
            'luas_kamar' => [  // Changed from room_size to match DB
                'required',
                'numeric',
                'min:9',
            ],
            'jumlah_kamar_tersedia' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
            
            // Selection fields
            'tipe_kost' => ['required', Rule::in(['Putri', 'Putra', 'Campur'])],
            'keamanan_id' => ['required', 'exists:keamanan,id'],
            'kebersihan_id' => ['required', 'exists:kebersihan,id'],
            'ventilasi_id' => ['required', 'exists:ventilasi,id'],
            'iuran_id' => ['required', 'exists:iuran,id'],
            'aturan_id' => ['required', 'exists:aturan,id'],
            
            // Facilities arrays
            'fasilitas_kamar' => ['required', 'array', 'min:1'],
            'fasilitas_kamar.*' => ['required', 'integer', 'exists:fasilitas_kamar,id'],
            'fasilitas_kamar_mandi' => ['required', 'array', 'min:1'],
            'fasilitas_kamar_mandi.*' => ['required', 'integer', 'exists:fasilitas_kamar_mandi,id'],
            'fasilitas_umum' => ['required', 'array', 'min:1'],
            'fasilitas_umum.*' => ['required', 'integer', 'exists:fasilitas_umum,id'],
            'akses_lokasi_pendukung' => ['required', 'array', 'min:1'],
            'akses_lokasi_pendukung.*' => ['required', 'integer', 'exists:akses_lokasi_pendukung,id'],
            
            // File upload
            'foto_utama' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:5120',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // General messages
            'required' => 'Kolom :attribute wajib diisi',
            'numeric' => 'Kolom :attribute harus berupa angka',
            'integer' => 'Kolom :attribute harus berupa angka bulat',
            'in' => 'Nilai :attribute tidak valid',
            'exists' => ':attribute yang dipilih tidak valid',
            'array' => 'Format data :attribute tidak valid',
            'image' => 'File harus berupa gambar',
            'mimes' => 'Format file yang didukung: :values',
            'max.file' => 'Ukuran file maksimal 5MB',
            'max.string' => 'Maksimal :max karakter',
            'max.numeric' => 'Nilai maksimal :max',
            'min.numeric' => 'Nilai minimal :min',
            'min.string' => 'Minimal :min karakter',
            'min.file' => 'Ukuran file minimal :min KB',
            'min.array' => 'Pilih minimal :min :attribute',
            
            // Field-specific messages
            'foto_utama.required' => 'Mohon unggah foto kosan',
            'foto_utama.image' => 'File harus berupa gambar',
            'foto_utama.mimes' => 'Format file yang didukung: jpeg, png, jpg, gif',
            'foto_utama.max' => 'Ukuran file maksimal 5MB',
            
            'jarak_kampus.numeric' => 'Jarak ke kampus harus berupa angka',
            'jarak_kampus.min' => 'Jarak ke kampus minimal :min',
            'jarak_kampus.max' => 'Jarak ke kampus maksimal :max',
            
            'luas_kamar.numeric' => 'Luas kamar harus berupa angka',
            'luas_kamar.min' => 'Luas kamar minimal :min m²',
            
            'harga.numeric' => 'Harga sewa harus berupa angka',
            'harga.min' => 'Harga sewa minimal Rp :min',
            
            'fasilitas_kamar.required' => 'Pilih minimal 1 fasilitas kamar',
            'fasilitas_kamar_mandi.required' => 'Pilih minimal 1 fasilitas kamar mandi',
            'fasilitas_umum.required' => 'Pilih minimal 1 fasilitas umum',
        ];
    }
    
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_kosan' => 'nama kosan',
            'alamat' => 'alamat',
            'description' => 'deskripsi',
            'harga' => 'harga sewa per bulan',
            'jarak_kampus' => 'jarak ke kampus',
            'luas_kamar' => 'luas kamar',
            'tipe_kost' => 'tipe kost',
            'jumlah_kamar_tersedia' => 'jumlah kamar tersedia',
            'keamanan_id' => 'tingkat keamanan',
            'kebersihan_id' => 'tingkat kebersihan',
            'ventilasi_id' => 'sistem ventilasi',
            'iuran_id' => 'sistem pembayaran',
            'aturan_id' => 'peraturan kost',
            'fasilitas_kamar' => 'fasilitas kamar',
            'fasilitas_kamar_mandi' => 'fasilitas kamar mandi',
            'fasilitas_umum' => 'fasilitas umum',
            'foto_utama' => 'foto utama',
        ];
    }
}
