<?php

namespace Botble\Agency\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AgencyRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'        => 'required',
            'description'    => '',
            'status'      => Rule::in(BaseStatusEnum::values()),
            'email'      => 'required|max:60|min:6|email',
            
        ];
    }
}
