<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DisplayEventResource extends JsonResource
{
    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $url = config('app.url') . '/' . $this->slug . '/submit';
        $qrCode = QrCode::size(200)->generate($url);
        return [
            'id' => $this->id,
            'event_name' => $this->event_name,
            'slug' => $this->slug,
            'messages' => DisplayMessageResource::collection($this->messages),
            'qr_code' => 'data:image/svg+xml;base64,' . base64_encode($qrCode),
        ];
    }

}
