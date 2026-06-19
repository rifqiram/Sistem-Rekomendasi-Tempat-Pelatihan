<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KeahlianResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'level' => $this->whenPivotLoaded('tabel_peserta_keahlian', fn () => $this->pivot->level),
            'kategori' => new KategoriResource($this->whenLoaded('kategori')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
