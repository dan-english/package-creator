<?php
namespace Packages\Logic;

/**
 * Class HasLogicPassThrough
 *
 * @package Packages\Logic
 */
trait HasLogicPassThrough
{
    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this, 'logic') && method_exists($this->logic(), $method)) {
            return $this->logic()->$method($parameters);
        }

        return parent::__call($method, $parameters);
    }
}
