<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * @var Zend_Application_Module_Autoloader
     */
    protected $_resourceLoader;

    /**
     * @var Zend_View
     */
    protected $_view;

    /**
     * @var Zend_Controller_Front
     */
    public $frontController;

    protected function _initStartSessionAfterDb()
    {
        $this->bootstrap('db');
		$this->bootstrap('session');
    }

    /**
     * Sets the logging for the application.
     */
    protected function _initLogging()
    {
        $log = new ZendSF_Log($this, true);
        $this->_logger = Zend_Registry::get('log');
        return $log;
    }

    /**
     * Add Global Action Helpers
     */
    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_Acl());
        Zend_Controller_Action_HelperBroker::addHelper(new ZendSF_Controller_Helper_Service());
    }

    /**
     * Sets up the helper paths for the application.
     */
    protected function _initGlobalViewHelperPath()
    {
        $this->bootstrap('view');

        $this->_view = $this->getResource('view');

        $this->_view->addHelperPath(
                APPLICATION_PATH . '/../library/ZendSF/View/Helper',
                'ZendSF_View_Helper'
        );
    }

    /**
     * Sets up and adds options config file to the registry.
     */
    protected function _initConfig()
    {
        $options = new Zend_Config_Ini(APPLICATION_PATH . '/configs/options.ini');
        Zend_Registry::set('config', $options);
    }

    /**
     * Sets up the main menu and adds it to the view navigation container.
     */
    protected function _initMenu()
    {
        $menu = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $this->_view->navigation(new Zend_Navigation($menu));
    }

    protected function _initViewSettings()
    {
        Zend_Dojo::enableView($this->_view);
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();

        $this->_view->dojo();
    }
}

