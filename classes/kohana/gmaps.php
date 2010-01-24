<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Google Maps library. Handles geocoding requests
 * as well as map visualization
 *
 * @package    Gmaps
 * @author     Guillermo Aguirre de Cárcer Domínguez
 * @copyright  (c) 2010 Guillermo Aguirre de Cárcer Domínguez
 * @license    http://kohanaphp.com/license.html
 */
abstract class Kohana_Gmaps {

	protected static $instance;
	protected $config;

	public static function instance()
	{
		if ( ! isset(Gmaps::$instance))
		{
			// Load the configuration for this type
			$config = Kohana::config('gmaps');
			
			// Create a new session instance
			Gmaps::$instance = new Gmaps($config);
		}
		return Gmaps::$instance;
	}

	/**
	 * Create an instance of Gmaps.
	 *
	 * @return  object
	 */
	public static function factory($config = array())
	{
		return new Gmaps($config);
	}

	/**
	 * Loads configuration options.
	 *
	 * @return  void
	 */
	public function __construct($config = array())
	{
		// Save the config in the object
		$this->config = $config;
	}
	
	/**
	 * Sends a GeoCoding Request
	 *
	 * @return  mixed
	 */
	public function geo_request($q, $gl = NULL, $ll = NULL, $spn = NULL)
	{
		// Format the URL
		$ca = $this->config->as_array();
		if (isset($gl)) $ca['gl'] = $gl;
		if (isset($ll) && isset($spn)) {
			$ca['ll'] = $ll;
			$ca['spn'] = $spn;
		}
		$url = $ca['url'].'?';
		unset($ca['url']);
		foreach ($ca as $key=>$value) $url .= '&'.$key.'='.$value;
		$url .= '&q='.str_replace(' ', '+', $address);
		echo $url.'<br />';
		// Get the server response
		$response = Remote::get($url);
		if ($this->config->get('output') == 'json') $response = json_decode($response);
		if ($response->Status->code == 200) return $response->Placemark;
		else return FALSE;
	}
	
}