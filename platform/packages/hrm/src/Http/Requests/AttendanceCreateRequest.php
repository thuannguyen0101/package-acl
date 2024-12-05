<?php


namespace Workable\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Workable\HRM\Enums\AttendanceEnum;

class AttendanceCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules(Request $request)
    {
        $userRule = Rule::exists('users', 'id')
            ->where('tenant_id', get_tenant_id());

        return [
            'user_id'           => ['required', $userRule],
            'date'              => ['required', 'date_format:Y-m-d'],
            'check_in'          => ['required', 'date_format:H:i:s'],
            'check_out'         => ['required', 'date_format:H:i:s', 'after:check_in'],
            'work'              => ['required', 'numeric', 'in:' . implode(',', array_keys(AttendanceEnum::WORK_TEXT))],
            'work_shift'        => ['required', 'integer', 'in:' . implode(',', array_keys(AttendanceEnum::SHIFT_TEXT))],
            'attendance_status' => ['required', 'integer', 'in:' . implode(',', array_keys(AttendanceEnum::STATUS_TEXT))],
            'late'              => ['nullable', 'integer'],
            'early'             => ['nullable', 'integer'],
            'note'              => ['nullable', 'string'],
            'overtime'          => ['nullable', 'integer'],
        ];

    }

    public function messages(): array
    {
        return [];
    }
}
