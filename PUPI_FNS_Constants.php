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

class PUPI_FNS_Constants
{
    const LANG_PREFIX = 'pupi_fns';

    // Paths

    const PAGE_NOTIFICATIONS = 'pupi-fns-notifications';
    const PAGE_NOTIFICATIONS_PARAM_TYPE = 'type';
    const PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_ALL = 'all';
    const PAGE_NOTIFICATIONS_PARAM_TYPE_VALUE_STATS_ONLY = 'stats-only';

    // Language strings

    const LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_LABEL = 'admin_max_notifications_per_user_label';
    const LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_NOTE = 'admin_max_notifications_per_user_note';
    const LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_LABEL = 'admin_use_builtin_ui_schema_label';
    const LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_NOTE = 'admin_use_builtin_ui_schema_note';

    const LANG_ID_EVENT_A_POST_NAME = 'event_a_post_name';
    const LANG_ID_EVENT_C_POST_NAME = 'event_c_post_name';
    const LANG_ID_EVENT_Q_CLOSE_NAME = 'event_q_close_name';
    const LANG_ID_EVENT_Q_REOPEN_NAME = 'event_q_reopen_name';
    const LANG_ID_EVENT_Q_MOVE_NAME = 'event_q_move_name';
    const LANG_ID_EVENT_A_SELECT_NAME = 'event_a_select_name';
    const LANG_ID_EVENT_A_UNSELECT_NAME = 'event_a_unselect_name';
    const LANG_ID_EVENT_Q_VOTE_UP_NAME = 'event_q_vote_up_name';
    const LANG_ID_EVENT_Q_VOTE_DOWN_NAME = 'event_q_vote_down_name';
    const LANG_ID_EVENT_Q_VOTE_NIL_NAME = 'event_q_vote_nil_name';
    const LANG_ID_EVENT_A_VOTE_UP_NAME = 'event_a_vote_up_name';
    const LANG_ID_EVENT_A_VOTE_DOWN_NAME = 'event_a_vote_down_name';
    const LANG_ID_EVENT_A_VOTE_NIL_NAME = 'event_a_vote_nil_name';
    const LANG_ID_EVENT_C_VOTE_UP_NAME = 'event_c_vote_up_name';
    const LANG_ID_EVENT_C_VOTE_DOWN_NAME = 'event_c_vote_down_name';
    const LANG_ID_EVENT_C_VOTE_NIL_NAME = 'event_c_vote_nil_name';
    const LANG_ID_EVENT_U_MESSAGE_NAME = 'event_u_message_name';
    const LANG_ID_EVENT_U_WALL_POST_NAME = 'event_u_wall_post_name';
    const LANG_ID_EVENT_U_LEVEL_NAME = 'event_u_level_name';

    const LANG_ID_NOTIFICATION_TEXT_U_LEVEL = 'notification_text_u_level';

    const LANG_ID_NOTIFICATIONS_LIST_NO_NOTIFICATIONS = 'notifications_list_no_notifications';
    const LANG_ID_NOTIFICATIONS_LIST_NO_UNREAD_NOTIFICATIONS = 'notifications_list_no_unread_notifications';
    const LANG_ID_NOTIFICATIONS_LIST_ONE_UNREAD_NOTIFICATION = 'notifications_list_one_unread_notification';
    const LANG_ID_NOTIFICATIONS_LIST_N_UNREAD_NOTIFICATIONS = 'notifications_list_n_unread_notifications';

    // Settings

    const SETTING_MAX_NOTIFICATIONS_PER_USER = 'pupi_fns_max_notifications_per_user';
    const SETTING_USE_BUILTIN_SCHEMA = 'pupi_fns_use_builtin_ui_schema';

    // Settings' default values

    const SETTING_MAX_NOTIFICATIONS_PER_USER_DEFAULT = 20;
    const SETTING_USE_BUILTIN_SCHEMA_DEFAULT = true;
}
