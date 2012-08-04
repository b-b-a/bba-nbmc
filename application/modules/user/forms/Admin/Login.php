<?php

class User_Form_Admin_Login extends ZendSF_Dojo_Form_Abstract
{
    public function init()
    {
        $this->setName('auth');

        $this->addElement('ValidationTextBox', 'username', array(
            'label'     => 'User Name:',
            'required'  => true,
            'filters'   => array('StringTrim', 'StripTags')
        ));

        $this->addElement('PasswordTextBox', 'password', array(
            'label'     => 'Password:',
            'required'  => true,
            'filters'   => array('StringTrim', 'StripTags')
        ));

        $this->addSubmit('Login', 'loginSubmitButton');
        //$this->addHash('csrf');
    }
}
