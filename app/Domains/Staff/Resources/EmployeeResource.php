<?php

namespace App\Domains\Staff\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'position'          => $this->position,
            'salary'            => $this->salary,
            'hire_date'         => $this->hire_date?->toDateString(),
            'status'            => $this->status,
            'emergency_contact' => $this->emergency_contact,
            'notes'             => $this->notes,
            'shifts'            => $this->shifts,
            'user'              => $this->whenLoaded('user', fn () => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
