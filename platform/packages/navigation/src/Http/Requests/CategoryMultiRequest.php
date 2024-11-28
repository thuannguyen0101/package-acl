<?php

namespace Workable\Navigation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Rules\ValidFields;

class CategoryMultiRequest extends formRequest
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
                'name'      => ['required', 'string', 'max:191'],
                'root_id'   => ['nullable', 'integer', 'exists:category_multi,id'],
                'parent_id' => ['nullable', 'exists:category_multi,id'],
                'url'       => ['required', 'string'],
                'type'      => ['nullable', 'string', 'max:50'],
                'icon'      => ['nullable', 'string', 'max:50'],
                'view_data' => ['nullable', 'string'],
                'label'     => ['integer'],
                'layout'    => ['integer'],
                'sort'      => ['integer'],
                'is_auth'   => ['integer'],
                'status'    => ['integer'],
                'meta'      => ['nullable', 'array'],
            ];
        }

        $validFields = [
            'with'      =>
                ['createdBy', 'updatedBy', 'parent', 'root'],
            'createdBy' =>
                ['name', 'tenant_id', 'password', 'email', 'phone', 'status', 'address', 'sex', 'date_of_birthday', 'avatar'],
            'parent'    =>
                ['name', 'root_id', 'parent_id', 'url', 'type', 'icon', 'view_data', 'label', 'layout', 'sort', 'is_auth', 'status', 'meta', 'created_by', 'updated_by'],
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
            'with_fields.parent'    => ['nullable', new ValidFields('parent', $validFields['parent'])],
            'with_fields.root'      => ['nullable', new ValidFields('root', $validFields['parent'])],
        ];
    }
}
