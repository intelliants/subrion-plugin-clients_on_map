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

class iaBackendController extends iaAbstractControllerModuleBackend
{
    protected $_name = 'clients_on_map';

    protected $_table = 'clients_on_map';

    protected $_gridFilters = ['client' => self::LIKE, 'status' => self::EQUAL];

    protected $_gridColumns = ['client', 'status'];

    protected function _indexPage(&$iaView)
    {
        $iaView->grid('_IA_URL_modules/' . $this->getModuleName() . '/js/admin/index');
    }


    protected function _gridQuery($columns, $where, $order, $start, $limit)
    {
        $sql = <<<SQL
SELECT SQL_CALC_FOUND_ROWS g.`id`, g.`client`, g.`status`, 1 `update`, 1 `delete` 
  FROM `:prefix:table_clients_on_map` g 
WHERE :where 
:order
LIMIT :start, :limit

SQL;
        $sql = iaDb::printf($sql, array(
            'prefix' => $this->_iaDb->prefix,
            'table_clients_on_map' => $this->getTable(),
            'start' => $start,
            'limit' => $limit,
            'order' => $order,
            'where' => $where
        ));

        return $this->_iaDb->getAll($sql);
    }

    protected function _preSaveEntry(array &$entry, array $data, $action)
    {
        parent::_preSaveEntry($entry, $data, $action);

        iaUtil::loadUTF8Functions('ascii', 'validation', 'bad', 'utf8_to_ascii');

        if (!utf8_is_valid($entry['client'])) {
            $entry['client'] = utf8_bad_replace($entry['client']);
        }
        if (empty($entry['client'])) {
            $this->addMessage('title_is_empty');
        }

        if ($this->getMessages()) {
            return false;
        }

        return true;
    }

    protected function _setDefaultValues(array &$entry)
    {
        $entry['lang'] = $this->_iaCore->iaView->language;
        $entry['lat'] = 37.09024;
        $entry['lng'] = -95.71289100000001;
    }
}
