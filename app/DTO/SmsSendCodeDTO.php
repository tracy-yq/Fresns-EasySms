<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasySms\DTO;

use App\Fresns\Words\Basic\DTO\SendCodeDTO;

/**
 * @property-read string countryCallingCode
 * @property-read string purePhone
 * @property-read string signName
 * @property-read string templateCode
 * @property-read string templateParam
 */
class SmsSendCodeDTO extends SendCodeDTO
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:1,2'],
            'templateId' => ['required', 'integer'],
            'langTag' => ['nullable', 'string'],
            'account' => ['required', 'string'],
            'countryCallingCode' => ['required_if:type,2', 'integer'],
        ];
    }

    public function setTypeAttribute(int $type)
    {
        if ($type === 1) {
            throw new \LogicException('服务商不支持邮件发信');
        }

        return $type;
    }
}
