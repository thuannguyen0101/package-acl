<?php

namespace Workable\Budget\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Rules\ValidFields;

class AccountMoneyListRequest extends formRequest
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

        $validFields = [
            'with'      =>
                ['tenant', 'createdBy', 'updatedBy'],
            'createdBy' =>
                ['name', 'tenant_id', 'password', 'email', 'phone', 'status', 'address', 'sex', 'date_of_birthday', 'avatar'],
            'tenant'    =>
                ['name', 'email', 'phone', 'status', 'address', 'full_name', 'description', 'business_phone', 'meta_attribute', 'gender', 'birthday', 'size'],
        ];


        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
            'with_fields.tenant'    => ['nullable', new ValidFields('tenant', $validFields['tenant'])],
        ];
    }
}
