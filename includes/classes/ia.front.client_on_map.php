<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2018 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://subrion.org/
 *
 ******************************************************************************/

class iaClientOnMap extends abstractModuleFront
{
    protected static $_table = 'clients_on_map';

    protected $_itemName = 'client';

    protected $_activityLog = ['item' => 'client'];

    protected $_statuses = [iaCore::STATUS_ACTIVE, iaCore::STATUS_INACTIVE];


    public function getClients()
    {
        $sql = <<<SQL
SELECT SQL_CALC_FOUND_ROWS *
  FROM `:prefix:clients_on_map`
WHERE `status` = ':status'
SQL;
        $sql = iaDb::printf($sql, array(
            'prefix' => $this->iaDb->prefix,
            'clients_on_map' => 'clients_on_map',
            'status' => iaCore::STATUS_ACTIVE
        ));

        $data = $this->iaDb->getAll($sql);
        $this->_processValues($data);

        return $data;
    }
}