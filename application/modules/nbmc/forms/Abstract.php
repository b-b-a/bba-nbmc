<?php
/**
 * Abstract.php
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
 * Form Class Abstract.
 *
 * @category   BBA
 * @package
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
abstract class Nbmc_Form_Abstract extends ZendSF_Form_Abstract
{
    public $elementDecorators = array(
        'ViewHelper',
        array('Errors', array('class' => 'valmsg')),
        'FormElements',
        'Label',
        array(
            array('row' => 'HtmlTag'),
            array('tag' => 'li')
        )
    );

    public $selectDecorators = array(
        'ViewHelper',
        array('Errors', array('class' => 'valmsg')),
        'FormElements',
        'Label',
        array(
            array('data' => 'HtmlTag'),
            array(
                'tag' => 'div',
                'class' => 'styled-select'
            )
        ),
        array(
            array('row' => 'HtmlTag'),
            array('tag' => 'li')
        )
    );

    protected $submitDecorators = array(
        'ViewHelper',
        'FormElements',
        array(
            array('row' => 'HtmlTag'),
            array('tag' => 'li')
        )
    );

    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array(
                'tag'   => 'ul',
                'class' => 'cform'
            )),
            array('Description', array(
                'placement' => 'prepend',
                'tag'       => 'h4'
            )),
            'Form',
        ));
    }
}
