<?php

namespace Workable\Navigation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;
use Workable\Navigation\Models\CategoryMulti;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\User;
use Workable\UserTenant\Rules\ValidFields;
use Workable\UserTenant\Traits\MessageValidateTrait;

class CategoryMultiRequest extends formRequest
{
    use ResponseHelperTrait, MessageValidateTrait;

    protected $user;
    protected $categoryMulti;

    public function __construct(
        User          $user,
        CategoryMulti $categoryMulti
    )
    {
        parent::__construct();
        $this->user          = $user;
        $this->categoryMulti = $categoryMulti;
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
            dd( $this->getMessage(
                [
                    'name'      => ['required', 'string', 'max:191'],
                    'root_id'   => ['nullable', 'integer', 'exists'],
                    'parent_id' => ['nullable', 'exists'],
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
                ],
                'navigation::api'
            ));
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
            'with'      => ['createdBy', 'updatedBy', 'parent', 'root'],
            'createdBy' => $this->user->getFillable(),
            'parent'    => $this->categoryMulti->getFillable(),
        ];

        return [
            'with'                  => ['nullable', new ValidFields('with', $validFields['with'])],
            'with_fields.createdBy' => ['nullable', new ValidFields('createdBy', $validFields['createdBy'])],
            'with_fields.updatedBy' => ['nullable', new ValidFields('updatedBy', $validFields['createdBy'])],
            'with_fields.parent'    => ['nullable', new ValidFields('parent', $validFields['parent'])],
            'with_fields.root'      => ['nullable', new ValidFields('root', $validFields['parent'])],
        ];
    }

    public function messages(): array
    {
        return $this->getMessage(
            [
                'name'      => ['required', 'string', 'max:191'],
                'root_id'   => ['nullable', 'integer', 'exists'],
                'parent_id' => ['nullable', 'exists'],
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
            ],
            'navigation::api'
        );
    }
}
