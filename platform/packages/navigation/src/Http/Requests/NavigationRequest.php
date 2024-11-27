<?php

namespace Workable\Navigation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Rules\ValidFields;

class NavigationRequest extends formRequest
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

            ];
        }
        $validFields = [
            'with'      =>
                ['tenant', 'createdBy', 'updatedBy', 'expenseCategory', 'accountMoney'],
            'createdBy' =>
                ['name', 'tenant_id', 'password', 'email', 'phone', 'status', 'address', 'sex', 'date_of_birthday', 'avatar'],
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
        ];
    }
}
