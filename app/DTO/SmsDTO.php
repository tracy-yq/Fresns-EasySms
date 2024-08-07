<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasySms\DTO;

use App\Fresns\Words\Send\DTO\SendSmsDTO;

/**
 * @property-read int countryCallingCode
 * @property-read int purePhone
 * @property-read string signName
 * @property-read string templateCode
 * @property-read string templateParam
 */
class SmsDTO extends SendSmsDTO
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'countryCallingCode' => ['required', 'integer'],
            'purePhone' => ['required', 'integer'],
            'signName' => ['nullable', 'string'],
            'templateCode' => ['required', 'string'],
            'templateParam' => ['nullable', 'string'],
        ];
    }

    public function getTemplateParamAttribute(): array
    {
        return json_decode($this->getItem('templateParam'), true) ?? [];
    }
}
