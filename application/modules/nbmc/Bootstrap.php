<?php
/**
 * Bootstrap.php
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
 * @package    NBMC
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * User Bootstrap
 *
 * @category   SF
 * @package    User
 * @subpackage Bootstrap
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Nbmc_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * @var Zend_Log
     */
    protected $_logger;

    /**
     * Sets the logging for the module.
     */
    protected function _initLogging()
    {
        $this->_logger = Zend_Registry::get('log');
        $this->app = $this->getApplication();
        $this->_logger->info(__METHOD__);
    }

    /**
     * Setup user module, autoloader and resource loader.
     */
    protected function _initDefaultModuleAutoloader()
    {
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Nbmc',
            'basePath'  => APPLICATION_PATH . '/modules/nbmc',
        ));

        $this->_resourceLoader->addResourceTypes(array(
            'widget' => array(
              'path'      => 'widgets',
              'namespace' => 'Widget',
            )
        ));
    }

    /**
     * Sets up the helper paths for the module.
     */
    protected function _initModuleViewHelperPath()
    {
        $this->_view = $this->app->getResource('view');

        $this->_view->addHelperPath(
            APPLICATION_PATH . '/modules/nbmc/views/helpers',
            'Nbmc_View_Helper'
        );
    }

    /**
     * Set up module routes.
     */
    protected function _initRoutes()
    {
        $router = $this->app->getResource('frontController')->getRouter();

        $routes = new Zend_Config_Ini(APPLICATION_PATH . '/modules/nbmc/configs/routes.ini');
        $router->addConfig($routes, 'routes');
    }
}
