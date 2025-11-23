<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_product' => $this->id_product,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'image' => $this->image ? asset('storage/products/' . $this->image) : null,
            'price' => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'stock' => $this->stock,
        ];
    }
}
