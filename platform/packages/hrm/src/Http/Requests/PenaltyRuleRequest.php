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
                'config'           => ['required', 'array'],
                'type'             => ['required', 'integer', 'in:' . implode(',', array_keys(PenaltyRuleEnum::TYPE))],
                'config.name'      => ['required', 'string'],
                'config.value'     => ['required', 'integer'],
                'config.price'     => ['required', 'integer'],
                'rule_description' => ['nullable', 'string'],
                'status'           => ['nullable', 'integer', 'in:' . implode(',', array_keys(PenaltyRuleEnum::STATUS))],
            ];
        }

        $validFields = [
            'with'      => ['tenant', 'createdBy', 'updatedBy'],
            'tenant'    => $this->tenant->getFillable(),
            'createdBy' => $this->user->getFillable(),
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.tenant'    => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}

