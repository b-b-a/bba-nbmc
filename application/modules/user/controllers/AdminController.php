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
 * @package    User
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Controller Class AdminController.
 *
 * @category   SF
 * @package    User
 * @subpackage Controller
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class User_AdminController extends Zend_Controller_Action
{
    /**
     * var User_Model_User
     */
    protected $_model;

    public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }

        $this->_model = new User_Model_User();
        $this->_authService = new ZendSF_Service_Authentication();

        $this->view->loginForm = $this->getLoginForm();
        $this->view->addForm = $this->getAddForm();

    }

    public function preDispatch()
    {
        if (!$this->_helper->acl('Admin')) {
            return $this->_redirect('/admin/nbmc');
        }
    }

    public function indexAction()
    {}

    public function detailsAction()
    {
        $user = $this->_model->getUserById($this->getRequest()->getParam('userId'));

        // assign search to the view script.
        $this->view->assign(array(
            'user'  => $user
        ));
    }

    public function loginAction()
    {}

    public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_redirect('admin');
        }

        // validate.
        $form = $this->_forms['login'];

        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }

        $log = Zend_Registry::get('log');
        $log->info($form->getValues());

        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }

        return $this->_redirect('admin');
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
		
		$row = $this->_model->getUserById($this->getRequest()->getParam('userId'));
		
		$data = $row->toArray(true);
		$data['type'] = 'edit';
		
		$form->populate($data)
		    ->getElement('passwd')
            ->setValue('');
		
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
    		return $this->_redirect('/admin/user');
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
    				'type'  => 'user'
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

        $id = $this->_model->delete($this->getRequest()->getParam('userId'));
    }

    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['login'] = $this->_model->getForm('adminLogin');
        $this->_forms['login']->setAction($urlHelper->url(array(
            'module'        => 'user',
            'controller'    => 'admin',
            'action'        => 'authenticate',
            'isAdmin'       => true
        ), 'user_admin'));
        $this->_forms['login']->setMethod('post');

        return $this->_forms['login'];
    }

    public function getAddForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['userAdd'] = $this->_model->getForm('adminAdd');
        $this->_forms['userAdd']->setAction($urlHelper->url(array(
            'action' => 'save'
        ), 'user_admin'));
        $this->_forms['userAdd']->setMethod('post');

        return $this->_forms['userAdd'];
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