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

class PUPI_FNS_NotificationTypes
{
    const EVENT_A_POST = 0;
    const EVENT_C_POST = 1;
    const EVENT_Q_CLOSE = 2;
    const EVENT_Q_REOPEN = 3;
    const EVENT_Q_MOVE = 4;
    const EVENT_A_SELECT = 5;
    const EVENT_A_UNSELECT = 6;
    const EVENT_Q_VOTE_UP = 7;
    const EVENT_Q_VOTE_DOWN = 8;
    const EVENT_Q_VOTE_NIL = 9;
    const EVENT_A_VOTE_UP = 10;
    const EVENT_A_VOTE_DOWN = 11;
    const EVENT_A_VOTE_NIL = 12;
    const EVENT_C_VOTE_UP = 13;
    const EVENT_C_VOTE_DOWN = 14;
    const EVENT_C_VOTE_NIL = 15;
    const EVENT_U_MESSAGE = 16;
    const EVENT_U_WALL_POST = 17;
    const EVENT_U_LEVEL = 18;

    const EVENT_STRING_TO_ID = [
        'a_post' => self::EVENT_A_POST,
        'c_post' => self::EVENT_C_POST,
        'q_close' => self::EVENT_Q_CLOSE,
        'q_reopen' => self::EVENT_Q_REOPEN,
        'q_move' => self::EVENT_Q_MOVE,
        'a_select' => self::EVENT_A_SELECT,
        'a_unselect' => self::EVENT_A_UNSELECT,
        'q_vote_up' => self::EVENT_Q_VOTE_UP,
        'q_vote_down' => self::EVENT_Q_VOTE_DOWN,
        'q_vote_nil' => self::EVENT_Q_VOTE_NIL,
        'a_vote_up' => self::EVENT_A_VOTE_UP,
        'a_vote_down' => self::EVENT_A_VOTE_DOWN,
        'a_vote_nil' => self::EVENT_A_VOTE_NIL,
        'c_vote_up' => self::EVENT_C_VOTE_UP,
        'c_vote_down' => self::EVENT_C_VOTE_DOWN,
        'c_vote_nil' => self::EVENT_C_VOTE_NIL,
        'u_message' => self::EVENT_U_MESSAGE,
        'u_wall_post' => self::EVENT_U_WALL_POST,
        'u_level' => self::EVENT_U_LEVEL,
    ];

    const EVENT_INFO = [
        self::EVENT_A_POST => [
            'eventName' => 'a_post',
            'paramsFilter' => ['postid', 'parent.postid', 'parent.type', 'parent.categoryid', 'parent.title', 'parent.userid', 'parent.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_POST_NAME,
        ],
        self::EVENT_C_POST => [
            'eventName' => 'c_post',
            'paramsFilter' => ['postid', 'parent.postid', 'parent.type', 'parent.userid', 'parent.handle', 'question.postid', 'question.categoryid', 'question.title', 'question.userid', 'question.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_C_POST_NAME,
        ],
        self::EVENT_Q_CLOSE => [
            'eventName' => 'q_close',
            'paramsFilter' => ['postid', 'reason', 'note', 'originalid', 'oldquestion.postid', 'oldquestion.categoryid', 'oldquestion.title', 'oldquestion.userid', 'oldquestion.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_CLOSE_NAME,
        ],
        self::EVENT_Q_REOPEN => [
            'eventName' => 'q_reopen',
            'paramsFilter' => ['postid', 'oldquestion.postid', 'oldquestion.categoryid', 'oldquestion.title', 'oldquestion.userid', 'oldquestion.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_REOPEN_NAME,
        ],
        self::EVENT_Q_MOVE => [
            'eventName' => 'q_move',
            'paramsFilter' => ['postid', 'categoryid', 'oldquestion.categoryid', 'oldquestion.title', 'oldquestion.userid', 'oldquestion.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_MOVE_NAME,
        ],
        self::EVENT_A_SELECT => [
            'eventName' => 'a_select',
            'paramsFilter' => ['postid', 'parent.postid', 'parent.categoryid', 'parent.title', 'parent.userid', 'parent.handle', 'answer.userid', 'answer.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_SELECT_NAME,
        ],
        self::EVENT_A_UNSELECT => [
            'eventName' => 'a_unselect',
            'paramsFilter' => ['postid', 'parent.postid', 'parent.categoryid', 'parent.title', 'parent.userid', 'parent.handle', 'answer.userid', 'answer.handle'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_UNSELECT_NAME,
        ],
        self::EVENT_Q_VOTE_UP => [
            'eventName' => 'q_vote_up',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_UP_NAME,
        ],
        self::EVENT_Q_VOTE_DOWN => [
            'eventName' => 'q_vote_down',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_DOWN_NAME,
        ],
        self::EVENT_Q_VOTE_NIL => [
            'eventName' => 'q_vote_nil',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_Q_VOTE_NIL_NAME,
        ],
        self::EVENT_A_VOTE_UP => [
            'eventName' => 'a_vote_up',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_UP_NAME,
        ],
        self::EVENT_A_VOTE_DOWN => [
            'eventName' => 'a_vote_down',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_DOWN_NAME,
        ],
        self::EVENT_A_VOTE_NIL => [
            'eventName' => 'a_vote_nil',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_A_VOTE_NIL_NAME,
        ],
        self::EVENT_C_VOTE_UP => [
            'eventName' => 'c_vote_up',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_UP_NAME,
        ],
        self::EVENT_C_VOTE_DOWN => [
            'eventName' => 'c_vote_down',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_DOWN_NAME,
        ],
        self::EVENT_C_VOTE_NIL => [
            'eventName' => 'c_vote_nil',
            'paramsFilter' => ['postid', 'userid', 'vote', 'oldvote'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_C_VOTE_NIL_NAME,
        ],
        self::EVENT_U_MESSAGE => [
            'eventName' => 'u_message',
            'paramsFilter' => ['userid', 'handle', 'messageid', 'message'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_U_MESSAGE_NAME,
        ],
        self::EVENT_U_WALL_POST => [
            'eventName' => 'u_wall_post',
            'paramsFilter' => ['userid', 'handle', 'messageid', 'content', 'format', 'text'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_U_WALL_POST_NAME,
        ],
        self::EVENT_U_LEVEL => [
            'eventName' => 'u_level',
            'paramsFilter' => ['userid', 'handle', 'level', 'oldlevel'],
            'langIdName' => PUPI_FNS_Constants::LANG_ID_EVENT_U_LEVEL_NAME,
        ],
    ];

    public function getEventId(string $event): ?int
    {
        return self::EVENT_STRING_TO_ID[$event] ?? null;
    }

    public function getNotificationParams(int $eventId, array $params): array
    {
        $paths = self::EVENT_INFO[$eventId]['paramsFilter'];

        return $this->filterArrayByDotNotation($params, $paths);
    }

    private function filterArrayByDotNotation(array $array, array $dotNotations): array
    {
        $filteredItem = [];

        foreach ($dotNotations as $dotNotation) {
            $keys = explode('.', $dotNotation);
            $value = $array;

            foreach ($keys as $key) {
                if (isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    $value = null;
                    break;
                }
            }

            $filteredItem[$dotNotation] = $value;
        }

        return $filteredItem;
    }
}
