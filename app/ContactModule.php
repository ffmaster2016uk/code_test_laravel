<?php

namespace App;


abstract class ContactModule
{
    /**
     * private property, a list of object instances
     * @access private
     * @var array
     */

    private static $instances = [];

    /**
     * private property, a list of errors, error_key => error data
     * @access protected
     * @var array
     */

    protected $_errors = [];

    /**
     * Provides access to a single instance of a module using the singleton pattern
     *
     * @mvc Controller
     *
     * @param  array
     * @return object
     *
     * @todo due to the system will be ran with PHP5.4, I could not use eval(...) to pass multiple params to constructor,
     *       the available option is the reflection class, but this needs the constructors to be public, my intention
     *       was for the constructors to be protected for singleton instance. The alternative is to pass the args array
     *       to the constructor of the class, but this adds another array dimension. I've decided to pass args to the
     *       constructors as this allow me to keep the constructors protected and enforce instance protection, I've left
     *       the reflection implementation in commented code
     *
     */
    public static function getInstance() {

        $module = get_called_class();

        if ( ! isset( self::$instances[ $module ] ) ) {
            $args         = func_get_args();
            //$reflect      = new \ReflectionClass($module);
            //$new_instance = $reflect->newInstanceArgs($args);
            //self::$instances[ $module ] = $new_instance;
            self::$instances[ $module ] = new $module($args);
        }
        return self::$instances[ $module ];

    }

    /**
     * Render a template
     *
     * @mvc @model
     *
     * @param  string | boolean $default_template_path
     * @param  array  $variables
     * @param  string $require
     * @return string
     */
    protected static function render_template( $default_template_path = false, $variables = array(), $require = 'once' ) {

        $template_path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $default_template_path;

        if ( is_file( $template_path ) ) {
            extract( $variables );
            ob_start();

            if ( 'always' == $require ) {
                require( $template_path );
            } else {
                require_once( $template_path );
            }

            $template_content = ob_get_clean();
        } else {
            $template_content = '';
        }

        return $template_content;
    }

    /**
     * Constructor
     *
     * @mvc Controller
     */
    abstract protected function __construct();
}