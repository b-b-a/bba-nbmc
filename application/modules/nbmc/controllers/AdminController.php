<?php
/**
 * AdminController.php
 *
 * Copyright (c) 2011 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of SF.
 *
 * SF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SF.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   SF
 * @package
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Controller Class AdminController.
 *
 * @category   SF
 * @package
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_AdminController extends Zend_Controller_Action
{
	/**
	 * @var Nbmc_Model_Nbmc
	 */
	protected $_model;
	
    public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }

        $this->_model = new Nbmc_Model_Nbmc();
    }

    public function preDispatch()
    {
        if (!$this->_helper->acl('User')) {
            return $this->_redirect('/nbmc');
        }
    }

    public function indexAction()
    {}

    public function detailsAction()
    {
        $row = $this->_model->getNbmcById($this->getRequest()->getParam('nbmcId'));

        // assign search to the view script.
        $this->view->assign('nbmc', $row);
    }

    public function addAction()
    {
        $form = $this->_model->getForm('adminAdd');
        
        $form->populate($this->getRequest()->getParams());

        // assign search to the view script.
        $this->view->assign('addForm', $form);
    }

    public function editAction()
    {
		$form = $this->_model->getForm('adminEdit');
		
		$row = $this->_model->getNbmcById($this->getRequest()->getParam('nbmcId'));
		
		$data = $row->toArray(true);
		$data['type'] = 'edit';
		
		$form->populate($data);
		
		$this->view->assign(array(
			'editForm' => $form,
			'row'		=> $row
		));
    }
    
    public function saveAction()
    {
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	
    	$request = $this->getRequest();
    	
    	if (!$request->isXmlHttpRequest() && !$request->isPost()) {
    		return $this->_redirect('/admin/nbmc');
    	}
    	
    	$action = $request->getParam('type');
    	
    	$id = $this->_model->$action($request->getPost());

    	$returnJson = array('saved' => $id);
    	
    	if ($id == 0) {
    		$form = $this->_model->getForm('admin' . ucfirst($action));
    		
    		$form->populate($this->getRequest()->getParams());
    		
    		$this->view->assign($action . 'Form', $form);
    		
    		$html = $this->view->render('admin/' . $action . '.phtml');
    		$returnJson['html'] = $html;
    	} else {
    		$this->view->assign(array(
    				'id'    => $id,
    				'type'  => 'nbmc'
    		));
    		$html = $this->view->render('confirm.phtml');
    		$returnJson['html'] = $html;
    	}
    	
    	$this->getResponse()
    		->setHeader('Content-Type', 'application/json')
    		->setBody(json_encode($returnJson));
    }

    public function deleteAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);

        $id = $this->_model->delete($this->getRequest()->getParam('nbmcId'));
    }

    public function csvFileAction()
    {
        $data = $this->_model->getAll();

        // assign search to the view script.
        $this->view->assign(array(
            'data'  => $data
        ));
    }

    public function promoSendAction()
    {
        $this->view->layout()->disableLayout();

        $db = new Nbmc_Model_DbTable_Emails();
        $emailAddress = $db->getFirstInList();

        $num = $db->fetchAll()->count() - 1;

        $public_path = realpath(APPLICATION_PATH . '/../public');

        $this->view->assign(array(
            'emailId'   => $emailAddress->emailId,
            'email'     => $emailAddress->email,
            'numLeft'   => $num,
            'route'     => $public_path
        ));

        if ($emailAddress) {

            $path = APPLICATION_PATH . '../public/images/web';

            $images = array(
                array('path'=> $path . '/bg.jpg', 'cid'=>'bg.jpg'),
                array('path'=> $path . '/bba-link-up-logo.png', 'cid'=>'bba-link-up-logo.png'),
                array('path'=> $path . '/hr.png', 'cid'=>'hr.png'),
                array('path'=> $path . '/icons/check.png', 'cid'=>'check.png')
            );

            $message = $this->view->render('admin/promo.phtml');

            $options = Zend_Registry::get('config')
                    ->mail;

            $transport = new Zend_Mail_Transport_Smtp($options->host, $options->config->toArray());

            $mail = new Nbmc_Model_HtmlEmail();

            $mail->setBodyHtml($message, 'UTF-8', Zend_Mime::MULTIPART_RELATED);
            $mail->setFrom('donotreply@newbusinessmasterclass.co.uk', 'BBA Link-up');
            $mail->setReplyTo($options->from->email);
            $mail->addTo($emailAddress->email);
            $mail->setSubject('New Business Masterclass');
            $mail->buildHtml();
            $mail->send($transport);

            $emailAddress->delete();
        }
    }

    public function dataStoreAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);

        $data = $this->_model->getDataStore($this->_request->getPost());

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json')
            ->setBody($data);
    }
}