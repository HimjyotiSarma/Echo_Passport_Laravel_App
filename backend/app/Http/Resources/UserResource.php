<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => $this->when($request->user()->isAdmin(), function(){
                return $this->email_verified_at != null;
            }),
            'created_at' => $this->when($request->user()->isAdmin(), $this->created_at->toIso8601String()),
            'updated_at' => $this->when($request->user()->isAdmin(), $this->updated_at->toIso8601String())
        ];
    }
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function with(Request $request)
    {
        return [
            'success' => true,
            'status' => 200,
            'message' => 'User fetched successfully'
        ];
    }
}
