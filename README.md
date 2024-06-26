# Flexible Notifications System [by [Gabriel Zanetti][author]]


## Description

Flexible Notifications System is a [Question2Answer][Q2A] plugin that allows users to receive notifications in a flexible and efficient way.

## Features

 * Generates notifications for users based on Q2A's events (e.g. an answer is added to a user's question)
 * Efficient database access
 * Avoids many database accesses by utilizing the core's built-in caching mechanisms (memcached is, by far, the fastest)
 * Allows plugins to extend the notifications from the core easily
 * Allows themes to replace the look and feel of the plugin
 * Tracks read/unread notifications
 * The built-in UI and the internal notification tracking logic are decoupled
 * Internationalization support
 * No need for core hacks or plugin overrides
 * Simple installation

## Requirements

 * Q2A version 1.8.0+
 * PHP 7.4.0+

## Installation instructions

 1. Copy the plugin directory into the `qa-plugin` directory
 1. Enable the plugin from the *Admin -> Plugins* menu option
 1. Click the `Save` button
 1. Initialize the database

## Documentation for users

Once installed, check the plugin settings. In there, you will be able to configure the following settings:

* **Maximum amount of notifications per user:** This is the maximum amount of notifications the server will store per user.
  You can input any number but, considering how volatile a notification is, it might not make sense to store 100 notifications.

  If a user has already reached their maximum number of notifications and a notification is generated to them, the oldest notification
  will be removed (regardless of whether it is read or not).

* **Use built-in user interface:** When checked, the UI used to display the notifications is the one shipped with the plugin.

  If you have access to another UI extension (e.g. one that comes with the theme you are using), you should install that one and
  uncheck this setting. Otherwise, keep it checked. 

## Documentation for developers

### General plugin architecture

The plugin provides a way to track events and their statuses (read or unread). The events are not the same as the ones triggered by the
`qa_report_event()` function. The events are just records in a plugin table. This way, the notifications can be generated without having
to create a record in the `^eventlog` table.

The events are exposed by means of an endpoint generated by a page module listening on `pupi-fns-notifications`. The endpoint has a
`type` GET parameter that can have two values `all` or `only-stats`. This all means that the notifications can be accessed in a URL
that would look like this: https://site.com/pupi-fns-notifications?type=all

The parameter value `all` will provide statistics information and also the notifications themselves. The value `only-stats` will only
return information about the statistics.

The endpoint will return a JSON-encoded response that will be similar to this:

```json

{
    "version": "1.0.0",
    "notifications_stats": {
        "total_notifications": 2,
        "unread_notifications": 1
    },
    "notifications": [
        {
            "plugin_id": null,
            "event_name": "c_post",
            "event_id": 1,
            "name": "Comment added",
            "created_at": "2024-01-13T17:48:50-03:00",
            "url": "http:\/\/site.com\/10059\/a-nice-question?show=10091#c10091",
            "text": "I have a comment about your question",
            "icon": "pupi-fns-icon-chat-empty",
            "is_read": false
        },
        {
            "plugin_id": null,
            "event_name": "a_post",
            "event_id": 0,
            "name": "Answer added",
            "created_at": "2024-01-13T17:48:30-03:00",
            "url": "http:\/\/site.com\/10060\/a-nicer-question?show=10097#a10097",
            "text": "This is the answer to a question",
            "icon": "pupi-fns-icon-chat",
            "is_read": true
        }
    ],
    "lang": "You have 1 unread notification"
}
```

Here is a field reference for the `notification` object:
 * `plugin_id`: Textual ID set by a plugin identifying their plugin. In this case, there is no plugin generating the notifications, but rather the core is. All core notifications will set this value to null
 notifications. It just exposes the ones from the Q2A core. That's why in this case is null
 * `event_name`: Textual ID of the notification. Just to help develpers when creating a new UI
 * `event_id`: Real numeric ID of the notification. Should be use interchangeably with the `event_name`
 * `name`: Short summary of the notification
 * `created_at`: Date and time in which the notification was registered. The timezone is the server's timezone
 * `url`: The URL that relates to the notification and where it should lead the user to, after clicking on it
 * `text`: Longer text of the notification
 * `icon`: Optional field that, in the built-in UI, adds a small icon using [Fontello](https://fontello.com).
 * `is_read`: Shows whether the notification has been read or not

Note the `lang` string is just a conveniece string displaying the amount of notifications in a localized way.

### Common use cases

There are two relevant use cases: plugin developers that want to trigger custom notifications from their plugins and theme
developers trying to style the notifications system according to their theme. Both cases will be covered next.

#### Extending the notifications system

In order for a plugin developer to add new custom notifications, the developer will need to:

 1. Define the structure of the new notification. It will need to match the structure mentioned before, but it can add new fields
 2. Find the right spot in the plugin code to insert the notification
 3. Define how the notification will be displayed with the structure previously defined

Below is a real life example of how to implement this with a plugin that triggers a notification whenever a user is mentioned in
a post using an `@`.

The notification structure, in this case, would be this one:

```json
{
    "plugin_id": "pupi_dm",
    "event_name": "mention",
    "event_id": 0,
    "name": "Mention",
    "created_at": "2024-01-14T17:03:29-03:00",
    "url": "http:\/\/site.com\/10059\/another-question?show=10093#c10093",
    "text": "user1 mentioned you in a post",
    "icon": "pupi-dm-icon-hashtag",
    "is_read": false
}
```
Take into account that some information is calculated by the FNS plugin, while other is added by the plugin being developed.
Furthermore, some information could be stored and fetched from the database, while other could be generated by PHP code. Choosing one
path or the other, is up to the plugin developer. The focus should be on efficiency and avoiding unnecessary database accesses.

For example, storing URLs might notn be a good idea. They should be dynamically generated. Otherwise, if the site URL changes later,
the notification will link to an incorrect page. So in order to link to a post, all is needed is the question the post belongs to
(note the title is not a must but might save a redirect), the post type, and the mentioned user handle. This way, it would be
possible to display a text that looks like "user1 mentioned in a post".

Other fields can be generated in PHP. There is no need to store the `event_name`, `name` or the `icon` in the database. In fact,
future releases of the plugin being developed might need to change data which, if stored in the database, might also need database
updates to be run in order to keep that data up-to-date.

So the fields so far look like this:

 * `plugin_id`: input when inserting the notification
 * `event_name`: handled by PHP code in the mentions plugin
 * `event_id`: input when inserting the notification
 * `name`: handled by PHP code in the mentions plugin
 * `created_at`: handled by FNS plugin
 * `url`: dynamically generated based on some params stored in the database
 * `text`: dynamically generated based on some params stored in the database
 * `icon`: handled by PHP code in the mentions plugin
 * `is_read`: handled by FNS plugin

Once the structure of the notification is thought in depth, then the insertion should happen. In the mentions plugin, it would happen
whenever a post is created or edited, so this will mean it will be inserted in an event module.

The notification is inserted using the `addNotification()` method of the `PUPI_FNS_NotificationsService` class. The parameters of the
method are the following:
 * `int $eventId`: the ID of the event (note it has to be unique only to your plugin)
 * `?string $pluginId`: the textual ID representation of your plugin. Note `null` is reserved for the core
 * `?array $userIds`: user IDs to send the notification to. Note `null` is reserved for the core
 * `array $params`: 1-dimension array containing the parameters that will be stored in a JSON format (the ones that need to be stored
 in the database, following the example)
 * `mixed $triggeringUserId`: the ID of the user that triggered the notification
 * `?string $createdAt`: an optional override of the date in which the notification was generated

Check the `PUPI_FNS_Setup.php` file for more information on the data types.

In the mentions plugin case, notifications could be inserted in this way:

```php
(new PUPI_FNS_NotificationsService())->addNotification(
    PUPI_DM_FNSExtension::NOTIFICATION_ID_MENTION,
    'pupi_dm',
    [$mentionedUserId],
    [
        'question.id' => $questionId,
        'question.title' => $questionTitle,
        'post.type' => $postType,
        'post.id' => $postId,
        'user.handle' => $handle,
    ],
    qa_get_logged_in_userid()
);
```

Note that the ID comes from another class that will be explained later.

For simplicity, the parameters need to be a 1-dimension array. The plugin developer is in control of the content in any other way
aside from that. If the parameters exceed the database field size, the FNS plugin will cut the longest strings in the array until it
fits the size. So it is important to keep the text lengths short.

The last part is displaying the notification. This is done by the UI, and the plugin will not care about that (too much). In order to
structure the params and turn them into something closer to the original notification structure, the parameters need to be processed.

The FNS allows other plugins to register extensions. Those extensions are run after fetching the raw notifications from the database
but before sending the response from the endpoint to the user. This allows plugins to hook in the middle of the generation of the
notifications.

In order for a plugin to register an extension to the FNS plugin, it will have to register itself by creating a `process` module,
like this:

```php
class PUPI_DM_FNSLoader
{
    public function plugins_loaded()
    {
        // Just in case the FNS plugin is disabled
        if (function_exists('pupi_fns')) {
            pupi_fns()->getExtensionsManager()->register(new PUPI_DM_FNSExtension());
        }
    }
}
```

This will make sure the class loader from the FNS plugin is already loaded when instantiating the extension.

In the mentions plugin example, the extension will look like this:

```php
class PUPI_DM_FNSExtension implements PUPI_FNS_IExtension
{
    const NOTIFICATION_ID_MENTION = 0;

    public function processNotification(array $notification, $userId, string $handle): array
    {
        if ($notification['plugin_id'] !== 'pupi_dm') {
            return $notification;
        }

        $result = [
            'plugin_id' => $notification['plugin_id'],
            'event_name' => '',
            'event_id' => $notification['event_id'],
            'name' => '',
            'created_at' => $notification['created_at'],
            'url' => '',
            'text' => '',
        ];

        switch ($notification['event_id']) {
            case self::NOTIFICATION_ID_MENTION:
                $result['event_name'] = 'mention';
                $result['name'] = qa_lang('pupi_dm/mention_notification_name');
                $result['url'] = qa_q_path(
                    $notification['params']['question.id'],
                    $notification['params']['question.title'],
                    true,
                    $notification['params']['post.type'],
                    $notification['params']['post.id']
                );
                $result['text'] = qa_lang_sub('pupi_dm/mention_notification_text', [$notification['params']['user.handle']]);
                $result['icon'] = 'pupi-dm-icon-hashtag';

                break;
            default:
        }

        return $result;
    }
}
```

This should close the loop. Each notification is given to all registered plugins. So it is important that each plugin return the
notification as it is, in case it does not belong to them. Then, the structure of the final notification is created. The `switch`
statement is unnecessary as there is only one notification, but the door for more to come was left open.

As the content of the notification was thought in depth and focused on avoiding database accesses, there is no need to query the
database in order to display the notifications. Everything is generated from what was stored in the params.

Note the `icon` also references a class. In the mentions plugin, Fontello was used to create a font just for that icon. There are
other alternative approaches. However, plugin developers mustn't make any assumptions about the existance of any of them 

There is a working example of an extension distributed with this plugin. Locate the file `extensions/PUPI_FNS_CoreExtension.php`.
It is the extension that turns raw core notifications into displayable notifications.

#### Visual customization of the UI

This is mainly intended for theme developers that want to display the notifications from the plugin in their custom theme. This is
actually a very simple process:

 1. Develop your own UI
 2. Tell your users to uncheck the "Use built-in user interface"

The FNS has two layers. One for the data and another for the UI. The data layer outputs in the `body_header` the following HTML:

```html
<script>
const pupi_fns_options = {
    "notifications_url_all": "http:\/\/site.com\/pupi-fns-notifications?type=all",
    "notifications_url_only_stats": "http:\/\/site.com\/pupi-fns-notifications?type=stats-only",
    "notification_stats": {
        "total_notifications": 20,
        "unread_notifications": 14
    }
};
</script>
```

The UI layer generates all the HTML needed to render the UI. This includes the bell button, its positioning, the sliding notifications
box, the logic to open and close it, the styling of each notification, etc.

Disabling the built-in UI means that everything needs to be done from scratch. The good thing is that you keep the `pupi_fns_options` object.
There is no need to query an endpoint to show the notification stats.

Once you have created your UI, you can call the notifications endpoint. Calling the notifications endpoint will return all the notifications
for the currently logged in user. At the same time, it will mark all the unread notifications as read.

You can take a look at the `PUPI_FNS_BuiltInNotificationsRendererLayer.php` file. Note that even though it is a layer, the same can be done
at a theme level.

## Support

If you have found a bug, create a ticket in the [Issues][issues] section.

## Get the plugin

The plugin can be downloaded from [this link][download]. You can say thanks [donating using PayPal][paypal].

[Q2A]: https://www.question2answer.org
[author]: https://question2answer.org/qa/user/pupi1985
[download]: https://github.com/pupi1985/q2a-pupi-fns/releases/latest
[issues]: https://github.com/pupi1985/q2a-pupi-fns/issues
[paypal]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Y7LUM6ML4UV9L
