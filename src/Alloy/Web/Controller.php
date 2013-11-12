<?php

namespace Alloy\Web;

use Alloy\Controller as BaseController;
use Alloy\Core\IObserver;
use Alloy\Core\IViewElement;
use Alloy\Observers\Packet;

/**
 * Class Controller
 * Base controller for all web operations
 *
 * @package Alloy\Web
 */
abstract class Controller extends BaseController
{
    /**
     * @var Request
     */
    private $_request;

    /**
     * Elements to view
     *
     * @var IViewElement[]
     */
    protected $_elements = array();

    /**
     * Setter for the request
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Returns current controller's request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * {@inheritdoc}
     */
    public function show(IViewElement $element)
    {
        $this->_elements[] = $element;
    }

    /**
     * Returns all view elements for this controller
     *
     * @return IViewElement[]
     */
    public function getViewElements()
    {
        return $this->_elements;
    }
}