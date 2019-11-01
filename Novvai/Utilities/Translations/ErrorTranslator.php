<?php

namespace Novvai\Utilities\Translations;

class ErrorTranslator
{
    const CODE_TRANSLATIONS = [
        5000 => "Невалидно потребителско име или парола",
        9001 => "Минимална стойност - :criterion",
        9002 => "Минимална дължина на полето - :criterion",
        9003 => "Максимална стойност - :criterion",
        9004 => "Максимална дължина на полето - :criterion",
        9005 => "Невалиден формат",
        9006 => "Задължително поле",
    ];

    /**
     * @param int $code
     * @param mixed $code
     * 
     * @return string
     */
    static private function fit($code, $criterion): string
    {
        return str_replace(":criterion", $criterion, self::CODE_TRANSLATIONS[$code]);
    }

    /**
     * @param array 
     * 
     * @return array
     */
    static public function map(array $errors)
    {
        foreach ($errors as &$errorGroup) {
            foreach ($errorGroup as &$error) {
                $error['message'] = self::fit($error['code'], $error['criterion']);
            }
        }
        return $errors;
    }

    static public function fromCode($code)
    {
        return [
            [
                [
                    "code" => $code,
                    "message" => self::CODE_TRANSLATIONS[$code] ?? "Нещо не е наред."
                ]
            ]
        ];
    }
}
