<?php

namespace Workable\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Workable\HRM\Enums\InsuranceEnum;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class InsuranceRequest extends FormRequest
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
            $userRule = Rule::exists('users', 'id')
                ->where('tenant_id', get_tenant_id());

            return [
                'user_id'            => ['required', $userRule],
                'number_insurance'   => ['required', 'string'],
                'start_insurance'    => ['required', 'date_format:Y-m-d'],
                'date_closing'       => ['nullable', 'date_format:Y-m-d'],
                'sent_date'          => ['nullable', 'date_format:Y-m-d'],
                'return_date'        => ['nullable', 'date_format:Y-m-d'],
                'treatment_location' => ['nullable', 'string'],
                'status'             => ['nullable', 'in:' . implode(',', array_keys(InsuranceEnum::STATUS))],
                'note'               => ['nullable', 'string'],
            ];
        }

        $validFields = [
            'with'   => ['tenant', 'user'],
            'user'   => $this->user->getFillable(),
            'tenant' => $this->tenant->getFillable(),
        ];

        return [
            'with'               => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.user'   => ['nullable', new ValidFields('user', $validFields['user'])],
            'with_fields.tenant' => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
