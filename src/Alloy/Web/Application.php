<?php

namespace Alloy\Web;

use Alloy\Actions\IHandler;
use Alloy\Actions\IRunnable;
use Alloy\Observers\Observable;
use Alloy\Web\Dict\HTTPStatusCodes;
use Alloy\Web\Exceptions\NoRoute;

class Application extends Observable implements IRunnable
{
    protected $_router;
    protected $_request;


    public function __construct($routerConfig)
    {
        // Creating router
        $this->_router = new Router($routerConfig);

        // Creating request
        $this->_request = new Request();
    }

    /**
     * Performs an action
     *
     * @return void
     */
    public function run()
    {
        $ctrl = null;

        try {
            $this->_oLog('Handling request ' . $this->_request->getFullRequestUri());

            try {
                $ctrl = $this->_startNormal();
            } catch (NoRoute $e) {
                $this->_oLog('No route ' . $e->getMessage());
                $ctrl = $this->_start404();
            }

        } catch (\Exception $exception) {
            $this->_oWarn($e);
            // Trying to handle exception
            try {
                $ctrl = $this->_startOnError($exception);
            } catch (\Exception $inner) {
                // no success
                $this->_oWarn($inner);
            }
        }

        if ($ctrl === null) {
            // Unrecoverable exception
            if (!headers_sent()) {
                header(HTTPStatusCodes::getFullText(500));
            }
            exit(1);
        }

        exit(0);
    }

    protected function _startNormal()
    {
        // Trying to instantiate and run controller
        try {
            $ctrl = $this->_router->getController($this->_request);
            $this->_inflateController($ctrl);
            $ctrl->run();
            return $ctrl;
        } catch (NoRoute $e) {
            // No route, must use 404 route
            $this->_oLog('Route not found. ' . $e->getMessage());
            return false;
        }
    }

    protected function _start404()
    {
        // Trying to instantiate 404 route
        $ctrl = $this->_router->getControllerNoRoute();
        $this->_inflateController($ctrl);
        $ctrl->run();
        return $ctrl;
    }

    protected function _startOnError(\Exception $exception)
    {
        // Trying to instantiate error route
        $ctrl = $this->_router->getControllerOnError();
        $this->_inflateController($ctrl);
        if ($ctrl instanceof IHandler) {
            $ctrl->handle($exception);
        } else {
            $ctrl->run();
        }
        return $ctrl;
    }

    protected function _inflateController(Controller $controller)
    {
        $controller->setRequest($this->_request);
    }

}