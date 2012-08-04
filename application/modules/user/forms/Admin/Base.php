<?php

class User_Form_Admin_Base extends ZendSF_Dojo_Form_Abstract
{
    public function init()
    {
        // add path to custom validators.
        $this->addElementPrefixPath(
            'User_Validate',
            APPLICATION_PATH . '/modules/user/models/validate/',
            'validate'
        );

        $this->addElement('ValidationTextBox', 'firstname', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'      => true,
            'label'         => 'First Name:'
        ));

        $this->addElement('ValidationTextBox', 'lastname', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                'Alpha',
                array('StringLength', true, array(3, 128))
            ),
            'required'      => true,
            'label'         => 'Last Name:'
        ));
        
        $this->addElement('ValidationTextBox', 'username', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(3, 20))
            ),
            'required'      => true,
            'label'         => 'Username:'
        ));

        $this->addElement('PasswordTextBox', 'passwd', array(
            'filters'       => array('StringTrim'),
            'validators'    => array(
                array('StringLength', true, array(4, 128))
            ),
            'required'      => true,
            'label'         => 'Password'
        ));

        $this->addElement('PasswordTextBox', 'passwdVerify', array(
            'filters'       => array('StringTrim'),
            'validators'    => array('PasswordVerification'),
            'required'      => true,
            'label'         => 'Confirm Password:'
        ));
        
        $this->addElement('FilteringSelect', 'role', array(
                'label'         => 'Role:',
                'multiOptions'  => array(
                    'registered'    => 'User',
                    'admin'         => 'Admin'
                ),
                'value'         => 'registered'
        ));
        
        $this->addHiddenElement('userId', '');
        
        $this->addHiddenElement('type', '');

        $this->addElement('Button', 'userSubmit', array(
            'required'  => false,
            'ignore'    => true,
            'decorators'    => $this->_submitDecorators,
            'label'     => 'Submit',
            'value'     => 'Submit',
            'dijitParams'   => array(
                'onClick' => "return dijit.byId('userForm').validate()"
            ),
            'attribs' => array('type' => 'submit')
        ));

        $this->addElement('Button', 'userCancel', array(
            'required'  => false,
            'ignore'    => true,
            'decorators'    => $this->_submitDecorators,
            'label'     => 'Cancel',
            'value'     => 'Cancel',
            'dijitParams'   => array(
                'onClick' => "return sf.closeDialog(dijit.byId('userForm'))"
            )
        ));
        
        $this->addDisplayGroup(
        	array(
        		'userSubmit',
        		'userCancel',
        	),
        	'Buttons',
        	array(
        		'decorators' => array(
        			'FormElements',
        			array(
        				array('data' => 'HtmlTag'),
        				array(
        					'tag' => 'td',
        					'class' => 'submitElement',
        					'colspan' => '2'
        				)
        			),
        			array(
        				array('row' => 'HtmlTag'),
        				array(
        					'tag' => 'tr',
        					'class' => 'form_row'
        				)
        			)
        		)
        	));
    }
}