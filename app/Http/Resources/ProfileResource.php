<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): ?array
    {
        if (is_null($this->resource)) {
            return [];
        }

        return [
            'id' => $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'verified' => $this->verified,
            'fcm_token' => $this->fcm_token,
            'created_at' => $this->created_at,
            'avatar' => !is_null($this->avatar) ? get_full_image_url($this->avatar) : null,
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
