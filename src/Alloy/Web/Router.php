<?php

namespace Alloy\Web;


use Alloy\Observers\Observable;
use Alloy\Type\ChainNode;

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
     *
     */
    public function getController()
    {

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
                if (preg_match($route->regex->getString(), $request->getRoutingUri())) {
                    // Found by regex
                    return $route;
                }
            }

            // Url position based routing
            if (!$route->urlpart->isEmpty()) {
                foreach($route->urlpart() as $offset => $pattern) {
                    if ($request->getUriPart($offset) === $pattern) {
                        return $route;
                    }
                }
            }
        }

        return null;
    }

} 