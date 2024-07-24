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

return [
    PUPI_FNS_Constants::LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_LABEL => 'Maximum amount of notifications per user',
    PUPI_FNS_Constants::LANG_ID_ADMIN_MAX_NOTIFICATIONS_PER_USER_NOTE => 'Notifications that exceed this number will be deleted',
    PUPI_FNS_Constants::LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_LABEL => 'Use built-in user interface',
    PUPI_FNS_Constants::LANG_ID_ADMIN_USE_BUILTIN_UI_SCHEMA_NOTE => 'Only uncheck if another notifications renderer has been installed',

    PUPI_FNS_Constants::LANG_ID_EVENT_A_POST_NAME => 'Question answered',
    PUPI_FNS_Constants::LANG_ID_EVENT_C_POST_NAME => 'Comment added',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_CLOSE_NAME => 'Question closed',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_REOPEN_NAME => 'Question reopened',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_MOVE_NAME => 'Question moved',
    PUPI_FNS_Constants::LANG_ID_EVENT_A_SELECT_NAME => 'Answer selected',
    PUPI_FNS_Constants::LANG_ID_EVENT_A_UNSELECT_NAME => 'Answer unselected',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_UP_NAME => 'Question upvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_DOWN_NAME => 'Question downvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_NIL_NAME => 'Question vote removed',
    PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_UP_NAME => 'Answer upvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_DOWN_NAME => 'Answer downvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_NIL_NAME => 'Answer vote removed',
    PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_UP_NAME => 'Comment upvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_DOWN_NAME => 'Comment downvoted',
    PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_NIL_NAME => 'Comment vote removed',
    PUPI_FNS_Constants::LANG_ID_EVENT_U_MESSAGE_NAME => 'Message received',
    PUPI_FNS_Constants::LANG_ID_EVENT_U_WALL_POST_NAME => 'Post on wall received',
    PUPI_FNS_Constants::LANG_ID_EVENT_U_LEVEL_NAME => 'Level changed',

    PUPI_FNS_Constants::LANG_ID_NOTIFICATION_TEXT_U_LEVEL => 'Your level has changed. Check your profile!',

    PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_NO_NOTIFICATIONS => 'You have no notifications',
    PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_NO_UNREAD_NOTIFICATIONS => 'You have no unread notifications',
    PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_ONE_UNREAD_NOTIFICATION => 'You have 1 unread notification',
    PUPI_FNS_Constants::LANG_ID_NOTIFICATIONS_LIST_N_UNREAD_NOTIFICATIONS => 'You have ^ unread notifications',
];
