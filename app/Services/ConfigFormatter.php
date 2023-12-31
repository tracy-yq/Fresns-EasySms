<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasySms\Services;

class ConfigFormatter
{
    protected $smsSystemConfig;

    public function __construct()
    {
        $this->smsSystemConfig = app(SmsConfig::class);
    }

    public function formatAliyunGatewayConfig(string $signName)
    {
        return [
            'access_key_id' => $this->smsSystemConfig->getKeyId(),
            'access_key_secret' => $this->smsSystemConfig->getKeySecret(),
            'sign_name' => $signName,
        ];
    }

    public function formatQcloudGatewayConfig(string $signName)
    {
        return [
            'sdk_app_id' => $this->smsSystemConfig->getAppId(),
            'secret_id' => $this->smsSystemConfig->getKeyId(),
            'secret_key' => $this->smsSystemConfig->getKeySecret(),
            'sign_name' => $signName,
        ];
    }

    public function formatVolcengineGatewayConfig(string $signName)
    {
        return [
            'access_key_id' =>$this->smsSystemConfig->getKeyId(),
            'access_key_secret' => $this->smsSystemConfig->getKeySecret(),
            'region_id' => 'cn-north-1',
            'sign_name' => $signName,
            'sms_account' => $this->smsSystemConfig->getAppId(),
        ];
    }
}
