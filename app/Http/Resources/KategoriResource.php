<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KategoriResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
