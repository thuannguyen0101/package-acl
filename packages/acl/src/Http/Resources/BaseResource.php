<?php

namespace Workable\ACL\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    protected $message = '';
    /**
     * Chuyển đổi resource thành một mảng dữ liệu có thể trả về.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Xử lý cấu trúc cơ bản ở đây nếu cần
        return parent::toArray($request);
    }

    public function setMessage(string $message = '')
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * Phương thức trả về metadata cho response.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return
            [
                'success' => true,
                'message' => $this->getMessage() ?? "success",
            ];
    }

    /**
     * Xử lý lỗi trả về nếu có lỗi xảy ra.
     *
     * @param string $message
     * @param mixed $data
     * @return array
     */
    public static function handleError(string $message,array $data = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors'  => $data,
        ];
    }
    public static function handleDelete(string $message): array
    {
        return [
            'success' => true,
            'message' => $message,
        ];
    }
}
