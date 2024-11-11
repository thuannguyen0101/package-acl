<?php

namespace Workable\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Bank\Enums\AccountEnum;
use Workable\Support\Traits\ResponseHelperTrait;

class AccountRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'account_type' => 'required|numeric|in:' . implode(',', array_keys(AccountEnum::TYPE_TEXT)),
            'bank_name'    => 'required|numeric|in:' . implode(',', array_keys(AccountEnum::BANK_NAME_TEXT)),
            'branch_name'  => 'required|numeric|in:' . implode(',', array_keys(AccountEnum::BRANCH_NAME_TEXT)),
        ];
    }

    public function messages(): array
    {
        return [
            'account_type.required' => __('acl::api.required', ['attribute' => 'loại tài khoản']),
            'account_type.numeric'  => __('acl::api.numeric', ['attribute' => 'loại tài khoản']),
            'account_type.in'       => __('acl::api.in', ['attribute' => 'loại tài khoản']),

            'bank_name.required' => __('acl::api.required', ['attribute' => 'tên ngân hàng']),
            'bank_name.numeric'  => __('acl::api.numeric', ['attribute' => 'tên ngân hàng']),
            'bank_name.in'       => __('acl::api.in', ['attribute' => 'tên ngân hàng']),

            'branch_name.required' => __('acl::api.required', ['attribute' => 'tên chi nhánh']),
            'branch_name.numeric'  => __('acl::api.numeric', ['attribute' => 'tên chi nhánh']),
            'branch_name.in'       => __('acl::api.in', ['attribute' => 'tên chi nhánh']),
        ];
    }
}
