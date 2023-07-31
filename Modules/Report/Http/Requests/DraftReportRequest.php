<?php

namespace Modules\Report\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * @property mixed $report_list
 */
class DraftReportRequest extends FormRequest
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
            'title' => 'required|string|max:60',
            'time_type' => 'required|in:dynamic,specific',
            'time_range' => 'sometimes|nullable|required_if:time_type,dynamic',
            'report_list' => ['sometimes', 'required', 'in:total,actions,status,types'],
            'types' => 'nullable|required_if:report_list,types',
            'types.*' => ['required'], //, new Enum(EventTypeEnum::class),
            'chart_types' => ['sometimes', 'nullable'],
            'chart_types.*' => 'required|in:' . implode(',', config('report.chart_types')),
            'type' => 'sometimes|required|in:specific,comparison',
            'site_ids' => 'nullable',
            'site_ids.*' => ['required', Rule::exists('locations', 'id')],
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
