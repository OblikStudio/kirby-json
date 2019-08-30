<?php

use Kirby\Data\Json;

Kirby::plugin('oblik/json', [
    'fields' => [
        'json' => [
            'props' => [
                'value' => function ($value = null) {
                    if (is_string($value)) {
                        try {
                            $value = Json::decode($value);
                        } catch (\Exception $e) {
                            $value = null;
                        }
                    }

                    return $value;
                }
            ],
            'save' => function ($value = null) {
                if (is_array($value)) {
                    if (count($value) > 0) {
                        $value = Json::encode($value);
                    } else {
                        $value = null;
                    }
                }

                return $value;
            }
        ]
    ],
    'translations' => [
        'en' => include 'localization/en.php'
    ]
]);
