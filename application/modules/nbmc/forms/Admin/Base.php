<?php
/**
 * Base.php
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
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Form Class Base.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Form_Admin_Base extends ZendSF_Dojo_Form_Abstract
{
    public function init()
    {
        $this->addElement('ValidationTextBox', 'firstname', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 100)),
            ),
            'required'   => true,
            'label'      => 'First Name:',
            'value'     => ''
        ));

        $this->addElement('ValidationTextBox', 'lastname', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 100)),
            ),
            'required'   => true,
            'label'      => 'Last Name:',
            'value'     => ''
        ));

        $this->addElement('ValidationTextBox', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 255)),
                array('EmailAddress', true, array(
                    'mx'    => true
                ))
            ),
            'required'   => true,
            'label'      => 'Email:',
            'attribs'   => array(
                'id'    => 'email'
            ),
            'value'     => ''
        ));

        $this->addElement('TextBox', 'telephone', array(
            'filters'    => array('StringTrim', 'StringToLower', 'StripTags', 'Digits'),
            'validators' => array(
                array('StringLength', true, array('min' => 6)),
                array('Digits', true)
            ),
            'required'   => false,
            'label'      => 'Telephone:',
            'value'     => ''
        ));

        $this->addElement('TextBox', 'company', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array('min' => 1))
            ),
            'required'   => false,
            'label'      => 'Company:',
            'value'     => ''
        ));

        $this->addElement('TextBox', 'address1', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => false,
            'label'      => 'Address Line 1:',
            'value'     => ''
        ));

        $this->addElement('TextBox', 'address2', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'required'   => false,
            'label'      => 'Address Line 2:',
            'value'     => ''
        ));

        $this->addElement('TextBox', 'postcode', array(
            'filters'    => array('StringTrim', 'StripTags', 'StringToUpper'),
            'validators' => array(
                 array('PostCode', true, array(
                    'locale' => 'en_GB'
                ))
            ),
            'required'   => false,
            'label'      => 'Post Code:',
            'value'     => ''
        ));

        $this->addElement('FilteringSelect', 'numberAttending', array(
            'label'         => 'No. Attending:',
            'validators'    => array(
                array('Digits', true)
            ),
            'multiOptions'  => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            ),
            'value'         => 1
        ));

        $this->addElement('SimpleTextarea', 'comments', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'label'      => 'Comments:',
            'value'     => ''
        ));

        $this->addHiddenElement('nbmcId', '');
        
        $this->addHiddenElement('type', '');

        $this->addElement('Button', 'nbmcSubmit', array(
            'required'  => false,
            'ignore'    => true,
            'decorators'    => $this->_submitDecorators,
            'label'     => 'Submit',
            'value'     => 'Submit',
            'dijitParams'   => array(
                'onClick' => "return dijit.byId('nbmcForm').validate()"
            ),
            'attribs' => array('type' => 'submit')
        ));

        $this->addElement('Button', 'nbmcCancel', array(
            'required'  => false,
            'ignore'    => true,
            'decorators'    => $this->_submitDecorators,
            'label'     => 'Cancel',
            'value'     => 'Cancel',
            'dijitParams'   => array(
                'onClick' => "return sf.closeDialog(dijit.byId('nbmcForm'))"
            )
        ));
        
        $this->addDisplayGroup(
        	array(
        		'nbmcSubmit',
        		'nbmcCancel',
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
