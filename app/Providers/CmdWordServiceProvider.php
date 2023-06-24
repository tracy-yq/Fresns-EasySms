<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\EasySms\Providers;

use Illuminate\Support\ServiceProvider;
use Plugins\EasySms\Services\SmsService;

class CmdWordServiceProvider extends ServiceProvider implements \Fresns\CmdWordManager\Contracts\CmdWordProviderContract
{
    use \Fresns\CmdWordManager\Traits\CmdWordProviderTrait;

    protected $fsKeyName = 'EasySms';

    /* This is a map of command word and its provider. */
    protected $cmdWordsMap = [
        ['word' => 'sendCode', 'provider' => [SmsService::class, 'sendCode']],
        ['word' => 'sendSms', 'provider' => [SmsService::class, 'sendSms']],
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCmdWordProvider();
    }
}
