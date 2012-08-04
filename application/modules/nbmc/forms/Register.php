<?php
/**
 * Register.php
 *
 * Copyright (c) 2012 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of BBA.
 *
 * BBA is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * BBA is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with BBA.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   BBA
 * @package
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Form Class Register.
 *
 * @category   BBA
 * @package
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Form_Register extends Nbmc_Form_Abstract
{
    public function init()
    {
        $this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 100)),
            ),
            'decorators' => $this->elementDecorators,
            'required'   => true,
            'label'      => 'First Name:',
            'attribs'   => array(
                'id'    => 'firstname',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 100)),
            ),
            'decorators' => $this->elementDecorators,
            'required'   => true,
            'label'      => 'Last Name:',
            'attribs'   => array(
                'id'    => 'lastname',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array(3, 255)),
                array('EmailAddress', true, array(
                    'mx'    => true
                ))
            ),
            'decorators' => $this->elementDecorators,
            'required'   => true,
            'label'      => 'Email:',
            'attribs'   => array(
                'id'    => 'email',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'telephone', array(
            'filters'    => array('StringTrim', 'StringToLower', 'StripTags', 'Digits'),
            'validators' => array(
                array('StringLength', true, array('min' => 6)),
                array('Digits', true)
            ),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Telephone:',
            'attribs'   => array(
                'id'    => 'telephone',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'company', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'validators' => array(
                array('StringLength', true, array('min' => 1))
            ),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Company:',
            'attribs'   => array(
                'id'    => 'company',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'address1', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Address Line 1:',
            'attribs'   => array(
                'id'    => 'address1',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'address2', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Address Line 2:',
            'attribs'   => array(
                'id'    => 'address2',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('text', 'postcode', array(
            'filters'    => array('StringTrim', 'StripTags', 'StringToUpper'),
            'validators' => array(
                 array('PostCode', true, array(
                    'locale' => 'en_GB'
                ))
            ),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Post Code:',
            'attribs'   => array(
                'id'    => 'postcode',
                'class' => 'fancyinput'
            ),
            'value'     => ''
        ));

        $this->addElement('Select', 'numberAttending', array(
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
            'decorators' => $this->selectDecorators,
            'attribs'   => array(
                'class' => 'fancyselect'
            ),
            'value'         => 1
        ));

        /*$this->addElement('textarea', 'requirements', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Special Dietary Requirements:',
            'attribs'   => array(
                'id'    => 'requirements',
                'class' => 'fancyinputarea',
                'rows'  => '10',
                'cols'  => '62'
            ),
            'value'     => ''
        ));*/

        $this->addElement('textarea', 'comments', array(
            'filters'    => array('StringTrim', 'StripTags'),
            'decorators' => $this->elementDecorators,
            'required'   => false,
            'label'      => 'Comments:',
            'attribs'   => array(
                'id'    => 'comments',
                'class' => 'fancyinputarea',
                'rows'  => '10',
                'cols'  => '62'
            ),
            'value'     => ''
        ));

        /*$this->addElement('captcha', 'captcha', array(
            'captcha'   => array(
                'captcha'   => 'ReCaptcha',
                'privKey'   => '6LfsqdQSAAAAAPxJ0RzRBNdCOoIb_j757R5_NhLa',
                'pubKey'    => '6LfsqdQSAAAAAIOn_ybXKLscTmtnD15yxP7OqMVY'
            ),
            'required'  => true,
            'label'     => 'Please enter the letters displayed below:',

        ));*/

        $this->addElement('submit', 'login', array(
            'required'  => false,
            'ignore'    => true,
            'label'     => 'Register',
            'decorators' => $this->submitDecorators,
            'attribs'   => array(
                'class' => 'fancybutton simple small_shadow'
            ),
            'value'     => 'Submit'
        ));
    }
}
