<?php
/**
 * Created by PhpStorm.
 * User: thuannn
 * Date: 22/10/2024
 * Time: 21:04
 */

namespace Workable\ACL\Core\Traits;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait HandleFailedValidationTrait
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
            'message' => 'Validation Error',
        ], 422));
    }
}
