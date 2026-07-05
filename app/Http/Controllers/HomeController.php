<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\TrainingCenter;
use App\Models\Recommendation;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = User::where('role', 'user')->count();
        $centerCount = TrainingCenter::count();
        $recommendationCount = Recommendation::count();
        $criteriaCount = 5; // Asumsi kriteria penilaian yang dipakai sistem (Minat, Skill, Lokasi, dll)

        // Ambil 6 Lembaga Pelatihan (Training Center) secara acak untuk ditampilkan di Landing Page
        $featuredCenters = TrainingCenter::where('status', 'active')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Ambil kategori pelatihan secara dinamis dengan mengagregasi sub-kategori
        $trainingCategories = DB::table('tabel_pelatihan')
            ->select('interest_category as title', DB::raw('count(*) as count'), DB::raw('GROUP_CONCAT(DISTINCT kategori SEPARATOR ", ") as sub_categories'))
            ->whereNotNull('interest_category')
            ->groupBy('interest_category')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // Memformat string subkategori agar tidak terlalu panjang (misal: "Web Dev, Android, +2 lainnya")
        foreach ($trainingCategories as $cat) {
            $subs = array_filter(array_map('trim', explode(',', $cat->sub_categories)));
            if (count($subs) > 2) {
                $cat->desc = $subs[0] . ', ' . $subs[1] . ' & ' . (count($subs) - 2) . ' lainnya';
            } else {
                $cat->desc = implode(', ', $subs);
            }
        }

        return view('welcome', compact('userCount', 'centerCount', 'recommendationCount', 'criteriaCount', 'featuredCenters', 'trainingCategories'));
    }
}
