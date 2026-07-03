<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Tampilkan profile user yang sedang login.
     */
    public function show(Request $request)
    {
        $profile = Profile::where('user_id', $request->user()->id)->first();

        if (!$profile) {
            return $this->errorResponse('Profile belum lengkap', 404);
        }

        return $this->successResponse($profile, 'Profile berhasil diambil');
    }

    /**
     * Simpan atau update profile user.
     */
    public function store(StoreProfileRequest $request)
    {
        $data = $request->validated();

        $profile = Profile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return $this->successResponse($profile, 'Profile berhasil disimpan');
    }
}
