<?php
// -- Environment setup --------------------------------------------------------

// Load the Kohana class, Application folder is higher priority than system folder.
if (is_file(APPPATH . 'classes/Kohana' . EXT)) {
    require APPPATH . 'classes/Kohana' . EXT; // Application folder
} else {
    require SYSPATH . 'classes/Kohana' . EXT;    // Load system kohana core
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load_PSR4'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_internal_encoding('UTF-8');
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
//I18n::lang('en-us');

if (isset($_SERVER['SERVER_PROTOCOL'])) {
    // Replace the default protocol.
    HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
$environment = isset($_SERVER['KOHANA_ENV']) ?
    constant('Kohana::' . strtoupper($_SERVER['KOHANA_ENV'])) :
    Kohana::PRODUCTION;

switch ($environment) {
    case Kohana::DEVELOPMENT:
        $core_modules = [
            'core-exception' => DEV_MODPATH . 'core/exception',
//      'core-profiling'  => DEV_MODPATH.'core-profiling',
            'core-wrapper' => MODPATH . 'core/wrapper',
            'core-lowercase' => MODPATH. 'core-lowercase',
            'core-log' => MODPATH . 'core/log',
            'core-debug' => DEV_MODPATH . 'core/debug',

            'sample' => DEV_MODPATH . 'sample',
        ];

        break;
    case Kohana::TESTING:
    case Kohana::STAGING:
    case Kohana::PRODUCTION:
        $core_modules = [
            'core-cache' => MODPATH . 'core/cache',
            'core-wrapper' => MODPATH . 'core/wrapper',
            'core-lowercase' => MODPATH. 'core-lowercase',
            'core-log' => MODPATH . 'core/log',
        ];

    default:
        $core_modules = [];
        break;
}

// include the module paths.
URL::set_base_url(isset($_SERVER['KOHANA_BASE_URL']) ? $_SERVER['KOHANA_BASE_URL'] : '/');
URL::set_index_file('');

Kohana::init($environment);           //set the enviorment variable
Kohana::modules($core_modules, true); //just create the module list and extend the path for file searching;
Kohana::modules_init();               //Enable modules. Modules are referenced by a relative or absolute path.

//[
//  'core-exception'  => DEV_MODPATH.'core/exception',
//  'core-debug'      => DEV_MODPATH.'core/debug',

//  'core-config'      => MODPATH.'core/config',

//  'kohana-core-dev' => DEV_MODPATH.'kohana-core-dev',

//  'error'  => MODPATH.'core_mvc',
//  'core-wrapper'  => MODPATH.'core-wrapper', //kohana use wrapper to resolve conflict className, maybe upgrade to namespace.
// 'auth'       => MODPATH.'auth',       // Basic authentication
// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
// 'codebench'  => DEV_MODPATH.'codebench',  // Benchmarking tool
// 'database'   => MODPATH.'database',   // Database access
// 'image'      => MODPATH.'image',      // Image manipulation
// 'minion'     => MODPATH.'minion',     // CLI Tasks
// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
// 'unittest'   => DEV_MODPATH.'unittest',   // Unit testing
// 'userguide'  => DEV_MODPATH.'userguide',  // User guide and API documentation
//  'core-i18n'  => MODPATH.'core-i18n',
//  'core-log'   => MODPATH.'core-log',


/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
//for development environment with host file and virtual host;
//the local virtual host should start with local., eg: http://local.xxxxxx.xxx
//$settings['base_url'] = ;


/**
 * Attach a file reader to config. Multiple readers are supported.
 * http://kohanaframework.org/3.3/guide/kohana/config
 */
//Config::$ins = new Config();
//Config::$ins->attach(new Config_File());


//$config = new Config();
//$config->attach(new Config_File());

//Kohana::$config->attach(new Config_File);


/**
 * Attach the file write to logging. Multiple writers are supported.
 */
//Kohana::$log->attach(new Log_File(APPPATH.'logs'));


/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

//support add addition routes with weighting.
Route::make_routes();

Route::set('default', '(<controller>)(/<action>(/<id>))(.<format>)')
    ->defaults(array(
        'controller' => 'welcome',
        'action' => 'index',
        'format' => 'php',
    ));

/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 *
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
//Cookie::$salt = isset($_SERVER['KOHANA_COOKIE_SALT']) ? $_SERVER['KOHANA_COOKIE_SALT'] : 'behungrybefoolish';
