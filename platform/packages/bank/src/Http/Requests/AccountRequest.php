<?php

namespace Workable\Bank\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Bank\Enums\AccountEnum;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Traits\MessageValidateTrait;

class AccountRequest extends FormRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

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
        return $this->getMessage(
            [
                'account_type' => ['required', 'numeric', 'in'],
                'bank_name'    => ['required', 'numeric', 'in'],
                'branch_name'  => ['required', 'numeric', 'in'],
            ],
            'bank::api'
        );
    }
}
