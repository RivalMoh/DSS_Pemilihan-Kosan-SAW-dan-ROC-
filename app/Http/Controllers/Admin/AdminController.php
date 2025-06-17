<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeightSetting;
use App\Models\AttributeRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        // Get statistics for the dashboard
        $stats = [
            'total_kosan' => \App\Models\Kosan::count(),
            'available_kosan' => \App\Models\Kosan::where('jumlah_kamar_tersedia', '>', 0)->count(),
            'total_users' => \App\Models\User::count(),
            'total_visits' => 0, // Can be implemented with analytics later
            'total_admins' => \App\Models\User::where('is_admin', true)->count(),
        ];

        return view('admin.dashboard_new', compact('stats'));
    }

    /**
     * Show the weight settings page
     */
    public function weightSettings()
    {
        $weights = WeightSetting::orderBy('criteria_name')->get();
        $totalWeight = $weights->sum('weight');
        
        return view('admin.settings.weights', [
            'weights' => $weights,
            'totalWeight' => $totalWeight
        ]);
    }

    /**
     * Update weight settings
     */
    public function updateWeights(Request $request)
    {
        $request->validate([
            'weights' => 'required|array',
            'weights.*' => 'required|numeric|min:0|max:1',
            'criteria_types' => 'required|array',
            'criteria_types.*' => 'required|in:cost,benefit',
        ]);

        $weights = $request->input('weights');
        $criteriaTypes = $request->input('criteria_types');
        $totalWeight = array_sum($weights);

        // Validate that total weight is approximately 1 (allowing for floating point precision)
        if (abs($totalWeight - 1) > 0.0001) {
            return back()->withErrors([
                'total_weight' => 'Total bobot harus sama dengan 1. Total saat ini: ' . number_format($totalWeight, 4)
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($weights as $id => $weight) {
                WeightSetting::where('id', $id)->update([
                    'weight' => $weight,
                    'criteria_type' => $criteriaTypes[$id] ?? 'benefit'
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.settings.weights')
                ->with('success', 'Pengaturan bobot berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pengaturan']);
        }
    }

    /**
     * Show the attribute ranges settings page
     */
    public function attributeRanges()
    {
        $ranges = AttributeRange::all();
        
        return view('admin.settings.attribute-ranges', [
            'ranges' => $ranges
        ]);
    }

    /**
     * Update an attribute range
     */
    public function updateAttributeRange(Request $request, $id)
    {
        $range = AttributeRange::findOrFail($id);
        
        $request->validate([
            'min_value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|gt:min_value',
            'number_of_groups' => 'required|integer|min:2|max:10'
        ]);

        $range->update([
            'min_value' => $request->input('min_value'),
            'max_value' => $request->input('max_value'),
            'number_of_groups' => $request->input('number_of_groups')
        ]);

        return redirect()
            ->route('admin.settings.attribute-ranges')
            ->with('success', 'Rentang ' . $range->display_name . ' berhasil diperbarui');
    }
}
