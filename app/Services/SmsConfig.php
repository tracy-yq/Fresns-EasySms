<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasySms\Services;

use App\Helpers\StrHelper;
use App\Models\Config;
use Overtrue\EasySms\Gateways\ErrorlogGateway;
use Overtrue\EasySms\Gateways\AliyunGateway;
use Overtrue\EasySms\Gateways\QcloudGateway;
use Overtrue\EasySms\Gateways\VolcengineGateway;

class SmsConfig
{
    protected $gateways = [
        0 => ErrorlogGateway::class, // 阿里云短信
        1 => AliyunGateway::class, // 阿里云短信
        2 => QcloudGateway::class, // 腾讯云短信
        3 => VolcengineGateway::class, // 火山引擎
    ];

    public function getValueByConfigItemKey(string $field)
    {
        $value = Config::where('item_key', $field)->first()?->item_value;

        return $value;
    }

    /**
     * 获取国际区号匹配语言标签配置.
     *
     * @return array|null
     */
    public function getEasySmsLinked(): ?array
    {
        $value = $this->getValueByConfigItemKey('easysms_linked');

        $default = [
            '86' => 'zh-Hans',
            'other' => 'en',
        ];

        return $value ?? $default;
    }

    public function getAppId(): ?string
    {
        $value = $this->getValueByConfigItemKey('easysms_sdk_appid');

        return $value;
    }

    public function getKeyId(): ?string
    {
        $value = $this->getValueByConfigItemKey('easysms_keyid');

        return $value;
    }

    public function getKeySecret(): ?string
    {
        $value = $this->getValueByConfigItemKey('easysms_keysecret');

        return $value;
    }

    public function getEasySmsType()
    {
        $value = $this->getValueByConfigItemKey('easysms_type');

        return $value;
    }

    public function getCodeTemplate(string $templateId, string $langTag)
    {
        $templateValue = $this->getValueByConfigItemKey('verifycode_template'.$templateId);

        $templates = $templateValue['sms']['templates'] ?? [];

        return StrHelper::languageContent($templates, $langTag);
    }

    public function getVerifyCodesTemplate(string $templateCode, string $langTag = 'en')
    {
        $template = Config::tag('verifyCodes')->get();

        $enableTemplates = $template
            ->where('is_enabled')
            ->reduce(function ($carry, $item) use ($templateCode, $langTag) {
                $data = collect($item->item_value)
                    ->where('type', 'sms')
                    ->filter(function ($item) {
                        return $item['isEnabled'] ?? false;
                    })
                    ->all();

                // 筛选语言标签
                $template = collect($data)
                    ->pluck('template')
                    ->flatten(1)
                    ->where('templateCode', $templateCode)
                    ->where('langTag', $langTag)
                    ->first();

                $carry[] = [
                    'sence' => $item->item_key,
                    'template_type' => 'sms',
                    'template_code' => $templateCode,
                    'template' => $template ?? null,
                ];

                return $carry;
            }, []);

        $template = collect($enableTemplates)->first();

        return $template;
    }

    /**
     * 国际区号匹配语言标签 easysms_linked.
     *
     * @param  int|null  $countryCallingCode
     * @return string
     */
    public function getLangTagOfEasySmsLinked(?int $countryCallingCode = null): string
    {
        $countryCallingCode = $countryCallingCode ?? 'other';

        $aqSmsLinked = $this->getEasySmsLinked();

        return $aqSmsLinked[$countryCallingCode] ?? 'en';
    }

    /**
     * 获取发送短信的网关.
     *
     * @return string|null
     */
    public function getEasySmsGateway(): ?string
    {
        return $this->gateways[$this->getEasySmsType()] ?? null;
    }

    /**
     * 获取发送短信的网关.
     *
     * @return string|null
     */
    public function getEasySmsGatewayName(): ?string
    {
        $gateway = $this->getEasySmsGateway();

        $name = str_replace(['Overtrue\\EasySms\\Gateways\\', 'Gateway'], '', $gateway);

        $name = lcfirst($name);

        return $name;
    }
}
