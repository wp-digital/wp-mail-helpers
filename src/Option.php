<?php

namespace Innocode\Mail\Helpers;

/**
 * Class Option
 * @package Innocode\Mail\Helpers
 */
class Option
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * Option constructor.
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function get_name()
    {
        return Plugin::OPTION_GROUP . "_$this->_name";
    }

    /**
     * @return string|false
     */
    public function get_default()
    {
        $constant = 'MAIL_' . strtoupper( $this->_name );

        return defined( $constant ) ? constant( $constant ) : false;
    }

    /**
     * @return string|false
     */
    public function get()
    {
        return get_option( $this->get_name() );
    }

    /**
     * @param mixed       $value
     * @param string|bool $autoload
     * @return bool
     */
    public function update( $value, $autoload = null )
    {
        return update_option( $this->get_name(), $value, $autoload );
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return delete_option( $this->get_name() );
    }
}
