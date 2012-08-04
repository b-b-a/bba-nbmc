<?php

class User_Model_User extends ZendSF_Model_Acl_Abstract
{
    public function getUserById($id)
    {
        $id = (int) $id;
        return $this->getDbTable('User')->getUserById($id);
    }

    public function getUserByEmail($email, $ignoreUser=null)
    {
        return $this->getDbTable('User')
            ->getUserByEmail($email, $ignoreUser);
    }

     public function getUserByUsername($username, $ignoreUser=null)
    {
        return $this->getDbTable('User')
            ->getUserByUsername($username, $ignoreUser);
    }

    public function getUsers($paged=false, $order=null)
    {
        return $this->getDbTable('User')
            ->getUsers($paged, $order);
    }

    public function registerUser($post)
    {
        if (!$this->checkAcl('register')) {
            throw new ZendSF_Acl_Exception('Insfficient rights');
        }

        $form = $this->getForm('register');

        return $this->_save($form, $post, array(
            'role' => 'registered'
        ));
    }

    public function add($post)
    {
        if (!$this->checkAcl('addUser')) {
            throw new ZendSF_Acl_Exception('Insufficient rights');
        }

        $form = $this->getForm('adminAdd');
        return $this->_save($form, $post);
    }
    
    public function edit($post)
    {
        if (!$this->checkAcl('editUser')) {
            throw new ZendSF_Acl_Exception('Insufficient rights');
        }
    
        $form = $this->getForm('adminEdit');
        $auth = new ZendSF_Service_Authentication();
        
        $defaults = array();
        
        if ($auth->getIdentity()->userId == $post['userId']) {
            $defaults = array('role' => $auth->getIdentity()->role);
        }
        
        return $this->_save($form, $post, $defaults);
    }
    
    public function delete($id)
    {
        if (!$this->checkAcl('delete')) {
            throw new ZendSF_Acl_Exception('Insfficient rights');
        }
         
        $id = (int) $id;
         
        $row = $this->getUserById($id);
        $auth = new ZendSF_Service_Authentication();
        
        if ($auth->getIdentity()->userId == $row->userId) {
            return false;
        }
        
        return $row->delete();
    }

    protected function _save(Zend_Form $form, array $info, $defaults=array())
    {
        if (!$form->isValid($info)) {
            return false;
        }

        // get filtered values.
        $data = $form->getValues();

        // password hashing.
        if (array_key_exists('passwd', $data) && '' != $data['passwd']) {
            $data['passwd'] = $this->_createPassword($data['passwd']);
        } else {
            unset ($data['passwd']);
        }

        // apply any defaults.
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = (array_key_exists('userId', $data)) ?
            $this->getDbTable('User')->getUserById($data['userId']) : null;

        return $this->getDbTable('User')->saveRow($data, $user);
    }

    private function _createPassword($passwd)
    {
        $auth = Zend_Registry::get('config')
            ->user
            ->auth;

        $treatment = $auth->credentialTreatment;

        return ZendSF_Utility_Password::$treatment(
            $passwd . $auth->salt
        );
    }

    /**
     * Gets the data store list, using search parameters.
     *
     * @param array $post
     * @return string JSON string
     */
    public function getDataStore(array $post)
    {
        $sort = $post['sort'];
        $count = $post['count'];
        $start = $post['start'];

        $form = $this->getForm('adminSearch');
        $search = array();

        if ($form->isValid($post)) {
            $search = $form->getValues();
        }

        $dataObj = $this->getDbTable('user')->search($search, $sort, $count, $start);

        $store = $this->_getDojoData($dataObj, 'userId');

        $store->setMetadata(
            'numRows',
            $this->getDbTable('user')->numRows($search)
        );

        return ($store->count()) ? $store->toJson() : '{}';
    }

    public function setAcl(Zend_Acl $acl)
    {
        parent::setAcl($acl);

        // implement rules here.
        $this->_acl->allow('admin', $this);

        return $this;
    }
}