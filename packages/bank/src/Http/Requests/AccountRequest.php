<?php

namespace Workable\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\Bank\Enums\AccountEnum;

class AccountRequest extends FormRequest
{
    use ApiResponseTrait;

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
            'account_type.required' => 'Loại tài khoản là bắt buộc.',
            'account_type.numeric'  => 'Loại tài khoản phải là các loại đã được chỉ định.',
            'account_type.in'       => 'Loại tài khoản không hợp lệ. Vui lòng chọn một trong các giá trị đã đưa ra.',

            'bank_name.required' => 'Tên ngân hàng là bắt buộc.',
            'bank_name.numeric'  => 'Tên ngân hàng phải là các tên đã được chỉ định.',
            'bank_name.in'       => 'Tên ngân hàng không hợp lệ. Vui lòng chọn một trong các giá trị đã đưa ra.',

            'branch_name.required' => 'Tên chi nhánh là bắt buộc.',
            'branch_name.numeric'  => 'Tên chi nhánh phải là các chi nhánh đã được chỉ định',
            'branch_name.in'       => 'Tên chi nhánh không hợp lệ. Vui lòng chọn một trong các giá trị đã đưa ra.',
        ];
    }
}
