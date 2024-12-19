<?php

namespace Workable\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Contract\Enums\TransactionEnum;
use Workable\Contract\Models\CRMContract;
use Workable\Customers\Models\Customer;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;

class TransactionRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;

    protected $user;

    protected $customer;

    protected $contract;

    public function __construct(
        Tenant      $tenant,
        User        $user,
        Customer    $customer,
        CRMContract $contract
    )
    {
        parent::__construct();
        $this->tenant   = $tenant;
        $this->user     = $user;
        $this->customer = $customer;
        $this->contract = $contract;
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

            $contractRule = Rule::exists('crm_contracts', 'id')
                ->where('tenant_id', get_tenant_id());

            return [
                'contract_id'  => ['required', 'integer', $contractRule],
                'customer_id'  => ['required', 'integer', $customersRule],
                'amount'       => ['required', 'integer'],
                'deductions'   => ['required', 'string'],
                'total_amount' => ['required', 'integer'],
                'status'       => ['nullable', 'integer', 'in:' . implode(',', array_keys(TransactionEnum::STATUS_TEXT))],
                'created_by'   => ['nullable', 'integer', $userRule],
            ];
        }

        $validFields = [
            'with'      => ['tenant', 'customer', 'contract', 'createdBy', 'updatedBy'],
            'tenant'    => $this->tenant->getFillable(),
            'customer'  => $this->customer->getFillable(),
            'contract'  => $this->contract->getFillable(),
            'createdBy' => $this->user->getFillable(),
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.tenant'    => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.customer'  => ['nullable', new ValidFields('customer', $validFields['customer'])],
            'with_fields.contract'  => ['nullable', new ValidFields('contract', $validFields['contract'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
