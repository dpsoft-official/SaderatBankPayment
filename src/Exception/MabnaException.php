<?php namespace Dpsoft\Saderat\Exception;


class MabnaException extends \Exception
{

    /**
     * MabnaException constructor.
     *
     * @param int $code
     */
    public function __construct(int $code)
    {
        $this->message = $this->codeToMessage((int)$code);
        $this->code = $code;
    }

    /**
     * @param int $code
     *
     * @return string $errors
     */
    private function codeToMessage(int $code)
    {
        $errors = [
            -1 => 'تراکنش پیدا نشد',
            -2 => 'تراکنش قبلا Reverse شده است',
            -3 => 'خطای ناشناخته رخ داده است',
            -4 => 'امکان انجام درخواست برای این تراکنش وجود ندارد',
            -5 => 'دسترسی IP مجاز نمی باشد (IP در Access List وجود ندارد)',
            -6 => 'عدم فعال بودن سرویس برگشت تراکنش برای پذیرنده',
            -7 => 'تراکنش توسط خریدار لغو شده است.',
            -8 => 'خطای تعریف نشده'
        ];

        return !empty($errors[$code]) ? $errors[$code] : 'خطای تعریف نشده!';
    }
}
