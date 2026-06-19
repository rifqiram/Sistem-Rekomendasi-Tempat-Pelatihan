<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'kategori' => $this->kategori,
            'level' => $this->level,
            'durasi' => $this->durasi,
            'sertifikat' => $this->sertifikat,
            'mentor' => new MentorResource($this->whenLoaded('mentor')),
            'keahlian' => KeahlianResource::collection($this->whenLoaded('keahlians')),
            'tanggal_mulai' => $this->tanggal_mulai?->toDateString(),
            'tanggal_selesai' => $this->tanggal_selesai?->toDateString(),
            'is_active' => (bool) $this->is_active,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
