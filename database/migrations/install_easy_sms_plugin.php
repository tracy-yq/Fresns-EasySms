<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

use App\Utilities\ConfigUtility;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    protected $fresnsConfigItems = [
        [
            'item_key' => 'easysms_type',
            'item_value' => '1',
            'item_type' => 'number',
            'item_tag' => 'easysms',
        ],
        [
            'item_key' => 'easysms_keyid',
            'item_value' => '',
            'item_type' => 'string',
            'item_tag' => 'easysms',
        ],
        [
            'item_key' => 'easysms_keysecret',
            'item_value' => '',
            'item_type' => 'string',
            'item_tag' => 'easysms',
        ],
        [
            'item_key' => 'easysms_sdk_appid',
            'item_value' => '',
            'item_type' => 'string',
            'item_tag' => 'easysms',
        ],
        [
            'item_key' => 'easysms_linked',
            'item_value' => [
                '86' => 'zh-Hans',
                'other' => 'en',
            ],
            'item_type' => 'object',
            'item_tag' => 'easysms',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ConfigUtility::addFresnsConfigItems($this->fresnsConfigItems);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        ConfigUtility::removeFresnsConfigItems($this->fresnsConfigItems);
    }
};
