<?php
/**
 * Registerable Ability
 *
 * @author     Tom Valk <tomvalk@lt-box.info>
 * @copyright  2016 Tom Valk
 */

namespace Arvici\Heart\Abilities;


interface Registrable
{
    /**
     * Register handler/ability.
     *
     * @param array $options
     *
     * @return mixed
     */
    public static function register($options = array());

    /**
     * Unregister handler/ability.
     *
     * @param array $options
     *
     * @return mixed
     */
    public static function unRegister($options = array());
}