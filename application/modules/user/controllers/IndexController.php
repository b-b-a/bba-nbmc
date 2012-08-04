<?php

class User_IndexController extends Zend_Controller_Action
{
    protected $_model;
    protected $_authService;

    public function init()
    {
        // get the default model.
        $this->_model = new User_Model_User();
        $this->_authService = new ZendSF_Service_Authentication();

        // add forms.
        $this->view->loginForm = $this->getLoginForm();
    }

    public function loginAction()
    {}

    public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('login');
        }

        // validate.
        $form = $this->_forms['login'];

        if (!$form->isValid($request->getPost())) {
            return $this->render('login');
        }

        if (false === $this->_authService->authenticate($form->getValues())) {
            $form->setDescription('Login failed, please try again.');
            return $this->render('login');
        }

        return $this->_redirect('/nbmc');
    }

    public function logoutAction()
    {
        $this->_authService->clear();
        return $this->_redirect('/');
    }

    public function getLoginForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['login'] = $this->_model->getForm('login');
        $this->_forms['login']->setAction($urlHelper->url(array(
            'action' => 'authenticate'
        ), 'user'));
        $this->_forms['login']->setMethod('post');

        return $this->_forms['login'];
    }
}