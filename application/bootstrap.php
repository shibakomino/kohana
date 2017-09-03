<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
//require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load system kohana core
	require SYSPATH.'classes/Kohana'.EXT;
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
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

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

if (isset($_SERVER['SERVER_PROTOCOL']))
{
	// Replace the default protocol.
//	HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */

$environment = isset($_SERVER['KOHANA_ENV'])?
  constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV'])) :
  Kohana::PRODUCTION;


// include the module paths.
define('DEV_MODPATH', realpath(DOCROOT.'../dev-modules/').DIRECTORY_SEPARATOR);
Kohana::modules(array(
  'core-debug'      => DEV_MODPATH.'core/debug',
  'core-exception'  => DEV_MODPATH.'core/exception',
  'core-wrapper'    => MODPATH.'core/wrapper',
//  'core-config'      => MODPATH.'core/config',

//  'kohana-core-dev' => DEV_MODPATH.'kohana-core-dev',

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

  'sample'     => DEV_MODPATH.'sample',
));

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

$settings = array(
  'base_url' => isset($_SERVER['KOHANA_BASE_URL']) ? $_SERVER['KOHANA_BASE_URL'] : '/',
  'errors' => FALSE,
  'profile' => FALSE,
  'caching' => TRUE,
  'index_file'=>'',
);

//DEPRECIATED: the base url for digi3 preview should handing in .htaccess
if(preg_match('/digi3studio.com\/preview/', APPPATH) == 1){
  $settings['base_url'] = preg_replace('/(\/mnt\/www\/digi3studio.com)|(application\/)/', "",APPPATH);
}

switch ($environment) {
  case Kohana::DEVELOPMENT:
    $settings['caching'] = FALSE;
    break;
  case Kohana::TESTING:
  case Kohana::STAGING:
  case Kohana::PRODUCTION:
  default:
    break;
}

Kohana::init($settings);

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
 * Enable modules. Modules are referenced by a relative or absolute path.
 */


Kohana::modules_init();


/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
//Helper_Route is d3core
//\Kohana\Helper\Route::make_routes();


Kohana_Route::set('default', '(<controller>)(/<action>(/<id>))(.<format>)')
  ->defaults(array(
    'controller' => 'welcome',
    'action'     => 'index',
    'format'	 => 'php',
  ));

/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 * 
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
//Cookie::$salt = isset($_SERVER['KOHANA_COOKIE_SALT']) ? $_SERVER['KOHANA_COOKIE_SALT'] : 'behungrybefoolish';
