<?php

namespace Workable\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\HRM\Enums\PenaltyRuleEnum;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class PenaltyRuleRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        if ($request->isMethod(RequestAlias::METHOD_POST)) {
            return [
                'rule_name'        => ['required', 'string'],
                'rule_description' => ['nullable', 'string'],
                'config'           => ['required', 'array'],
                'config.name'      => ['required', 'string'],
                'config.value'     => ['required', 'integer'],
                'config.price'     => ['required', 'integer'],
                'type'             => ['nullable', 'integer', 'in:' . implode(',', array_keys(PenaltyRuleEnum::TYPE))],
                'status'           => ['nullable', 'integer', 'in:' . implode(',', array_keys(PenaltyRuleEnum::STATUS))],
            ];
        }

        $validFields = [
            'with'   => ['tenant', 'user', 'approvedBy'],
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

