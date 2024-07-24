<?php

/*
    This file is part of PUPI - Flexible Notifications System, a
    Question2Answer plugin that allows users to receive notifications in a
    flexible and efficient way.

    Copyright (C) 2024 Gabriel Zanetti <https://github.com/pupi1985>

    PUPI - Flexible Notifications System is free software: you can redistribute
    it and/or modify it under the terms of the GNU General Public License as
    published by the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    PUPI - Flexible Notifications System is distributed in the hope that it
    will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
    Public License for more details.

    You should have received a copy of the GNU General Public License along
    with PUPI - Flexible Notifications System. If not, see
    <http://www.gnu.org/licenses/>.
*/

class PUPI_FNS_Plugin
{
    private static ?PUPI_FNS_Plugin $instance = null;

    private PUPI_FNS_ExtensionsManager $extensionsManager;

    public static function getInstance(): PUPI_FNS_Plugin
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->initialize();
        }

        return self::$instance;
    }

    private function initialize()
    {
        $this->prepareClassAutoloader();
        $this->extensionsManager = new PUPI_FNS_ExtensionsManager();
    }

    private function prepareClassAutoloader()
    {
        $classMap = [
            'PUPI_FNS_NotificationTypes' => 'PUPI_FNS_NotificationTypes.php',
            'PUPI_FNS_NotificationsService' => 'PUPI_FNS_NotificationsService.php',

            'PUPI_FNS_NotificationsStatsCache' => 'PUPI_FNS_NotificationsStatsCache.php',

            'PUPI_FNS_UserNotificationStatsModel' => 'models/PUPI_FNS_UserNotificationStatsModel.php',
            'PUPI_FNS_NotificationsModel' => 'models/PUPI_FNS_NotificationsModel.php',
            'PUPI_FNS_PostsModel' => 'models/PUPI_FNS_PostsModel.php',

            'PUPI_FNS_ExtensionsManager' => 'extensions/PUPI_FNS_ExtensionsManager.php',
            'PUPI_FNS_IExtension' => 'extensions/PUPI_FNS_IExtension.php',
            'PUPI_FNS_CoreExtension' => 'extensions/PUPI_FNS_CoreExtension.php',
        ];

        spl_autoload_extensions('.php');
        spl_autoload_register(function ($className) use ($classMap) {
            if (isset($classMap[$className])) {
                require_once __DIR__ . '/' . $classMap[$className];
            }
        });
    }

    public function lang(string $langId): string
    {
        return qa_lang(PUPI_FNS_Constants::LANG_PREFIX . '/' . $langId);
    }

    public function langSub(string $langId, string $replacement): string
    {
        return qa_lang_sub(PUPI_FNS_Constants::LANG_PREFIX . '/' . $langId, $replacement);
    }

    public function getExtensionsManager(): PUPI_FNS_ExtensionsManager
    {
        return $this->extensionsManager;
    }
}
