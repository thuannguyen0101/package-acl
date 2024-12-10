<?php

namespace Workable\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class ConfigSettingAttendanceRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;

    protected $user;

    public function __construct(
        Tenant $tenant,
        User   $user
    )
    {
        parent::__construct();
        $this->tenant = $tenant;
        $this->user   = $user;
    }

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
        if ($request->isMethod(RequestAlias::METHOD_POST)) {
            return [
                'shift_start_time'        => ['required', 'date_format:H:i'],
                'break_start_time'        => ['required', 'date_format:H:i'],
                'break_end_time'          => ['required', 'date_format:H:i'],
                'shift_end_time'          => ['required', 'date_format:H:i'],
                'full_time_minimum_hours' => ['nullable', 'integer', 'min:1'],
                'exclude_weekends'        => ['nullable', 'array'],
                'half_day_weekends'       => ['nullable', 'array'],
            ];
        }

        $validFields = [
            'with'   => ['tenant', '',],
            'user'   => $this->user->getFillable(),
            'tenant' => $this->tenant->getFillable(),
        ];

        return [
            'with'                   => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.user'       => ['nullable', new ValidFields('user', $validFields['user'])],
            'with_fields.tenant'     => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.approvedBy' => ['nullable', new ValidFields('approvedBy', $validFields['user'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
