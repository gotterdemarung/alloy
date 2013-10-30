<?php

namespace Alloy\Core;

/**
 * Base interface for all serializers
 */
interface ISerializer
{
    /**
     * Serializes $data into the string
     *
     * @param mixed $data
     * @return string
     */
    public function pack($data);

    /**
     * Serializes $data, but threats it as array
     *
     * @param array $data
     * @return mixed
     */
    public function packArray($data);

    /**
     * Deserializes $data and returns data
     *
     * @param string $data
     * @return mixed
     */
    public function unpack($data);

    /**
     * Deserializes $data and tries to provide
     * array as result
     *
     * @param string $data
     * @return array|mixed
     */
    public function unpackArray($data);
}