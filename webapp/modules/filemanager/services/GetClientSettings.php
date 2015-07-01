<?php

namespace webapp\modules\filemanager\services;


class GetClientSettings extends ServiceAbstract
{
    protected function loadResults()
    {
        $settings = $this->settings();

        $this->results = [
            'root' => $settings[static::CONFIG_ROOT],
            'cloudUrl' => $settings[static::CONFIG_CLOUD_URL],
            'allowMimeTypes' => $settings[static::CONFIG_ALLOW_MIME_TYPE],
            'denyMimeTypes' => $settings[static::CONFIG_DENY_MIME_TYPE],
        ];
    }
}