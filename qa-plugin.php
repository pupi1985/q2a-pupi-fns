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

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

require_once 'PUPI_FNS_Constants.php';
require_once 'PUPI_FNS_Plugin.php';

function pupi_fns(): PUPI_FNS_Plugin
{
    return PUPI_FNS_Plugin::getInstance();
}

pupi_fns();

qa_register_plugin_module('process', 'PUPI_FNS_Setup.php', 'PUPI_FNS_Setup', 'PUPI FNS Setup');
qa_register_plugin_module('process', 'PUPI_FNS_Admin.php', 'PUPI_FNS_Admin', 'PUPI_FNS Admin');

qa_register_plugin_module('event', 'PUPI_FNS_EventListener.php', 'PUPI_FNS_EventListener', 'PUPI FNS Event Listener');

qa_register_plugin_module('page', 'PUPI_FNS_NotificationsPage.php', 'PUPI_FNS_NotificationsPage', 'PUPI_FNS Notifications Page');

qa_register_plugin_phrases('lang/' . PUPI_FNS_Constants::LANG_PREFIX . '_*.php', PUPI_FNS_Constants::LANG_PREFIX);

qa_register_plugin_layer('PUPI_FNS_NotificationsDataLayer.php', 'PUPI_FNS Notifications Data Layer');
if (qa_opt_if_loaded(PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA) ?? PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA_DEFAULT) {
    qa_register_plugin_layer('PUPI_FNS_BuiltInNotificationsRendererLayer.php', 'PUPI_FNS BuiltIn Notifications Renderer Layer');
    pupi_fns()->getExtensionsManager()->register(new PUPI_FNS_CoreExtension());
}
