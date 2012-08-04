<?php
/**
 * NBMC.php
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
 * @subpackage Model
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * NBMC Model.
 *
 * @category   BBA
 * @package
 * @subpackage Model
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Model_Nbmc extends ZendSF_Model_Acl_Abstract
{
	/**
	 * 
	 * @param strin $id
	 * @return Zend_Db_Table_Row
	 */
    public function getNbmcById($id)
    {
        $id = (int) $id;
        return $this->getDbTable('nbmc')->getRowById($id);
    }
    
    public function getAll()
    {
    	return $this->getDbTable('nbmc')->fetchAll();
    }

    public function register(array $post)
    {
        $form = $this->getForm('register');
        
        return $this->_save($form, $post);
    }
    
    public function add(array $post)
    {
    	if (!$this->checkAcl('add')) {
    		throw new ZendSF_Acl_Exception('Insfficient rights');
    	}
    	
    	$form = $this->getForm('adminAdd');
    	
    	return $this->_save($form, $post);
    }
    
    public function edit(array $post)
    {
    	if (!$this->checkAcl('edit')) {
    		throw new ZendSF_Acl_Exception('Insfficient rights');
    	}
    	
    	$form = $this->getForm('adminEdit');
    	
    	return $this->_save($form, $post);
    }
    
    public function delete($id)
    {
    	if (!$this->checkAcl('delete')) {
    		throw new ZendSF_Acl_Exception('Insfficient rights');
    	}
    	
    	$id = (int) $id;
    	
    	$row = $this->getNbmcById($id);
    	
    	return $row->delete();
    }

    public function mailConfirmation($id)
    {
        $row = $this->getDbTable('nbmc')->getRowById($id);

        $options = Zend_Registry::get('config')
                ->mail;

        $public_path = realpath(APPLICATION_PATH . '/../public');

        $view = new Zend_View();

        $view->addScriptPath(
            realpath(APPLICATION_PATH
            . '/modules/nbmc/views/email')
        );

        $view->assign(array(
            'row'   => $row,
            'route' => $public_path
        ));

        $transport = new Zend_Mail_Transport_Smtp($options->host, $options->config->toArray());

        $mail = new Nbmc_Model_HtmlEmail();

        $mail->setBodyHtml($view->render('email.phtml'), 'UTF-8', Zend_Mime::MULTIPART_RELATED);
        $mail->setFrom($options->from->email, $options->from->name);
        $mail->addTo($row->email, $row->getFullName());
        $mail->setSubject('New Business Masterclass registration [NBMC' . $row->invoiceNumber . ']');
        $mail->buildHtml();
        $mail->send($transport);

        $mail = new Nbmc_Model_HtmlEmail();

        $mail->setBodyHtml($view->render('admin.phtml'), 'UTF-8', Zend_Mime::MULTIPART_RELATED);
        $mail->setFrom($row->email, $row->getFullName());
        $mail->addTo($options->from->email, $options->from->name);
        //$mail->addTo('shaun@shaunfreeman.co.uk', $options->from->name);
        $mail->setSubject('New Business Masterclass registration [NBMC' . $row->invoiceNumber . ']');
        $mail->buildHtml();
        $mail->send($transport);
    }
    
    protected function _save(Zend_Form $form, array $post)
    {
    	if (!$form->isValid($post)) {
    		return false;
    	}
    	
    	// get filtered values.
    	$data = $form->getValues();
    	
    	foreach ($data as $key => $value) {
    		if (!$value) $data[$key] = null;
    	}
    	
    	$inv = (array_key_exists('nbmcId', $data)) ?
    	$this->getDbTable('Nbmc')->getRowById($data['nbmcId']) : null;
    	
    	// if new row create an invoiceNo.
    	if (null === $inv) {
    		$lastInv = $this->getDbTable('Nbmc')->getNewInvoiceNumber();
    	
    		$data['invoiceNumber'] = ($lastInv) ? $lastInv + 1 : '1001';
    	}
    	
    	$id = $this->getDbTable('Nbmc')->saveRow($data, $inv);
    	
    	return $id;
    }

    /**
     * Gets the data store list, using search parameters.
     *
     * @param array $post
     * @return string JSON string
     */
    public function getDataStore(array $post)
    {
        $sort = $post['sort'];
        $count = $post['count'];
        $start = $post['start'];

        $form = $this->getForm('adminSearch');
        $search = array();

        if ($form->isValid($post)) {
            $search = $form->getValues();
        }

        $dataObj = $this->getDbTable('nbmc')->search($search, $sort, $count, $start);

        $store = $this->_getDojoData($dataObj, 'nbmcId');

        $store->setMetadata(
            'numRows',
            $this->getDbTable('nbmc')->numRows($search)
        );

        return ($store->count()) ? $store->toJson() : '{}';
    }

    public function setAcl(Zend_Acl $acl)
    {
        parent::setAcl($acl);

        // implement rules here.
        $this->_acl->allow('registered', $this);

        return $this;
    }
}