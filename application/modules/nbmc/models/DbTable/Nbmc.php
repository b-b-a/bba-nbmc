<?php
/**
 * Nbmc.php
 *
 * Copyright (c) 2012 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of BBA NBMC.
 *
 * BBA NBMC is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BBA NBMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BBA NBMC.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Model_DbTable
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Database adapter class for the Nbmc table.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Model_DbTable
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Model_DbTable_Nbmc extends ZendSF_Model_DbTable_Abstract
{
    /**
     * @var string database table
     */
    protected $_name = 'nbmc';

    /**
     * @var string primary key
     */
    protected $_primary = 'nbmcId';

    /**
     * @var string row class.
     */
    protected $_rowClass = 'Nbmc_Model_DbTable_Row_Nbmc';

    /**
     * @var array Reference map for parent tables
     */
    protected $_referenceMap = array();

    public function getNewInvoiceNumber()
    {
        $select = $this->select()
            ->from('nbmc', array('inv' => 'MAX(invoiceNumber)'));

        return $this->fetchRow($select)->inv;
    }

    protected function _getSearchSelect(array $search)
    {
        $select = $this->select(false)->from('nbmc');

        if (!$search['nbmc'] == '') {
            if (substr($search['nbmc'], 0, 1) == '=') {
                $id = (int) substr($search['nbmc'], 1);
                $select->where('nbmcId = ?', $id);
            } else {
                $select->orWhere('invoiceNumber like ?', '%' . $search['nbmc'] . '%')
                ->orWhere('forename like ?', '%' . $search['nbmc'] . '%')
                ->orWhere('lastname like ?', '%' . $search['nbmc'] . '%')
                ->orWhere('email like ?', '%' . $search['nbmc'] . '%')
                ->orWhere('postcode like ?', '%' . $search['nbmc'] . '%');;
            }
        }

        return $select;
    }

    public function search(array $search, $sort = '', $count = null, $offset = null)
    {
        $select = $this->_getSearchSelect($search);
        $select = $this->getLimit($select, $count, $offset);
        $select = $this->getSortOrder($select, $sort);

        return $this->fetchAll($select);
    }

    public function numRows($search)
    {
        $select = $this->_getSearchSelect($search);
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->columns(array('numRows' => 'COUNT(nbmcId)'));
        $result = $this->fetchRow($select);

        return $result->numRows;
    }
}
