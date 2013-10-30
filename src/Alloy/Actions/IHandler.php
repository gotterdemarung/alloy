<?php
namespace Alloy\Actions;

/**
 * Classes, implementing interface, must handle received argument
 * and return an answer
 *
 * @package Alloy\Actions
 */
interface IHandler
{
    /**
     * Receives data and handles it
     *
     * @param mixed $x Data to handle
     * @return mixed
     */
    public function handle($x);
}