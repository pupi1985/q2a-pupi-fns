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

class PUPI_FNS_Setup
{
    const LATEST_DB_VERSION = 1;

    const PARAMS_FIELD_LENGTH = 1024;
    const TOTAL_NOTIFICATIONS_MAX_VALUE = 65535;

    public function init_queries($tableslc)
    {
        $queries = array();

        $currenDbVersion = $this->getDbVersion();
        if ($currenDbVersion < self::LATEST_DB_VERSION) {
            $currenDbVersion++;
            switch ($currenDbVersion) {
                case 0:  // Initial value where nothing should already be installed
                case 1:
                    $queries = $this->getQueriesV1($tableslc, $queries);
            }
        }

        return $queries;
    }

    public function getDbVersion()
    {
        // Go to the database directly to avoid returning the old cached version
        return (int)qa_db_read_one_value(qa_db_query_sub(
            'SELECT `content` FROM `^options` ' .
            'WHERE `title` = $',
            'pupi_fns_plugin_db_version'
        ), true);
    }

    private function getUpdateVersionQuery($version)
    {
        return qa_db_apply_sub(
            'INSERT INTO `^options` (`title`, `content`) ' .
            'VALUES ($, #) ' .
            'ON DUPLICATE KEY UPDATE `content` = VALUES(`content`)',
            array(
                'pupi_fns_plugin_db_version',
                $version,
            )
        );
    }

    private function getQueriesV1($tableslc, array $queries): array
    {
        // For qa_get_mysql_user_column_type()
        require_once QA_INCLUDE_DIR . 'app/users.php';

        if (!in_array(qa_db_add_table_prefix('pupi_fns_notifications'), $tableslc)) {
            $queries[] =
                'CREATE TABLE IF NOT EXISTS `^pupi_fns_notifications` (' .
                '   `event_id` TINYINT NOT NULL,' .
                '   `plugin_id` VARCHAR(10),' .
                '   `params` VARCHAR (' . self::PARAMS_FIELD_LENGTH . '),' .
                '   `user_id` ' . qa_get_mysql_user_column_type() . ' NOT NULL,' .
                '   `url` VARCHAR(2048),' .
                '   `created_at` DATETIME NOT NULL,' .
                '   CONSTRAINT `^pupi_fns_notifications_fk1` FOREIGN KEY (`user_id`) REFERENCES `^users` (`userid`) ON DELETE CASCADE,' .
                '   INDEX `^pupi_fns_events_idx1` (`user_id`, `created_at`)' .
                ') ENGINE = InnoDB CHARSET = utf8';
        }

        if (!in_array(qa_db_add_table_prefix('pupi_fns_user_notification_stats'), $tableslc)) {
            $queries[] =
                'CREATE TABLE IF NOT EXISTS `^pupi_fns_user_notification_stats` (' .
                '   `user_id` ' . qa_get_mysql_user_column_type() . ' NOT NULL,' .
                '   `total_notifications` SMALLINT NOT NULL,' .
                '   `unread_notifications` SMALLINT NOT NULL,' .
                '   PRIMARY KEY(`user_id`),' .
                '   CONSTRAINT `^pupi_fns_user_notification_stats_fk1` FOREIGN KEY (`user_id`) REFERENCES `^users` (`userid`) ON DELETE CASCADE' .
                ') ENGINE = InnoDB CHARSET = utf8';
        }

        $queries[] = $this->getUpdateVersionQuery(1);

        return $queries;
    }
}
