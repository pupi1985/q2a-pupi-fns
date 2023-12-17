<?php

/*
    This file is part of PUPI - Flexible Notifications System, a
    Question2Answer plugin that helps manage Q2A's configuration.

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

class PUPI_FNS_Admin
{
    const SAVE_BUTTON = 'pupi_fns_save_button';

    public function option_default($option)
    {
        switch ($option) {
            case PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER:
                return PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER_DEFAULT;
            case PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA:
                return PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA_DEFAULT;
        }

        return null;
    }

    public function admin_form(&$qa_content): array
    {
        $saveButtonClicked = qa_clicked(self::SAVE_BUTTON);

        if ($saveButtonClicked) {
            $this->saveAllSettings();

            // Only needed to load or avoid loading the built-in layer immediately
            qa_redirect('admin/plugins', ['show' => qa_get('show')], null, null, qa_get('show'));
        }

        return [
            'style' => 'tall',
            'fields' => $this->getFields(),
            'buttons' => $this->getButtons(),
        ];
    }

    private function getButtons(): array
    {
        return [
            'save' => [
                'tags' => sprintf('name="%s"', self::SAVE_BUTTON),
                'label' => qa_lang_html('admin/save_options_button'),
            ],
        ];
    }

    // Fields

    private function getFields(): array
    {
        return [
            $this->getFieldMaxNotificationsPerUser(),
            $this->getFieldUseBuiltInUISchema(),
        ];
    }

    private function getFieldMaxNotificationsPerUser(): array
    {
        return [
            'type' => 'number',
            'tags' => sprintf('name="%s"', qa_html(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER)),
            'label' => qa_html(pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_LABEL)),
            'value' => (int)qa_opt(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER),
            'note' => qa_html(pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_NOTE)),
        ];
    }

    private function getFieldUseBuiltInUISchema(): array
    {
        return [
            'type' => 'checkbox',
            'tags' => sprintf('name="%s"', qa_html(PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA)),
            'label' => qa_html(pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_LABEL)),
            'value' => (bool)qa_opt(PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA),
            'note' => qa_html(pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_NOTE)),
        ];
    }

    // Save methods

    private function saveAllSettings()
    {
        $this->saveSettingMaxNotificationsPerUser();
        $this->saveSettingUseBuiltInUISchema();
    }

    private function saveSettingMaxNotificationsPerUser()
    {
        $value = max((int)qa_post_text(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER), 1);
        $value = min($value, PUPI_FNS_Setup::TOTAL_NOTIFICATIONS_MAX_VALUE);
        qa_opt(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER, $value);
    }

    private function saveSettingUseBuiltInUISchema()
    {
        $value = (bool)qa_post_text(PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA);
        qa_opt(PUPI_FNS_Constants::SETTING_USE_BUILTIN_SCHEMA, $value);
    }
}
