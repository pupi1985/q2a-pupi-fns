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

class PUPI_FNS_PostsModel
{
    public function getById(int $postId): ?array
    {
        $sql =
            'SELECT `postid`, `parentid`, `type`, `title` ' .
            'FROM `^posts` ' .
            'WHERE `postid` = #';

        $result = qa_db_read_one_assoc(qa_db_query_sub($sql, $postId));

        if (is_null($result)) {
            return null;
        }

        $result['postid'] = (int)$result['postid'];
        $result['parentid'] = (int)$result['parentid'];
        $result['type'] = $result['type'][0];

        return $result;
    }
}
