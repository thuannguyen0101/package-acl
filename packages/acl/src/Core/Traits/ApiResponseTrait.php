<?php

namespace Workable\ACL\Core\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Workable\ACL\Enums\ResponseMessageEnum;

trait ApiResponseTrait
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = "", int $code = ResponseMessageEnum::CODE_OK): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message ?: $this->getMessageDefault($code),
            'data'    => $data,
        ], $code);
    }

    /**
     * Created success response
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function createdResponse($data, string $message = ""): JsonResponse
    {
        $code    = ResponseMessageEnum::CODE_CREATED;
        $message = $message ?: $this->getMessageDefault($code);

        return $this->successResponse($data, $message, $code);
    }

    /**
     * Updated success response
     *
     * @param string $message
     * @param mixed $data
     * @return JsonResponse
     */
    protected function updatedResponse($data, string $message = ""): JsonResponse
    {
        $code    = ResponseMessageEnum::CODE_OK;
        $message = $message ?: "Dữ liệu đã được cập nhật thành công.";

        return $this->successResponse($data, $message, $code);
    }

    /**
     * Deleted success response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function deletedResponse(string $message = ""): JsonResponse
    {
        $code    = ResponseMessageEnum::CODE_NO_CONTENT;
        $message = $message ?: $this->getMessageDefault($code);
        $data    = null;

        return $this->successResponse($data, $message, $code);
    }

    /**
     * Error response
     *
     * @param string|null $message
     * @param int $code
     * @param mixed $data
     * @return JsonResponse
     */
    protected function errorResponse(string $message = "", int $code = ResponseMessageEnum::CODE_BAD_REQUEST, $data = null): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message ?: $this->getMessageDefault($code),
            'data'    => $data,
        ], $code);
    }

    /**
     * Not found response
     *
     * @param string|null $message
     * @return JsonResponse
     */
    protected function notFoundResponse(string $message = ""): JsonResponse
    {
        $code    = ResponseMessageEnum::CODE_NOT_FOUND;
        $message = $message ?: $this->getMessageDefault($code);

        return $this->errorResponse($message, $code);
    }

    /**
     * Validate false
     *
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        $code = ResponseMessageEnum::CODE_UNPROCESSABLE_ENTITY;

        throw new HttpResponseException(response()->json([
            'success' => 'error',
            'message' => $this->getMessageDefault($code),
            'errors'  => $validator->errors(),
        ], $code));
    }

    private function getMessageDefault(int $statusCode = ResponseMessageEnum::CODE_INTERNAL_SERVER_ERROR): string
    {
        $messages = ResponseMessageEnum::MESSAGE_TEXT;

        return $messages[$statusCode] ?? $messages[500];
    }
}
