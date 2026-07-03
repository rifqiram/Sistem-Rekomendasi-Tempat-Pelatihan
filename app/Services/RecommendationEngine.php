<?php

namespace App\Services;

use App\Models\Pelatihan;
use App\Models\Profile;
use App\Models\QuestionnaireResponse;
use App\Models\Recommendation;
use App\Models\TrainingCenter;

class RecommendationEngine
{
    /**
     * Jalankan Recommendation Engine untuk user.
     * Mengikuti 3 Fase dari AGENTS.md
     */
    public function generateForUser(int $userId): void
    {
        $profile = Profile::where('user_id', $userId)->first();
        $questionnaire = QuestionnaireResponse::where('user_id', $userId)->first();

        if (!$profile || !$questionnaire) {
            return; // Profil/Kuesioner belum lengkap, skip.
        }

        $answers = json_decode($questionnaire->answers, true) ?? [];

        // Fase 1: Hard Filter
        $eligibleTrainings = $this->applyHardFilter($answers);

        if ($eligibleTrainings->isEmpty()) {
            // Bersihkan rekomendasi lama jika tidak ada yang eligible
            Recommendation::where('user_id', $userId)->delete();
            return;
        }

        // Fase 2: Weighted Score & Aggregate ke Training Center
        $scoredCenters = $this->calculateWeightedScore($eligibleTrainings, $answers, $profile);

        // Fase 3: Persist Recommendation
        $this->persistRecommendations($userId, $scoredCenters);
    }

    private function applyHardFilter(array $answers)
    {
        // Status Aktif: is_active = true
        // Pastikan pelatihan memiliki training_center_id
        $query = Pelatihan::where('is_active', true)
            ->whereNotNull('training_center_id');

        // Bidang Pelatihan
        if (isset($answers['bidang_diminati'])) {
            $query->where('interest_category', $answers['bidang_diminati']);
        }

        // Skill Level
        if (isset($answers['tingkat_keahlian'])) {
            $userSkill = strtolower($answers['tingkat_keahlian']);
            if ($userSkill === 'beginner') {
                $query->whereIn('required_skill', ['Beginner', 'beginner']);
            } elseif ($userSkill === 'intermediate') {
                $query->whereIn('required_skill', ['Beginner', 'beginner', 'Intermediate', 'intermediate']);
            }
            // Advanced can access everything
        }

        // Metode
        if (isset($answers['metode_pelatihan'])) {
            $query->where(function($q) use ($answers) {
                $q->where('method', $answers['metode_pelatihan'])
                  ->orWhereNull('method')
                  ->orWhere('method', 'Hybrid'); // Hybrid cocok dengan online/offline
            });
        }

        return $query->get();
    }

    private function calculateWeightedScore($trainings, array $answers, Profile $profile)
    {
        $centerScores = [];
        $distanceService = new DistanceService();

        // Ambil data training centers sekaligus
        $tcIds = $trainings->pluck('training_center_id')->unique();
        $trainingCenters = TrainingCenter::whereIn('id', $tcIds)->get()->keyBy('id');

        foreach ($trainings as $training) {
            $score = 0;

            // Bidang (35%)
            if (isset($answers['bidang_diminati']) && strtolower($training->interest_category) === strtolower($answers['bidang_diminati'])) {
                $score += 35;
            }

            // Skill (20%)
            if (isset($answers['tingkat_keahlian']) && strtolower($training->required_skill) === strtolower($answers['tingkat_keahlian'])) {
                $score += 20;
            }

            // Metode (15%)
            if (isset($answers['metode_pelatihan']) && strtolower($training->method) === strtolower($answers['metode_pelatihan'])) {
                $score += 15;
            } elseif (strtolower($training->method) === 'hybrid') {
                $score += 10; // Partial match
            }

            // Popularitas (10%)
            $popularityVal = min(100, max(0, $training->popularity ?? 0));
            $score += ($popularityVal / 100) * 10;

            // Aggregate ke Training Center: ambil skor base pelatihan tertinggi di TC tersebut
            $tcId = $training->training_center_id;
            if (!isset($centerScores[$tcId])) {
                $centerScores[$tcId] = $score;
            } else {
                $centerScores[$tcId] = max($centerScores[$tcId], $score);
            }
        }

        $scored = [];
        foreach ($centerScores as $tcId => $baseScore) {
            $tc = $trainingCenters->get($tcId);
            $distanceKm = null;
            $finalScore = $baseScore;

            // Hitung Distance (20%) jika koordinat tersedia
            if ($tc && $profile->latitude && $profile->longitude && $tc->latitude && $tc->longitude) {
                $distanceKm = $distanceService->calculateDistance(
                    $profile->latitude, $profile->longitude,
                    $tc->latitude, $tc->longitude
                );

                // Asumsi max jarak relevan 100km. Jika > 100km, skor 0. Jika 0km, skor penuh (20).
                $maxDistance = 100;
                $distScore = max(0, (1 - ($distanceKm / $maxDistance)) * 20);
                $finalScore += $distScore;
            }

            $scored[] = [
                'training_center_id' => $tcId,
                'score' => round($finalScore, 2),
                'distance' => $distanceKm
            ];
        }

        // Urutkan descending berdasarkan final score
        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $scored;
    }

    private function persistRecommendations(int $userId, array $scoredCenters): void
    {
        // Hapus rekomendasi lama milik user
        Recommendation::where('user_id', $userId)->delete();

        // Ambil Top 5
        $topN = array_slice($scoredCenters, 0, 5);
        $rank = 1;

        foreach ($topN as $item) {
            Recommendation::create([
                'user_id' => $userId,
                'training_center_id' => $item['training_center_id'],
                'score' => $item['score'],
                'distance' => $item['distance'],
                'rank' => $rank++
            ]);
        }
    }
}
