<?php

namespace Alloy\Web;


use Alloy\Di\Configurator;
use Alloy\Observers\Observable;
use Alloy\Type\ChainNode;
use Alloy\Web\Exceptions\BadRouterConfig;
use Alloy\Web\Exceptions\NoRoute;

class Router extends Observable
{

    /**
     * @var ChainNode
     */
    private $_routes;

    /**
     * Creates new router from array
     *
     * @param array $array
     * @return Router
     */
    static public function createFromArray($array)
    {
        return new self(new ChainNode($array));
    }

    /**
     * Constructor
     *
     * @param ChainNode $config
     */
    public function __construct(ChainNode $config)
    {
        $this->_routes = $config;
    }

    /**
     * Returns true if router has route for provided request
     *
     * @param Request $request
     * @return bool
     */
    public function hasRoute(Request $request)
    {
        return $this->_getRoute($request) !== null;
    }

    /**
     * Returns controller for specified request
     *
     * @param Request $request
     * @return Controller
     * @throws Exceptions\BadRouterConfig
     * @throws Exceptions\NoRoute
     */
    public function getController(Request $request)
    {
        $route = $this->_getRoute($request);

        // No route (i.e. 404)
        if ($route === null) {
            throw new NoRoute();
        }

        return $this->_getController($route);
    }

    /**
     * Returns Controller for 404 errors (NoRoute)
     *
     * @return Controller
     * @throws Exceptions\BadRouterConfig
     */
    public function getControllerNoRoute()
    {
        $route = $this->_getRoute404();
        if ($route === null) {
            throw new BadRouterConfig();
        }

        return $this->_getController($route);
    }

    /**
     * Returns Controller for unhandled exceptions
     *
     * @return Controller
     * @throws Exceptions\BadRouterConfig
     */
    public function getControllerOnError()
    {
        $route = $this->_getRouteError();

        if ($route === null) {
            throw new BadRouterConfig();
        }

        return $this->_getController($route);
    }

    /**
     * Utility function
     *
     * @param ChainNode $cnf
     * @return Controller
     * @throws Exceptions\BadRouterConfig
     */
    protected function _getController(ChainNode $cnf)
    {
        // Controller not set
        if ($cnf->controller->isEmpty()) {
            throw new BadRouterConfig();
        }

        $className = $cnf->controller->getString();

        // Controller not exists
        if (!class_exists($className)) {
            throw new BadRouterConfig(
                'Controller ' . $className . ' not found'
            );
        }

        // Instantiation
        try {
            $ctrl = new $className();
            if (!$cnf->di->isEmpty()) {
                // Dependency injection
                $cnf = new Configurator();
                $cnf->apply($ctrl, $cnf->di);
            }
        } catch (\Exception $exception) {
            throw new BadRouterConfig(
                'Instantiation error for ' . $className,
                0,
                $exception
            );
        }

        // Type check
        if (!$ctrl instanceof Controller) {
            throw new BadRouterConfig(
                'Controller ' . $className . ' is not instance of web controller'
            );
        }

        return $ctrl;
    }

    /**
     * Utility function
     *
     * @param Request $request
     * @return ChainNode|null
     */
    protected function _getRoute(Request $request)
    {
        $this->_oDebug('Resolving route for ' . $request->getFullRequestUri());
        foreach ($this->_routes as $route) {
            /** @var ChainNode $route */

            // Index page routing
            if ($route->index->isTrue()) {
                return $route;
            }

            // Regex based routing
            if (!$route->regex->isEmpty()) {
                // Regex match
                if (preg_match(
                    $route->regex->getString(),
                    $request->getRoutingUri()
                )) {
                    // Found by regex
                    return $route;
                }
            }

            // Url position based routing
            if (!$route->urlpart->isEmpty()) {
                foreach ($route->urlpart() as $offset => $pattern) {
                    if ($request->getUriPart($offset) === $pattern) {
                        return $route;
                    }
                }
            }
        }

        // No route found
        return $this->_getRoute404();
    }

    /**
     * Returns 404 route if set
     *
     * @return ChainNode|null
     */
    protected function _getRoute404()
    {
        return isset($this->_routes['404']) ? $this->_routes['404'] : null;
    }

    /**
     * Returns error route is set
     * This route must be used on global-level exceptions
     *
     * @return ChainNode|null
     */
    protected function _getRouteError()
    {
        return isset($this->_routes['error']) ? $this->_routes['error'] : null;
    }

} 