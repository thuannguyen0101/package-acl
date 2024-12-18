<?php

namespace Workable\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Workable\Contract\Enums\CRMContractEnum;
use Workable\Customers\Models\Customer;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class CRMContractRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;

    protected $user;

    protected $customer;

    public function __construct(
        Tenant   $tenant,
        User     $user,
        Customer $customer
    )
    {
        parent::__construct();
        $this->tenant   = $tenant;
        $this->user     = $user;
        $this->customer = $customer;
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

            $customersRule = Rule::exists('customers', 'id')
                ->where('tenant_id', get_tenant_id());

            return [
                'customer_id'    => ['required', $customersRule],
                'contract_name'  => ['required', 'string'],
                'start_date'     => ['required', 'date_format:Y-m-d'],
                'end_date'       => ['required', 'date_format:Y-m-d', 'after:start_date'],
                'payment'        => ['required', 'integer'],
                'status'         => ['nullable', 'integer', 'in:' . implode(',', array_keys(CRMContractEnum::STATUS_TEXT))],
                'payment_notes'  => ['nullable', 'string'],
                'discount_total' => ['nullable', 'integer'],
                'created_by'     => ['nullable', 'integer', $userRule],
            ];
        }

        $validFields = [
            'with'      => ['tenant', 'customer', 'createdBy', 'updatedBy'],
            'createdBy' => $this->user->getFillable(),
            'tenant'    => $this->tenant->getFillable(),
            'customer'  => $this->customer->getFillable(),
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.tenant'    => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.customer'  => ['nullable', new ValidFields('customer', $validFields['customer'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
