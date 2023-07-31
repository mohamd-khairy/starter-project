<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissions = DB::table('role_has_permissions')->select('permissions.id', 'permissions.name')
            ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->whereIn('role_id', $this->roles->pluck('id'))
            ->distinct()
            ->pluck('name');

        return [
            "id" => $this->id ??  null,
            "name" =>  $this->name ?? null,
            "username" => $this->username ?? null,
            "avatar" => $this->avatar ?? null,
            "phone" => $this->phone ?? null,
            "email" => $this->email ?? null,
            "email_verified_at" => $this->email_verified_at ?? null,
            "created_at" => $this->created_at ?? null,
            "address1" => $this->address1 ?? null,
            "address2" => $this->address2 ?? null,
            "postal_code" => $this->postal_code ?? null,
            "website" => $this->website ?? null,
            "city" => $this->city ?? null,
            "region" => $this->region ?? null,
            "country" => $this->country ?? null,
            "gender" => $this->gender ?? null,
            "status" => $this->status ?? null,
            "last_login" => $this->last_login ?? null,
            "roles" => JsonDataResource::collection($this->roles) ?? null,
            'permissions' => $permissions ?? null,
        ];
    }
}
