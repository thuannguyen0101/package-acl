<?php

namespace Workable\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\HRM\Models\Attendance;
use Workable\HRM\Models\PenaltyRule;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class PenaltyRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;

    protected $user;

    protected $attendance;
    protected $penaltyRule;

    public function __construct(
        Tenant      $tenant,
        User        $user,
        Attendance  $attendance,
        PenaltyRule $penaltyRule
    )
    {
        parent::__construct();
        $this->tenant      = $tenant;
        $this->user        = $user;
        $this->attendance  = $attendance;
        $this->penaltyRule = $penaltyRule;
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
    public function rules(Request $request): array
    {
        if ($request->isMethod(RequestAlias::METHOD_POST)) {
            $userRule    = Rule::exists('users', 'id')
                ->where('tenant_id', get_tenant_id());
            $attendances = Rule::exists('attendances', 'id')
                ->where('tenant_id', get_tenant_id());

            return [
                'attendance_id' => ['nullable', 'integer', $attendances],
                'rule_id'       => ['required', 'integer'],
                'user_id'       => ['required', $userRule],
                'fine_type'     => ['required', 'integer'],
                'amount'        => ['required', 'integer'],
                'status'        => ['required', 'integer', 'in:' . implode(',', array_keys(PenaltyRuleEnum::STATUS))],
                'note'          => ['nullable', 'string'],
            ];
        }

        $validFields = [
            'with'        => ['tenant', 'user', 'attendance', 'penaltyRule', 'createdBy', 'updatedBy'],
            'tenant'      => $this->tenant->getFillable(),
            'user'        => $this->user->getFillable(),
            'attendance'  => $this->attendance->getFillable(),
            'penaltyRule' => $this->penaltyRule->getFillable(),
        ];

        return [
            'with'                    => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.tenant'      => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.user'        => ['nullable', new ValidFields('user', $validFields['user'])],
            'with_fields.attendance'  => ['nullable', new ValidFields('attendance', $validFields['attendance'])],
            'with_fields.penaltyRule' => ['nullable', new ValidFields('penaltyRule', $validFields['penaltyRule'])],
            'with_fields.createdBy'   => ['nullable', new ValidFields('createdBy', $validFields['user'])],
            'with_fields.updatedBy'   => ['nullable', new ValidFields('updatedBy', $validFields['user'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
