<?php

namespace Workable\Budget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Budget\Models\AccountMoney;
use Workable\Budget\Models\ExpenseCategory;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;

class BudgetRequest extends formRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $tenant;
    protected $accountMoney;
    protected $expenseCategory;
    protected $user;

    public function __construct(
        Tenant          $tenant,
        AccountMoney    $accountMoney,
        ExpenseCategory $expenseCategory,
        User            $user
    )
    {
        parent::__construct();
        $this->tenant          = $tenant;
        $this->accountMoney    = $accountMoney;
        $this->expenseCategory = $expenseCategory;
        $this->user            = $user;
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
            $expenseCategoryRule = Rule::exists('expense_categories', 'id')->where('tenant_id', get_tenant_id());
            $accountMoneyRule    = Rule::exists('account_monies', 'id')->where('tenant_id', get_tenant_id());

            return [
                'name'                => ['required', 'string', 'max:255'],
                'description'         => ['nullable', 'string', 'max:255'],
                'money'               => ['required', 'numeric'],
                'expense_category_id' => ['required', 'numeric', $expenseCategoryRule],
                'account_money_id'    => ['required', 'numeric', $accountMoneyRule],
                'meta_content'        => ['nullable', 'array'],
                // 'meta_file' => ['nullable', 'string', 'max:255'],
            ];
        }

        $validFields = [
            'with'            =>
                ['tenant', 'createdBy', 'updatedBy', 'expenseCategory', 'accountMoney'],
            'createdBy'       => $this->user->getFillable(),
            'tenant'          => $this->tenant->getFillable(),
            'expenseCategory' => $this->expenseCategory->getFillable(),
            'accountMoney'    => $this->accountMoney->getFillable(),
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

    public function messages(): array
    {
        return $this->getMessage(
            [
                'name.budget'         => ['required', 'string', 'max:255'],
                'description.budget'  => ['nullable', 'string', 'max:255'],
                'money'               => ['required', 'numeric',],
                'expense_category_id' => ['required', 'numeric', 'exists'],
                'account_money_id'    => ['required', 'string', 'exists'],
                'meta_content'        => ['string'],
            ],
            'budget::api'
        );
    }
}
