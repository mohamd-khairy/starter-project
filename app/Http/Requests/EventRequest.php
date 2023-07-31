<?php

namespace App\Http\Requests;

use App\Enums\EventTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'date' => 'required',
            'image' => 'required|string',
            'detections' => 'required|array',
            'detections.*.box' => 'required|array',
            'detections.*.type' => ['required', new Enum(EventTypeEnum::class)],
            'detections.*.position' => ['nullable'],
        ];
    }
}
