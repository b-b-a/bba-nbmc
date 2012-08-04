<?php

class User_Form_Admin_edit extends User_Form_Admin_Base
{
    public function init()
    {
        // make sure parent is called.
        parent::init();

        // specialize this form.
        $this->getElement('passwd')->setRequired(false);
        $this->getElement('passwdVerify')->setRequired(false);
    }
}