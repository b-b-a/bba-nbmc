<?php

class User_Form_Admin_Add extends User_Form_Admin_Base
{
    public function init()
    {
        // make sure parent is called.
        parent::init();

        // specialize this form.
        $this->removeElement('userId');
    }
}