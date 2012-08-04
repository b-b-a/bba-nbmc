<?php

class User_Model_DbTable_User extends ZendSF_Model_DbTable_Abstract
{
    protected $_name = 'user';
    protected $_primary = 'userId';
    protected $_rowClass = 'User_Model_DbTable_Row_User';

    public function getUserById($id)
    {
        return $this->find($id)->current();
    }

    public function getUserByEmail($email, $ignoreUser=null)
    {
        $select = $this->select();
        $select->where('email = ?', $email);

        if (null !== $ignoreUser) {
            $select->where('email != ?', $ignoreUser->email);
        }

        return $this->fetchRow($select);
    }

    public function getUserByUsername($username, $ignoreUser=null)
    {
        $select = $this->select();
        $select->where('username = ?', $username);

        if (null !== $ignoreUser) {
            $select->where('username != ?', $ignoreUser->email);
        }

        return $this->fetchRow($select);
    }
    
    protected function _getSearchSelect(array $search)
    {
        $select = $this->select(false)->from('user');

        if (!$search['user'] == '') {
            if (substr($search['user'], 0, 1) == '=') {
                $id = (int) substr($search['user'], 1);
                $select->where('userId = ?', $id);
            } else {
                $select->orWhere('username like ?', '%' . $search['user'] . '%');
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
        $select->columns(array('numRows' => 'COUNT(userId)'));
        $result = $this->fetchRow($select);

        return $result->numRows;
    }

    public function getUsers($paged=null, $order=null)
    {
        if (true === is_array($order)) {
            $select->order($order);
        }

        if (null !== $paged) {
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);

            $count = clone $select;
            $count->reset(Zend_Db_Select::COLUMNS);
            $count->reset(Zend_Db_Select::FROM);
            $count->from(
                'user',
                new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`')
            );
            $adapter->setRowCount($count);

            $itemsPerPage = Zend_Registry::get('config')
                ->paginate
                ->user
                ->users;

            $paginator = new Zend_Paginator($adapter);
            $paginator->setItemCountPerPage($itemsPerPage)
                ->setCurrentPageNumber((int) $paged);
            return $paginator;
        }

        return $this->fetchAll($select);
    }
}