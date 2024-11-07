<?php
namespace  Workable\ACL\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidFields implements Rule
{
    protected $validFields;
    protected $entity;

    public function __construct($entity, array $validFields)
    {
        $this->validFields = $validFields;
        $this->entity = $entity;
    }

    /**
     * Kiểm tra xem validation có pass hay không.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $fields = explode(',', $value);

        foreach ($fields as $field) {
            if (!in_array($field, $this->validFields)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Thông báo lỗi nếu validation không pass.
     *
     * @return string
     */
    public function message()
    {
        return "Một hoặc nhiều trường không hợp lệ cho {$this->entity}.";
    }
}
