<?php

namespace Workable\Budget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Budget\Enums\ExpenseCategoryEnum;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\Tenant;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;

class ExpenseCategoryRequest extends formRequest
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
    public function rules(Request $request)
    {
        if ($request->isMethod(RequestAlias::METHOD_POST)) {
            return [
                'name'        => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status'      => ['nullable', 'integer', 'in:' . implode(',', array_keys(ExpenseCategoryEnum::STATUS))],
            ];
        }

        $validFields = [
            'with'      => ['tenant', 'createdBy', 'updatedBy'],
            'createdBy' => $this->user->getFillable(),
            'tenant'    => $this->tenant->getFillable(),
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
            'with_fields.tenant'    => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
        ];
    }

    public function messages(): array
    {
        return $this->getMessage(
            [
                'name.expense_category'        => ['required', 'string', 'max:255'],
                'description.expense_category' => ['nullable', 'string', 'max:255'],
                'status'                       => ['required', 'numeric',],
            ],
            'budget::api'
        );
    }
}
