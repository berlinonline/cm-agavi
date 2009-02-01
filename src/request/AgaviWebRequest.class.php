<?php

// +---------------------------------------------------------------------------+
// | This file is part of the Agavi package.                                   |
// | Copyright (c) 2005-2009 the Agavi Project.                                |
// | Based on the Mojavi3 MVC Framework, Copyright (c) 2003-2005 Sean Kerr.    |
// |                                                                           |
// | For the full copyright and license information, please view the LICENSE   |
// | file that was distributed with this source code. You can also view the    |
// | LICENSE file online at http://www.agavi.org/LICENSE.txt                   |
// |   vi: set noexpandtab:                                                    |
// |   Local Variables:                                                        |
// |   indent-tabs-mode: t                                                     |
// |   End:                                                                    |
// +---------------------------------------------------------------------------+

/**
 * AgaviWebRequest provides additional support for web-only client requests
 * such as cookie and file manipulation.
 *
 * @package    agavi
 * @subpackage request
 *
 * @author     Sean Kerr <skerr@mojavi.org>
 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
 * @author     David Zülke <dz@bitxtender.com>
 * @copyright  Authors
 * @copyright  The Agavi Project
 *
 * @since      0.9.0
 *
 * @version    $Id$
 */
class AgaviWebRequest extends AgaviRequest
{
	/**
	 * @var        string The protocol information of this request.
	 */ 
	protected $protocol = null;
	
	/**
	 * @var        string The current URL scheme.
	 */
	protected $urlScheme = '';

	/**
	 * @var        string The current URL authority.
	 */
	protected $urlHost = '';

	/**
	 * @var        string The current URL authority.
	 */
	protected $urlPort = 0;

	/**
	 * @var        string The current URL path.
	 */
	protected $urlPath = '';

	/**
	 * @var        string The current URL query.
	 */
	protected $urlQuery = '';

	/**
	 * @var        string The current request URL (path and query).
	 */
	protected $requestUri = '';

	/**
	 * @var        string The current URL.
	 */
	protected $url = '';

	/**
	 * Get the request protocol information, e.g. "HTTP/1.1".
	 *
	 * @return     string The protocol information.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getProtocol()
	{
		return $this->protocol;
	}

	/**
	 * Retrieve the scheme part of a request URL, typically the protocol.
	 * Example: "http".
	 *
	 * @return     string The request URL scheme.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlScheme()
	{
		return $this->urlScheme;
	}

	/**
	 * Retrieve the hostname part of a request URL.
	 *
	 * @return     string The request URL hostname.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlHost()
	{
		return $this->urlHost;
	}

	/**
	 * Retrieve the hostname part of a request URL.
	 *
	 * @return     string The request URL hostname.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlPort()
	{
		return $this->urlPort;
	}

	/**
	 * Retrieve the request URL authority, typically host and port.
	 * Example: "foo.example.com:8080".
	 *
	 * @param      bool Whether or not ports 80 (for HTTP) and 433 (for HTTPS)
	 *                  should be included in the return string.
	 *
	 * @return     string The request URL authority.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlAuthority($forcePort = false)
	{
		$port = $this->getUrlPort();
		$scheme = $this->getUrlScheme();
		return $this->getUrlHost() . ($forcePort || AgaviToolkit::isPortNecessary($scheme, $port) ? ':' . $port : '');
	}

	/**
	 * Retrieve the relative part of the request URL, i.e. path and query.
	 * Example: "/foo/bar/baz?id=4815162342".
	 *
	 * @return     string The relative URL of the curent request.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getRequestUri()
	{
		return $this->requestUri;
	}

	/**
	 * Retrieve the path part of the URL.
	 * Example: "/foo/bar/baz".
	 *
	 * @return     string The path part of the URL.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlPath()
	{
		return $this->urlPath;
	}

	/**
	 * Retrieve the query part of the URL.
	 * Example: "id=4815162342".
	 *
	 * @return     string The query part of the URL, or an empty string.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrlQuery()
	{
		return $this->urlQuery;
	}

	/**
	 * Retrieve the full request URL, including protocol, server name, port (if
	 * necessary), and request URI.
	 * Example: "http://foo.example.com:8080/foo/bar/baz?id=4815162342".
	 *
	 * @return     string The URL of the curent request.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function getUrl()
	{
		return
			$this->getUrlScheme() . '://' .
			$this->getUrlAuthority() .
			$this->getRequestUri();
	}

	/**
	 * Whether or not HTTPS was used for this request.
	 *
	 * @return     bool True, if it's an HTTPS request, false otherwise.
	 *
	 * @author     David Zülke <david.zuelke@bitextender.com>
	 * @since      0.11.6
	 */
	public function isHttps()
	{
		return $this->getUrlScheme() == 'https';
	}

	/**
	 * Constructor.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setParameters(array(
			'request_data_holder_class' => 'AgaviWebRequestDataHolder',
		));
	}

	/**
	 * Clear magic quotes. Properly. That means keys are cleared, too.
	 *
	 * @param      array An array of data to be put out of it's misery.
	 *
	 * @return     array An array delivered from magic quotes.
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public final static function clearMagicQuotes($input)
	{
		// this method only works with PHP 5.2.7+
		// there used to be special code for versions < 5.2.2
		// http://bugs.php.net/bug.php?id=41093
		// but we now require 5.2.8 anyway in combination with magic_quotes_gpc, see initialize()
		// http://trac.agavi.org/ticket/953
		// http://trac.agavi.org/ticket/944
		// http://bugs.php.net/bug.php?id=41093
		
		$retval = array();

		foreach($input as $key => $value) {
			$key = stripslashes($key);

			if(is_array($value)) {
				$retval[$key] = self::clearMagicQuotes($value);
			} elseif(is_string($value)) {
				$retval[$key] = stripslashes($value);
			} else {
				$retval[$key] = $value;
			}
		}

		return $retval;
	}

	/**
	 * Initialize this Request.
	 *
	 * @param      AgaviContext An AgaviContext instance.
	 * @param      array        An associative array of initialization parameters.
	 *
	 * @throws     <b>AgaviInitializationException</b> If an error occurs while
	 *                                                 initializing this Request.
	 *
	 * @author     Veikko Mäkinen <mail@veikkomakinen.com>
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.9.0
	 */
	public function initialize(AgaviContext $context, array $parameters = array())
	{
		parent::initialize($context, $parameters);

		// very first thing to do: remove magic quotes
		if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			// check if we're on PHP < 5.2.8
			// http://trac.agavi.org/ticket/953
			// http://trac.agavi.org/ticket/945
			// http://bugs.php.net/bug.php?id=46313
			if(version_compare(PHP_VERSION, '5.2.8', 'lt')) {
				throw new AgaviException(
					"For security reasons, PHP 5.2.8 or later is required when magic_quotes_gpc is enabled. Upgrade to the latest PHP release or disable magic_quotes_gpc.\n" . 
					"\nMore info:\n" .
					"- http://trac.agavi.org/ticket/953\n" .
					"- http://trac.agavi.org/ticket/945\n" .
					"- http://bugs.php.net/bug.php?id=46313\n" .
					"\nAlso related:\n" .
					"- http://trac.agavi.org/ticket/944\n" .
					"- http://bugs.php.net/bug.php?id=41093\n"
				);
			}
			
			$rla = ini_get('register_long_arrays');
			$_GET = self::clearMagicQuotes($_GET);
			$_POST = self::clearMagicQuotes($_POST);
			$_COOKIE = self::clearMagicQuotes($_COOKIE);
			$_REQUEST = self::clearMagicQuotes($_REQUEST);
			$_FILES = self::clearMagicQuotes($_FILES);
			if($rla) {
				$GLOBALS['HTTP_GET_VARS'] = $_GET;
				$GLOBALS['HTTP_POST_VARS'] = $_POST;
				$GLOBALS['HTTP_COOKIE_VARS'] = $_COOKIE;
				$GLOBALS['HTTP_POST_FILES'] = $_FILES;
			}
		}

		$sources = array_merge(array(
			'HTTPS' => 'HTTPS',
			'REQUEST_METHOD' => 'REQUEST_METHOD',
			'SERVER_NAME' => 'SERVER_NAME',
			'SERVER_PORT' => 'SERVER_PORT',
			'SERVER_PROTOCOL' => 'SERVER_PROTOCOL',
		), (array)$this->getParameter('sources'));
		$this->setParameter('sources', $sources);

		// this is correct: if a user-supplied parameter was set, then null is used as the default return value, which means getSourceValue() returns the user-supplied parameter as a last resort if a key of the same name could not be found. allows setting of static values for any of those below
		$sourceDefaults = array(
			'HTTPS' => isset($parameters['sources']['HTTPS']) ? null : 'off',
			'REQUEST_METHOD' => isset($parameters['sources']['REQUEST_METHOD']) ? null : 'GET',
			'SERVER_NAME' => null,
			'SERVER_PORT' => isset($parameters['sources']['SERVER_PORT']) ? null : $this->urlPort,
			'SERVER_PROTOCOL' => isset($parameters['sources']['SERVER_PROTOCOL']) ? null : 'HTTP/1.0',
		);

		$methods = array_merge(array(
			'GET' => 'read',
			'POST' => 'write',
			'PUT' => 'create',
			'DELETE' => 'remove',
		), (array)$this->getParameter('method_names'));
		$this->setParameter('method_names', $methods);

		$REQUEST_METHOD = self::getSourceValue($sources['REQUEST_METHOD'], $sourceDefaults['REQUEST_METHOD']);

		// map REQUEST_METHOD value to a method name, or fall back to the default in $sourceDefaults.
		// if someone set a static value as default for a source that does not have a mapping, then he's really asking for it, and thus out of luck
		$this->setMethod($this->getParameter(sprintf('method_names[%s]', $REQUEST_METHOD), $this->getParameter(sprintf('method_names[%s]', $sourceDefaults['REQUEST_METHOD']))));
		
		$this->protocol = self::getSourceValue($sources['SERVER_PROTOCOL'], $sourceDefaults['SERVER_PROTOCOL']);
		
		$HTTPS = self::getSourceValue($sources['HTTPS'], $sourceDefaults['HTTPS']);

		$this->urlScheme = 'http' . (strtolower($HTTPS) == 'on' ? 's' : '');

		$this->urlPort = (int)self::getSourceValue($sources['SERVER_PORT'], $sourceDefaults['SERVER_PORT']);

		$SERVER_NAME = self::getSourceValue($sources['SERVER_NAME'], $sourceDefaults['SERVER_NAME']);
		$port = $this->getUrlPort();
		if(preg_match_all('/\:/', $SERVER_NAME, $m) > 1) {
			$this->urlHost = preg_replace('/\]\:' . preg_quote($port, '/') . '$/', '', $SERVER_NAME);
		} else {
			$this->urlHost = preg_replace('/\:' . preg_quote($port, '/') . '$/', '', $SERVER_NAME);
		}

		if(isset($_SERVER['HTTP_X_REWRITE_URL']) && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
			// Microsoft IIS with ISAPI_Rewrite
			$this->requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
		} elseif(!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false) {
			// Microsoft IIS with PHP in CGI mode
			$this->requestUri = $_SERVER['ORIG_PATH_INFO'] . (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ? '?' . $_SERVER['QUERY_STRING'] : '');
		} elseif(isset($_SERVER['REQUEST_URI'])) {
			$this->requestUri = $_SERVER['REQUEST_URI'];
		}

		// Microsoft IIS with PHP in CGI mode
		if(!isset($_SERVER['QUERY_STRING'])) {
			$_SERVER['QUERY_STRING'] = '';
		}
		if(!isset($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = $this->getRequestUri();
		}

		// 'scheme://authority' is necessary so parse_url doesn't stumble over '://' in the request URI
		$parts = array_merge(array('path' => '', 'query' => ''), parse_url('scheme://authority' . $this->getRequestUri()));
		$this->urlPath = $parts['path'];
		$this->urlQuery = $parts['query'];
		unset($parts);

		if($this->getMethod() == $methods['PUT']) {
			// PUT. We now gotta set a flag for that and populate $_FILES manually

			$putFile = tempnam(AgaviConfig::get('core.cache_dir'), "PUTUpload_");
			$size = stream_copy_to_stream(fopen("php://input", "rb"), $handle = fopen($putFile, "wb"));
			fclose($handle);

			$_FILES = array(
				$this->getParameter('http_put_file_name', 'put_file') => array(
					'name' => $putFile,
					'type' => isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'application/octet-stream',
					'size' => $size,
					'tmp_name' => $putFile,
					'error' => UPLOAD_ERR_OK,
					'is_uploaded_file' => false,
				)
			);
		}

		$headers = array();
		foreach($_SERVER as $key => $value) {
			if(substr($key, 0, 5) == 'HTTP_') {
				$headers[substr($key, 5)] = $value;
			}
		}

		$rdhc = $this->getParameter('request_data_holder_class');
		$this->setRequestData(new $rdhc(array(
			constant("$rdhc::SOURCE_PARAMETERS") => array_merge($_GET, $_POST),
			constant("$rdhc::SOURCE_COOKIES") => $_COOKIE,
			constant("$rdhc::SOURCE_FILES") => $_FILES,
			constant("$rdhc::SOURCE_HEADERS") => $headers,
		)));
	}

	/**
	 * Do any necessary startup work after initialization.
	 *
	 * This method is not called directly after initialize().
	 *
	 * @author     David Zülke <dz@bitxtender.com>
	 * @since      0.11.0
	 */
	public function startup()
	{
		parent::startup();
		
		if($this->getParameter("unset_input", true)) {
			$rla = ini_get('register_long_arrays');
			
			$_GET = $_POST = $_COOKIE = $_REQUEST = $_FILES = array();
			if($rla) {
				// clean long arrays, too!
				$GLOBALS['HTTP_GET_VARS'] = $GLOBALS['HTTP_POST_VARS'] = $GLOBALS['HTTP_COOKIE_VARS'] = $GLOBALS['HTTP_POST_FILES'] = array();
			}
			
			foreach($_SERVER as $key => $value) {
				if(substr($key, 0, 5) == 'HTTP_') {
					unset($_SERVER[$key]);
					unset($_ENV[$key]);
					if($rla) {
						unset($GLOBALS['HTTP_SERVER_VARS'][$key]);
						unset($GLOBALS['HTTP_ENV_VARS'][$key]);
					}
				}
			}
		}
	}
}

?>