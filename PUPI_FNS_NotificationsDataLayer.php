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

class qa_html_theme_layer extends qa_html_theme_base
{
    public function initialize()
    {
        parent::initialize();

        if (!qa_is_logged_in()) {
            return;
        }

        $this->addJsBodyHeader();
    }

    /**
     * @return void
     */
    private function addJsBodyHeader(): void
    {
        if (!isset($this->content['body_header'])) {
            $this->content['body_header'] = '';
        }

        $options = [
            'notifications_url_all' => qa_path(
                PUPI_FNS_Constants::PAGE_NOTIFICATIONS,
                [PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE => PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_ALL],
                qa_opt('site_url')
            ),
            'notifications_url_only_stats' => qa_path(
                PUPI_FNS_Constants::PAGE_NOTIFICATIONS,
                [PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE => PUPI_FNS_Constants::PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_STATS_ONLY],
                qa_opt('site_url')
            ),
            'notification_stats' => (new PUPI_FNS_NotificationsService())->getNotificationsStatsForUser(qa_get_logged_in_userid()),
        ];
        $this->content['body_header'] .= sprintf('<script>const pupi_fns_options = %s;</script>', json_encode($options));
    }
}
