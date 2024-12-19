<?php

namespace Workable\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Customers\Models\Customer;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;

class ActivityRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;

    protected $user;

    protected $customer;

    protected $contract;

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

            $customersRule = Rule::exists('customers', 'id')
                ->where('tenant_id', get_tenant_id());

            return [
                'customer_id' => ['required', 'integer', $customersRule],
                'type'        => ['required', 'integer'],
                'meta'        => ['required', 'array'],
            ];
        }

        $validFields = [
            'with'     => ['tenant', 'customer'],
            'tenant'   => $this->tenant->getFillable(),
            'customer' => $this->customer->getFillable(),
        ];

        return [
            'with'                 => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.tenant'   => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.customer' => ['nullable', new ValidFields('customer', $validFields['customer'])],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
