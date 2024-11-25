<?php

namespace Workable\Budget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Rules\ValidFields;

class BudgetRequest extends formRequest
{
    use ResponseHelperTrait;

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
                'name'                => ['required', 'string', 'max:255'],
                'description'         => ['nullable', 'string', 'max:255'],
                'money'               => ['required', 'numeric'],
                'expense_category_id' => ['required', 'numeric', Rule::exists('expense_categories', 'id')
                    ->where('tenant_id', get_tenant_id())],
                'account_money_id'    => ['required', 'numeric', Rule::exists('account_monies', 'id')
                    ->where('tenant_id', get_tenant_id())],
//            'meta_file'           => ['nullable', 'string', 'max:255'],
                'meta_content'        => ['nullable', 'array'],
            ];
        }
        $validFields = [
            'with'            =>
                ['tenant', 'createdBy', 'updatedBy', 'expenseCategory', 'accountMoney'],
            'createdBy'       =>
                ['name', 'tenant_id', 'password', 'email', 'phone', 'status', 'address', 'sex', 'date_of_birthday', 'avatar'],
            'tenant'          =>
                ['name', 'email', 'phone', 'status', 'address', 'full_name', 'description', 'business_phone', 'meta_attribute', 'gender', 'birthday', 'size'],
            'expenseCategory' => ['tenant_id', 'area_id', 'area_source_id', 'name', 'description', 'status', 'created_by', 'updated_by',
            ],
            'accountMoney'    => ['tenant_id', 'area_id', 'area_source_id', 'name', 'description', 'created_by', 'updated_by',
            ]
        ];

        return [
            'with'                        => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy'       => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy'       => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
            'with_fields.tenant'          => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
            'with_fields.expenseCategory' => ['nullable', new ValidFields('expenseCategory', $validFields['expenseCategory'])],
            'with_fields.accountMoney'    => ['nullable', new ValidFields('accountMoney', $validFields['accountMoney'])],
        ];
    }
}
