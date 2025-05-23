<?php
/**
 * Utils class.
 *
 * @since 1.3.6
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utils class.
 *
 * @since 1.3.6
 */
class OMAPI_Utils {

	/**
	 * Determines if given type is an inline type.
	 *
	 * @since  1.3.6
	 *
	 * @param  string $type Type to check.
	 *
	 * @return boolean
	 */
	public static function is_inline_type( $type ) {
		return 'post' === $type || 'inline' === $type;
	}

	/**
	 * Check if an item is in field.
	 *
	 * @since 2.16.17
	 *
	 * @param mixed  $item The item to check.
	 * @param array  $fields The fields to check.
	 * @param string $field The field to check.
	 *
	 * @return bool True if the item is in the field, false otherwise.
	 */
	public static function item_in_field( $item, $fields, $field ) {
		return $item
			&& is_array( $fields )
			&& ! empty( $fields[ $field ] )
			&& in_array( $item, (array) $fields[ $field ] );
	}

	/**
	 * Check if a field is not empty and has values.
	 *
	 * @since 2.16.17
	 *
	 * @param array  $fields The fields to check.
	 * @param string $field The field to check.
	 *
	 * @return bool True if the field is not empty and has values, false otherwise.
	 */
	public static function field_not_empty_array( $fields, $field ) {
		if ( empty( $fields[ $field ] ) ) {
			return false;
		}

		$values = array_values( (array) $fields[ $field ] );
		$values = array_filter( $values );

		return ! empty( $values ) ? $values : false;
	}

	/**
	 * WordPress utility functions.
	 */

	/**
	 * Check if the current page is the front page, home page, or search page.
	 *
	 * @since 2.16.17
	 *
	 * @return bool True if the current page is the front page, home page, or search page, false otherwise.
	 */
	public static function is_front_or_search() {
		return is_front_page() || is_home() || is_search();
	}

	/**
	 * Check if a term archive is enabled.
	 *
	 * @since 2.16.17
	 *
	 * @param int    $term_id The term ID.
	 * @param string $taxonomy The taxonomy.
	 *
	 * @return bool True if the term archive is enabled, false otherwise.
	 */
	public static function is_term_archive( $term_id, $taxonomy ) {
		if ( ! $term_id ) {
			return false;
		}
		return 'post_tag' === $taxonomy && is_tag( $term_id ) || is_tax( $taxonomy, $term_id );
	}

	/**
	 * Determines if AMP is enabled on the current request.
	 *
	 * @since 1.9.8
	 *
	 * @return bool True if AMP is enabled, false otherwise.
	 */
	public static function is_amp_enabled() {
		return ( function_exists( 'amp_is_request' ) && amp_is_request() )
			|| ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() );
	}

	/**
	 * Ensures a unique array.
	 *
	 * @since  1.9.10
	 *
	 * @param  array $val Array to clean.
	 *
	 * @return array       Cleaned array.
	 */
	public static function unique_array( $val ) {
		if ( empty( $val ) ) {
			return array();
		}

		$val = array_filter( $val );

		return array_unique( $val );
	}

	/**
	 * A back-compatible parse_url helper.
	 *
	 * @since 2.3.0
	 * @deprecated 2.16.3 Use `wp_parse_url`.
	 *
	 * @param  string $url URL to parse.
	 *
	 * @return array       The URL parts.
	 */
	public static function parse_url( $url ) {
		_deprecated_function( __METHOD__, '2.17.0', 'wp_parse_url' );
		return wp_parse_url( $url );
	}

	/**
	 * Build Inline Data
	 *
	 * @since 2.3.0
	 *
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 * @param string $data        String containing the javascript to be added.
	 *
	 * @return string The formatted script string.
	 */
	public static function build_inline_data( $object_name, $data ) {
		return sprintf( 'var %s = %s;', $object_name, self::json_encode( $data ) );
	}

	/**
	 * Add Inline Script
	 *
	 * @since 2.3.0
	 *
	 * @see WP_Scripts::add_inline_script()
	 *
	 * @param string $handle      Name of the script to add the inline script to.
	 * @param string $object_name Name for the JavaScript object. Passed directly, so it should be qualified JS variable.
	 * @param string $data        String containing the javascript to be added.
	 * @param string $position    Optional. Whether to add the inline script before the handle
	 *                            or after. Default 'after'.
	 *
	 * @return bool True on success, false on failure.
	 */
	public static function add_inline_script( $handle, $object_name, $data, $position = 'before' ) {
		$data   = apply_filters( 'om_add_inline_script', $data, $handle, $position, $object_name ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$output = self::build_inline_data( $object_name, $data );
		$output = apply_filters( 'om_add_inline_script_output', $output, $data, $handle, $position, $object_name ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		return wp_add_inline_script( $handle, $output, $position );
	}

	/**
	 * Back-compatible wp_json_encode wrapper.
	 *
	 * @since 2.6.1
	 *
	 * @param  mixed $data Data to encode.
	 *
	 * @return string      JSON-encoded data.
	 */
	public static function json_encode( $data ) {
		return function_exists( 'wp_json_encode' )
			? wp_json_encode( $data )
			: json_encode( $data ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	}

	/**
	 * Check if given date is before provided start date.
	 *
	 * @since 2.11.1
	 *
	 * @param  DateTime $compare The date to compare against the start date.
	 * @param  string   $start   The start date to compare against, in 'Y-m-d H:i:s' format.
	 *
	 * @return bool Whether the given date is before provided start date.
	 */
	public static function date_before( DateTime $compare, $start ) {
		$start = DateTime::createFromFormat( 'Y-m-d H:i:s', $start, $compare->getTimezone() );

		return $compare < $start;
	}

	/**
	 * Check if given date is between provided start/end date.
	 *
	 * @since 2.11.1
	 *
	 * @param  DateTime $compare The date to compare against the start/end date.
	 * @param  string   $start   The start date to compare against, in 'Y-m-d H:i:s' format.
	 * @param  string   $end     The end date to compare against, in 'Y-m-d H:i:s' format.
	 *
	 * @return bool Whether the given date is between provided start/end date.
	 */
	public static function date_within( DateTime $compare, $start, $end ) {
		return ! self::date_before( $compare, $start )
			&& $compare < DateTime::createFromFormat( 'Y-m-d H:i:s', $end, $compare->getTimezone() );
	}

	/**
	 * Get the domains for each language when WPML is enabled.
	 *
	 * @since 2.16.19
	 *
	 * @return array $language_switcher The array of language code and domains.
	 */
	public static function get_wpml_language_domains() {
		if ( ! self::is_wpml_active() ) {
			return array();
		}

		global $sitepress;

		// Get the language switcher settings.
		$language_switcher = $sitepress->get_setting( 'language_domains', array() );

		return $language_switcher;
	}

	/**
	 * Check if WPML is enabled.
	 *
	 * If "A different domain per language" is selected for "Language URL format",
	 * only then we are considering WPML is active. For language_negotiation_type setting:
	 *     1 = Different languages in directories;
	 *     2 = A different domain per language;
	 *     3 = Language name added as a parameter.
	 *
	 * @since 2.16.19
	 *
	 * @return bool True if WPML is active, false otherwise.
	 */
	public static function is_wpml_active() {
		global $sitepress;

		return defined( 'ICL_SITEPRESS_VERSION' ) && $sitepress && 2 === (int) $sitepress->get_setting( 'language_negotiation_type' );
	}

}
