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

class PUPI_FNS_NotificationsService
{
    private PUPI_FNS_UserNotificationStatsModel $userNotificationStatsModel;

    public function __construct()
    {
        $this->userNotificationStatsModel = new PUPI_FNS_UserNotificationStatsModel();
    }

    public function addNotification(int $eventId, ?string $pluginId, ?array $userIds, array $params, $triggeringUserId, ?string $createdAt = null): void
    {
        try {
            $notificationTypes = new PUPI_FNS_NotificationTypes();

            if (isset($pluginId)) {
                $notificationParams = $params;
            } else {
                $notificationParams = $notificationTypes->getNotificationParams($eventId, $params);
                $notificationParams = $this->getAdditionalParams($eventId, $notificationParams);
            }

            if (is_null($userIds)) {
                $userIds = $this->getRecipientUserIds($eventId, $notificationParams, $params, $triggeringUserId);
            }

            if (empty($userIds)) {
                return;
            }

            $paramsString = $this->fitParams($notificationParams);

            foreach ($userIds as $userId) {
                $this->addNotificationToSingleUser($eventId, $pluginId, $paramsString, $userId, $createdAt);
            }
        } catch (Exception $e) {
            error_log('PUPI_FNS: ' . $e->getMessage());
        }
    }

    private function getRecipientUserIds(int $eventId, array $notificationParams, array $params, $triggeringUserId): array
    {
        $result = [];

        switch ($eventId) {
            case PUPI_FNS_NotificationTypes::EVENT_A_POST:
                $result[$notificationParams['parent.userid']] = $notificationParams['parent.userid'];
                break;
            case PUPI_FNS_NotificationTypes::EVENT_C_POST:
                $result[$notificationParams['parent.userid']] = $notificationParams['parent.userid'];

                foreach ($params['thread'] as $comment) {
                    if (isset($comment['notify']) && !qa_post_is_by_user($comment, $triggeringUserId, null)) {
                        $result[$comment['userid']] = $comment['userid'];
                    }
                }

                break;

            case PUPI_FNS_NotificationTypes::EVENT_Q_CLOSE:
            case PUPI_FNS_NotificationTypes::EVENT_Q_REOPEN:
            case PUPI_FNS_NotificationTypes::EVENT_Q_MOVE:
                $result[$notificationParams['oldquestion.userid']] = $notificationParams['oldquestion.userid'];
                break;

            case PUPI_FNS_NotificationTypes::EVENT_A_SELECT:
            case PUPI_FNS_NotificationTypes::EVENT_A_UNSELECT:
                $result[$notificationParams['answer.userid']] = $notificationParams['answer.userid'];
                break;

            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_NIL:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_NIL:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_NIL:

            case PUPI_FNS_NotificationTypes::EVENT_U_MESSAGE:
            case PUPI_FNS_NotificationTypes::EVENT_U_WALL_POST:
            case PUPI_FNS_NotificationTypes::EVENT_U_LEVEL:
                $result[$notificationParams['userid']] = $notificationParams['userid'];
                break;
            default:
                // Should never happen
                return [];
        }

        unset($result[$triggeringUserId]);

        return array_flip($result);
    }

    /**
     * Reduce the size of the strings inside $params so that the total size is less or equal to PARAMS_FIELD_LENGTH
     * characters. The algorithm is not
     *
     * @param array $params
     *
     * @return string
     * @throws Exception If unable to shrink the params array enough
     */
    private function fitParams(array $params): string
    {
        // For qa_strlen(), qa_substr()
        require_once QA_INCLUDE_DIR . 'util/string.php';

        $paramString = json_encode($params);

        $diff = qa_strlen($paramString) - PUPI_FNS_Setup::PARAMS_FIELD_LENGTH;
        if ($diff <= 0) {
            return $paramString;
        }

        // Create an associative array with keys and their corresponding string lengths
        $stringLengths = [];
        foreach ($params as $key => $value) {
            $stringLengths[$key] = qa_strlen($value);
        }

        // Sort the array by string length in descending order, keeping the original key order
        arsort($stringLengths);

        foreach ($stringLengths as $key => $length) {
            $maxLength = max($length - $diff, 0);
            $params[$key] = qa_substr($params[$key], 0, $maxLength);

            $paramString = json_encode($params);
            $diff = qa_strlen($paramString) - PUPI_FNS_Setup::PARAMS_FIELD_LENGTH;

            // If the size is now within the limit, break out of the loop
            if ($diff <= 0) {
                return $paramString;
            }
        }

        // Should only happen whenever it's not possible to shrink the strings enough
        throw new Exception('Unable to shrink JSON string enough');
    }

    public function zeroUnread($userId)
    {
        $this->userNotificationStatsModel->update($userId, ['unread_notifications' => 0]);
        (new PUPI_FNS_NotificationsStatsCache())->delete($userId);
    }

    public function getNotificationsForUser($userId, string $handle, int $unreadNotifications): array
    {
        $notificationsModel = new PUPI_FNS_NotificationsModel();

        $maxNotifications = qa_opt(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER);

        $rawNotifications = $notificationsModel->getForUserId($userId, $maxNotifications);

        return $this->formatNotifications($rawNotifications, $userId, $handle, $unreadNotifications);
    }

    private function formatNotifications(array $rawNotifications, $userId, string $handle, int $unreadNotifications): array
    {
        $result = [];

        foreach ($rawNotifications as $index => $rawNotification) {
            $notification = pupi_fns()->getExtensionsManager()->run($rawNotification, $userId, $handle);
            $notification['is_read'] = $index + 1 > $unreadNotifications;

            $result[] = $notification;
        }

        return $result;
    }

    private function getAdditionalParams(int $eventId, array $notificationParams): array
    {
        $postsModel = new PUPI_FNS_PostsModel();

        switch ($eventId) {
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_Q_VOTE_NIL:
                $question = $postsModel->getById($notificationParams['postid']);

                $notificationParams['question.postid'] = $question['postid'];
                $notificationParams['question.title'] = $question['title'];
                break;

            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_A_VOTE_NIL:
                $answer = $postsModel->getById($notificationParams['postid']);
                $question = $postsModel->getById($answer['parentid']);

                $notificationParams['question.postid'] = $question['postid'];
                $notificationParams['question.title'] = $question['title'];
                break;

            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_UP:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_DOWN:
            case PUPI_FNS_NotificationTypes::EVENT_C_VOTE_NIL:
                $comment = $postsModel->getById($notificationParams['postid']);
                $parent = $postsModel->getById($comment['parentid']);

                $question = $parent['type'] === 'Q'
                    ? $parent
                    : $postsModel->getById($parent['parentid']);

                $notificationParams['question.postid'] = $question['postid'];
                $notificationParams['question.title'] = $question['title'];
                break;
            default:
        }

        return $notificationParams;
    }

    public function getNotificationsStatsForUser($userId): array
    {
        $statsCache = new PUPI_FNS_NotificationsStatsCache();

        $results = $statsCache->get($userId);

        if (!empty($results)) {
            return $results;
        }

        $results = (new PUPI_FNS_UserNotificationStatsModel())->getByUserId($userId) ?? [
            'total_notifications' => 0,
            'unread_notifications' => 0,
        ];

        $statsCache->save($userId, $results);

        return $results;
    }

    /**
     * @param int $eventId
     * @param string|null $pluginId
     * @param string $paramsString
     * @param mixed $userId
     * @param string|null $createdAt
     *
     * @return void
     */
    private function addNotificationToSingleUser(int $eventId, ?string $pluginId, string $paramsString, $userId, ?string $createdAt): void
    {
        $notificationsModel = new PUPI_FNS_NotificationsModel();
        $notificationsModel->insert($eventId, $pluginId, $paramsString, $userId, $createdAt);

        $userNotificationStats = $this->userNotificationStatsModel->getByUserId($userId);
        if (is_null($userNotificationStats)) {
            $this->userNotificationStatsModel->insert($userId, 1, 1);
        } else {
            $maxNotifications = (int)qa_opt(PUPI_FNS_Constants::SETTING_MAX_NOTIFICATIONS_PER_USER);

            $newTotalNotifications = min($userNotificationStats['total_notifications'] + 1, $maxNotifications);
            $newUnreadNotifications = min($userNotificationStats['unread_notifications'] + 1, $maxNotifications);

            $notificationsToDelete = max(0, $userNotificationStats['total_notifications'] - $maxNotifications + 1);

            // Delete oldest notification if needed
            if ($notificationsToDelete > 0) {
                $notificationsModel->deleteOldestNotifications($userId, $notificationsToDelete);
            }

            $updateFields = [];
            // Update total notifications count if changed
            if ($newTotalNotifications !== $userNotificationStats['total_notifications']) {
                $updateFields['total_notifications'] = $newTotalNotifications;
            }
            // Update unread notifications count if changed
            if ($newUnreadNotifications !== $userNotificationStats['unread_notifications']) {
                $updateFields['unread_notifications'] = $newUnreadNotifications;
            }

            if (!empty($updateFields)) {
                $this->userNotificationStatsModel->update($userId, $updateFields);
            }
        }

        (new PUPI_FNS_NotificationsStatsCache())->delete($userId);
    }
}
