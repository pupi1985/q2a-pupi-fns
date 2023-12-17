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

const createNode = (tagName, classes, parent) => {
    const node = document.createElement(tagName);

    if (classes) {
        node.classList.add(...classes);
    }

    if (parent) {
        parent.appendChild(node);
    }

    return node;
};

const createMainUi = () => {
    const updateUnreadNotifications = notificationCount => {
        labelNode.innerText = notificationCount;
        labelNode.style.display = notificationCount > 0 ? 'flex' : 'none';
    };

    const setNotificationListVisible = isVisible => {
        notificationListNode.style.display = isVisible ? 'flex' : 'none';
        if (!isVisible) {
            updateUnreadNotifications(0);
        }
    };

    const notificationIconContainer = createNode('div', ['pupi_fns_notification-icon-container'], null);

    document.querySelector('.qa-nav-main').after(notificationIconContainer);

    const bellIconNode = createNode('i', ['pupi-fns-icon-bell'], notificationIconContainer);
    bellIconNode.dataset.fetchingData = 'false';

    const notificationListNode = createNode('div', ['pupi_fns_notification-list'], null);
    notificationListNode.style.display = 'none';

    const labelNode = createNode('span', ['pupi_fns_notification-icon-label'], notificationIconContainer);
    updateUnreadNotifications(pupi_fns_options.notification_stats.unread_notifications);

    document.addEventListener('click', e => setNotificationListVisible(false));

    const notificationBellClickHandler = async e => {
        e.stopPropagation();

        const toggleBellIconLoading = isLoading => {
            bellIconNode.classList.toggle('pupi-fns-icon-bell', !isLoading);
            bellIconNode.classList.toggle('pupi-fns-icon-spin4', isLoading);
            bellIconNode.classList.toggle('animate-spin', isLoading);
            bellIconNode.dataset.fetchingData = isLoading ? 'true' : 'false';
        };

        const moveNotificationList = () => {
            if (window.screen.width >= 576) {
                notificationListNode.style.removeProperty('width');
                notificationIconContainer.appendChild(notificationListNode);
            } else {
                const mainNavWrapperNode = document.querySelector('.qam-main-nav-wrapper');
                notificationListNode.style.width = '100%';
                mainNavWrapperNode.appendChild(notificationListNode);
            }
        };

        const displayNoNotifications = lang => {
            notificationListNode.classList.add('pupi_fns_no-notifications');
            createNode('div', ['pupi_fns_notification-list-no-notifications-header'], notificationListNode);
            createNode('div', ['pupi_fns_notification-list-no-notifications-body'], notificationListNode).innerText = lang;
        };

        const displayNotifications = notifications => {
            for (const notification of notifications) {
                notificationListNode.appendChild(createNotificationItemNode(notification));
            }
        };

        const isFetchingData = bellIconNode.dataset.fetchingData === 'true';

        if (isFetchingData) {
            return;
        }

        const isVisible = notificationListNode.style.display === 'flex';

        if (isVisible) {
            setNotificationListVisible(false);
            return;
        }

        toggleBellIconLoading(true);
        moveNotificationList();

        try {
            const data = await fetchNotifications();

            notificationListNode.replaceChildren();

            if (data.notifications_stats.total_notifications === 0) {
                displayNoNotifications(data.lang);
            } else {
                updateUnreadNotifications(data.notifications_stats.unread_notifications);
                displayNotifications(data.notifications);
            }

            setNotificationListVisible(true);
        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            toggleBellIconLoading(false);
        }
    };

    notificationIconContainer.addEventListener('click', notificationBellClickHandler);
};

const createNotificationItemNode = notification => {
    const notificationItemNode = createNode('div', null, null);

    let notificationItemContainerNode = notificationItemNode;
    if (notification.url !== null) {
        notificationItemContainerNode = createNode('a', ['pupi_fns_notification-item'], notificationItemNode);
        notificationItemContainerNode.href = notification.url;
    } else {
        notificationItemContainerNode.classList.add('pupi_fns_notification-item');
    }

    if (notification.is_read) {
        notificationItemContainerNode.classList.add('pupi_fns_is-read');
    }

    const headerNode = createNode('div', ['pupi_fns_notification-item-header'], notificationItemContainerNode);

    createNode('div', ['pupi_fns_notification-item-name'], headerNode).innerText = notification.name;

    const notificationItemImageNode = createNode('div', ['pupi_fns_notification-item-image'], headerNode);
    createNode('i', [notification.icon], notificationItemImageNode);

    createNode('div', ['pupi_fns_notification-item-date'], headerNode).innerText = formatDate(notification.created_at);

    const notificationItemIsRead = createNode('div', ['pupi_fns_notification-item-is-read'], headerNode);
    createNode('div', ['pupi_fns_notification-item-is-read-dot', notification.is_read ? 'pupi_fns_notification-item-is-read-dot-grey' : 'pupi_fns_notification-item-is-read-dot-red'], notificationItemIsRead);

    createNode('div', ['pupi_fns_notification-item-body'], notificationItemContainerNode).innerText = notification.text;

    return notificationItemContainerNode;
};

const fetchNotifications = async () => {
    const response = await fetch(pupi_fns_options.notifications_url_all);

    return response.json();
};

const formatDate = dateString => {
    const createdAtDate = new Date(dateString);

    const twoDigitFormat = value => ('0' + value).slice(-2);

    const year = createdAtDate.getFullYear();
    const month = twoDigitFormat(createdAtDate.getMonth() + 1);
    const day = twoDigitFormat(createdAtDate.getDate());
    const hours = twoDigitFormat(createdAtDate.getHours());
    const minutes = twoDigitFormat(createdAtDate.getMinutes());
    const seconds = twoDigitFormat(createdAtDate.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

createMainUi();
