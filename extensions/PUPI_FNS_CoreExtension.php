<?php

class PUPI_FNS_CoreExtension implements PUPI_FNS_IExtension
{

    const EVENT_TO_ICON_CLASS_MAP = [
        PUPI_FNS_NotificationTypes::EVENT_A_POST => 'pupi-fns-icon-chat',
        PUPI_FNS_NotificationTypes::EVENT_C_POST => 'pupi-fns-icon-chat-empty',
        PUPI_FNS_NotificationTypes::EVENT_Q_CLOSE => 'pupi-fns-icon-lock',
        PUPI_FNS_NotificationTypes::EVENT_Q_REOPEN => 'pupi-fns-icon-lock-open',
        PUPI_FNS_NotificationTypes::EVENT_Q_MOVE => 'pupi-fns-icon-tag',
        PUPI_FNS_NotificationTypes::EVENT_A_SELECT => 'pupi-fns-icon-check',
        PUPI_FNS_NotificationTypes::EVENT_A_UNSELECT => 'pupi-fns-icon-check-empty',
        PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_UP => 'pupi-fns-icon-up-open',
        PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_DOWN => 'pupi-fns-icon-down-open',
        PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_NIL => 'pupi-fns-icon-minus',
        PUPI_FNS_NotificationTypes::EVENT_A_VOTE_UP => 'pupi-fns-icon-up-open',
        PUPI_FNS_NotificationTypes::EVENT_A_VOTE_DOWN => 'pupi-fns-icon-down-open',
        PUPI_FNS_NotificationTypes::EVENT_A_VOTE_NIL => 'pupi-fns-icon-minus',
        PUPI_FNS_NotificationTypes::EVENT_C_VOTE_UP => 'pupi-fns-icon-up-open',
        PUPI_FNS_NotificationTypes::EVENT_C_VOTE_DOWN => 'pupi-fns-icon-down-open',
        PUPI_FNS_NotificationTypes::EVENT_C_VOTE_NIL => 'pupi-fns-icon-minus',
        PUPI_FNS_NotificationTypes::EVENT_U_MESSAGE => 'pupi-fns-icon-mail',
        PUPI_FNS_NotificationTypes::EVENT_U_WALL_POST => 'pupi-fns-icon-pin',
        PUPI_FNS_NotificationTypes::EVENT_U_LEVEL => 'pupi-fns-icon-user',
    ];

    /**
     * Receive a notification to be processed and identification of the user receiver of the notification.
     *
     * Notification structure to be returned (additional fields can be added): [
     *   'plugin_id': 'pupi_dm',
     *   'event_name': 'mention',
     *   'event_id': 0,
     *   'name': 'Mention',
     *   'created_at': '2023-12-24T18:32:20-03:00',
     *   'url': 'http://site.com/q2a/9997/how-to-clean-a-microwave-oven?show=10040#c10040',
     *   'text': 'You have been mentioned in a post',
     * ]
     *
     * @param array $notification
     * @param mixed $userId
     * @param string $handle
     *
     * @return array Processed notification
     */
    public function processNotification(array $notification, $userId, string $handle): array
    {
        if (isset($notification['plugin_id'])) {
            return $notification;
        }

        $eventId = $notification['event_id'];
        $params = $notification['params'];

        $eventInfo = PUPI_FNS_NotificationTypes::EVENT_INFO[$eventId];

        $notification = [
            'plugin_id' => $notification['plugin_id'],
            'event_name' => $eventInfo['eventName'],
            'event_id' => $eventId,
            'name' => pupi_fns()->lang($eventInfo['langIdName']),
            'created_at' => $notification['created_at'],
        ];

        switch ($eventId) {
            case PUPI_FNS_NotificationTypes::EVENT_A_POST:
                $url = qa_q_path($params['parent.postid'], $params['parent.title'], true, 'A', $params['postid']);
                $text = $params['parent.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_C_POST:
                $url = qa_q_path($params['question.postid'], $params['question.title'], true, 'C', $params['postid']);
                $text = $params['question.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_Q_CLOSE:
            case PUPI_FNS_NotificationTypes::EVENT_Q_REOPEN:
                $url = qa_q_path($params['oldquestion.postid'], $params['oldquestion.title'], true);
                $text = $params['oldquestion.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_Q_MOVE:
                $url = qa_q_path($params['postid'], $params['oldquestion.title'], true);
                $text = $params['oldquestion.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_A_SELECT:
            case PUPI_FNS_NotificationTypes::EVENT_A_UNSELECT:
                $url = qa_q_path($params['parent.postid'], $params['parent.title'], true, 'A', $params['postid']);
                $text = $params['parent.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_NIL:
                $url = qa_q_path($params['question.postid'], $params['question.title'], true);
                $text = $params['question.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_NIL:
                $url = qa_q_path($params['question.postid'], $params['question.title'], true, 'A', $params['postid']);
                $text = $params['question.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_NIL:
                $url = qa_q_path($params['question.postid'], $params['question.title'], true, 'C', $params['postid']);
                $text = $params['question.title'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_U_MESSAGE:
                $url = qa_path('messages', null, qa_opt('site_url'), null, 'm' . $params['messageid']);
                $text = $params['message'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_U_WALL_POST:
                $url = qa_path(sprintf('user/%s/wall', $handle), null, qa_opt('site_url'), null, 'm' . $params['messageid']);
                $text = $params['text'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_U_LEVEL:
                $url = qa_path('user/' . $params['handle'], null, qa_opt('site_url'), null, 'level');
                $text = pupi_fns()->lang(PUPI_FNS_Constants::LANG_ID_NOTIFICATION_TEXT_U_LEVEL);
                break;
            default:
                // Should never happen
                $url = null;
                $text = null;
        }

        $notification['url'] = $url;
        $notification['text'] = $text;

        if (!isset(self::EVENT_TO_ICON_CLASS_MAP[$eventId])) {
            return $notification;
        }

        $notification['icon'] = self::EVENT_TO_ICON_CLASS_MAP[$eventId];

        return $notification;
    }
}
