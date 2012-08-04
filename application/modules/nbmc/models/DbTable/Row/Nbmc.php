<?php
/**
 * Nbmc.php
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
 * @subpackage Model_DbTable_Row
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Database class for the Nbmc table row.
 *
 * @category   BBA_NBMC
 * @package
 * @subpackage Model_DbTable_Row
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Model_DbTable_Row_Nbmc extends ZendSF_Model_DbTable_Row_Abstract
{
    public function getFullName()
    {
        return $this->getRow()->firstname . ' ' . $this->getRow()->lastname;
    }

    public function getInvoiceNumber()
    {
        return 'NBMC' . $this->getRow()->invoiceNumber;
    }

    public function getProFormaInvoiceSent()
    {
        return ($this->getRow()->proFormaInvoiceSent) ? 'Yes' : 'No';
    }

    public function getRecieiedPayment()
    {
        return ($this->getRow()->recieiedPayment) ? 'Yes' : 'No';
    }

    public function getInvoiceSent()
    {
        return ($this->getRow()->invoiceSent) ? 'Yes' : 'No';
    }

    /**
     * Returns row as an array, with optional date formating.
     *
     * @param string $dateFormat
     * @return array
     */
    public function toArray($raw=false)
    {
        $array = array();

        foreach ($this->getRow() as $key => $value) {

            if (true === $raw) {
                $array[$key] = $value;
            } else {
                switch ($key) {
                    case 'invoiceNumber':
                        $array[$key] = 'NBMC' . $value;
                        break;
                    case 'proFormaInvoiceSent':
                    case 'recieiedPayment':
                    case 'invoiceSent':
                        $array[$key] = ($value) ? 'Yes' : 'No';
                        break;
                    default:
                        $array[$key] = $value;
                        break;
                }
            }
        }

        return $array;
    }
}
