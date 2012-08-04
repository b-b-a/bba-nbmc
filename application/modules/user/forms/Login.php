<?php

class User_Form_Login extends Nbmc_Form_Abstract
{
    public function init()
    {
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', true, array(3, 128)),
            ),
            'decorators' => $this->elementDecorators,
            'required'   => true,
            'label'      => 'User Name:',
            'attribs'   => array(
                'id'    => 'username',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('password', 'passwd', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', true, array(4, 128))
            ),
            'decorators' => $this->elementDecorators,
            'required'   => true,
            'label'      => 'Password:',
            'attribs'   => array(
                'id'    => 'passwd',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('hash', 'csrf', array(
            'ignore'    => true,
            'salt'      => 'unique',
            'decorators' => $this->elementDecorators,
        ));

        $this->addElement('submit', 'login', array(
            'required'  => false,
            'ignore'    => true,
            'label'     => 'Log In',
            'decorators' => $this->submitDecorators,
            'attribs'   => array(
                'class' => 'fancybutton simple small_shadow'
            ),
            'value'     => 'Submit'
        ));

        $this->setDescription('Please use the form below to access your account.');


    }
}
