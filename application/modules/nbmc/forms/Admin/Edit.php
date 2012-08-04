<?php
/**
 * Edit.php
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
 * Form Class Edit.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Form
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

class Nbmc_Form_Admin_Edit extends Nbmc_Form_Admin_Base
{
	public function init()
	{	
		//ProForma Invoice Sent: 	No
		
		$this->addElement('FilteringSelect', 'proFormaInvoiceSent', array(
			'label'         => 'ProForma Invoice Sent:',
			'validators'    => array(
					array('Digits', true)
			),
			'multiOptions'  => array(
					0 => 'No',
					1 => 'Yes'
			),
			'value'         => ''
		));
		
		//Recieied Payment: 	No
		
		$this->addElement('FilteringSelect', 'recievedPayment', array(
				'label'         => 'Recieied Payment:',
				'validators'    => array(
						array('Digits', true)
				),
				'multiOptions'  => array(
						0 => 'No',
						1 => 'Yes'
				),
				'value'         => ''
		));
		//Invoice Sent: 	No
		
		$this->addElement('FilteringSelect', 'invoiceSent', array(
				'label'         => 'Invoice Sent:',
				'validators'    => array(
						array('Digits', true)
				),
				'multiOptions'  => array(
						0 => 'No',
						1 => 'Yes'
				),
				'value'         => ''
		));
		
		parent::init();
	}
}
