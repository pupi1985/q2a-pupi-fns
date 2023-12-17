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

class PUPI_FNS_ExtensionsManager
{
    /** @var PUPI_FNS_IExtension[] */
    private array $extensions = [];

    public function register(PUPI_FNS_IExtension $extension)
    {
        $this->extensions[] = $extension;
    }

    public function run(array $notification, $userId, string $handle): array
    {
        foreach ($this->extensions as $extension) {
            $notification = $extension->processNotification($notification, $userId, $handle);
        }

        return $notification;
    }
}
