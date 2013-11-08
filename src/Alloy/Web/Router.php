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

        // Controller not set
        if ($route->controller->isEmpty()) {
            throw new BadRouterConfig();
        }

        $className = $route->controller->getString();

        // Controller not exists
        if (!class_exists($className)) {
            throw new BadRouterConfig(
                'Controller ' . $className . ' not found'
            );
        }

        // Instantiation
        try {
            $ctrl = new $className();
            if (!$route->di->isEmpty()) {
                // Dependency injection
                $cnf = new Configurator();
                $cnf->apply($ctrl, $route->di);
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

        return null;
    }

} 