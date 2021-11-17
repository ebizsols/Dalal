<?php

namespace Botble\Agency\Http\Resources;
use Botble\Agency\Models\Agency;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Agency
 */
class AgencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'avatar'      => $this->avatar_url,
            'description' => $this->description,
            
        ];
    }
}
