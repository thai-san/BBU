<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Gisconverter {
	
		public function __construct(){
			if (version_compare(PHP_VERSION, '5.3') < 0) {
    			die ('Sorry, you need php at least 5.3 for gisconverter.php');
			}
			require_once APPPATH.'/libraries/Gisconverter.class.php';
		}

		public function wkt_to_geojson ($text) {
		    $decoder = new gisconverter\WKT();
		    return $decoder->geomFromText($text)->toGeoJSON();
		}

		public function wkt_to_kml ($text) {
		    $decoder = new gisconverter\WKT();
		    return $decoder->geomFromText($text)->toKML();
		}

		public function wkt_to_gpx($text) {
		    $decoder = new gisconverter\WKT();
		    return $decoder->geomFromText($text)->toGPX();
		}

		public function geojson_to_wkt ($text) {
		    $decoder = new gisconverter\GeoJSON();
		    return $decoder->geomFromText($text)->toWKT();
		}

		public function geojson_to_kml ($text) {
		    $decoder = new gisconverter\GeoJSON();
		    return $decoder->geomFromText($text)->toKML();
		}

		public function geojson_to_gpx ($text) {
		    $decoder = new gisconverter\GeoJSON();
		    return $decoder->geomFromText($text)->toGPX();
		}

		public function kml_to_wkt ($text) {
		    $decoder = new gisconverter\KML();
		    return $decoder->geomFromText($text)->toWKT();
		}

		public function kml_to_geojson ($text) {
		    $decoder = new gisconverter\KML();
		    return $decoder->geomFromText($text)->toGeoJSON();
		}

		public function kml_to_gpx ($text) {
		    $decoder = new gisconverter\KML();
		    return $decoder->geomFromText($text)->toGPX();
		}

		public function gpx_to_wkt ($text) {
		    $decoder = new gisconverter\GPX();
		    return $decoder->geomFromText($text)->toWKT();
		}

		public function gpx_to_geojson ($text) {
		    $decoder = new gisconverter\GPX();
		    return $decoder->geomFromText($text)->toGeoJSON();
		}

		public function gpx_to_kml ($text) {
		    $decoder = new gisconverter\GPX();
		    return $decoder->geomFromText($text)->toGPX();
		}
}

/* End of file Gisconverter.php */