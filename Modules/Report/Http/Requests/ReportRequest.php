<?php

namespace Modules\Report\Http\Requests;

use App\Enums\EventTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

/**
 * @property mixed $report_list
 */
class ReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'model_type' => 'required|in:Detection,Drone',
            'type' => 'sometimes|required|in:specific,comparison',
            'time_type' => 'sometimes|required|in:dynamic,specific',
            'report_list' => ['sometimes', 'required', 'in:total,actions,status,types'],
            'site_ids.*' => ['required', Rule::exists('locations', 'id')],
            'time_range' => 'nullable|required_if:time_type,dynamic',
            'types' => 'nullable|required_if:report_list,types',
            'types.*' => ['required'], //, new Enum(EventTypeEnum::class)
        ];

        if (request('time_type') == 'specific') {
            $rules += [
                'start' => [
                    'required', 'date', 'date_format:Y-m-d', 'before:end'
                ],
                'end' => [
                    'required', 'date', 'date_format:Y-m-d', 'after:start'
                ]
            ];
        }


        return $rules;
    }

    public function messages(): array
    {
        return [
            'site_id.required_if' => trans('dashboard.site_required'),
            'site_ids.required_if' => trans('dashboard.site_required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(responseFail($validator->errors()->first(), 422));
    }
}
