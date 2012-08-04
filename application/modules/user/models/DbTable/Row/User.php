<?php

class User_Model_DbTable_Row_User extends ZendSF_Model_DbTable_Row_Abstract
    implements Zend_Acl_Role_Interface
{
    public function getFullName()
    {
        return $this->getRow()->firstname .
            ' ' .
            $this->getRow()->lastname;
    }

    public function getRoleId()
    {
        if (null === $this->getRow()->role) {
            return 'guest';
        }

        return $this->getRow()->role;
    }
}