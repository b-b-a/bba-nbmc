<?php

class Nbmc_IndexController extends Zend_Controller_Action
{
    /**
     * @var Nbmc_Model_Nbmc
     */
    protected $_model;

    public function init()
    {
        $this->_model = new Nbmc_Model_Nbmc();
        $this->view->registerForm = $this->getBookingForm();
    }

    public function indexAction()
    {
        // action body
    }

    public function bookingFormAction()
    {

    }

    public function completeBookingAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->_helper->redirector('booking-form');
        }

        if (false === ($id = $this->_model->register($request->getPost()))) {
            return $this->render('booking-form');
        }

        // send email on success.
        $this->_model->mailConfirmation($id);

    }

    public function sitemapAction()
    {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $sitemap = $this->view->navigation()
            ->sitemap()
            ->setUseXmlDeclaration(true)
            ->setFormatOutput(true)
            ->setAcl($this->getHelper('acl')->getAcl())
            ->setRole('guest')
            ->render();

        $this->getResponse()
            ->setHeader('Content-Type', 'application/xml')
            ->setBody($sitemap);
    }

    

    public function getBookingForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['register'] = $this->_model->getForm('register');
        $this->_forms['register']->setAction($urlHelper->url(array(
            'action' => 'complete-booking'
        ), 'nbmc'));
        $this->_forms['register']->setMethod('post');

        return $this->_forms['register'];
    }

}

