<?php

namespace Mdm\AdsManager;

use \Mdm\AdsManager\Utilities as Utilities;

class Database extends \Mdm\AdsManager {

	public static $display_table = 'adunit_display_rules';

	public static $stats_table = 'adunit_display_stats';

	public static function createTables() {
		// We need to include upgrade.php from wp-admin
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		// Inlude wpdb class
		global $wpdb;
		// Create display table
		$display_table      = $wpdb->prefix . self::$display_table;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $display_table (
				rule          VARCHAR (4) NOT NULL,
				adunit_id     INT NOT NULL,
				relation_id   INT NOT NULL,
				relation_type VARCHAR (25) NOT NULL,
				PRIMARY KEY  ( rule, relation_id, adunit_id, relation_type )
			) $charset_collate;";
		dbDelta( $sql );
		// Create statistics table
		$stats_table      = $wpdb->prefix . self::$stats_table;
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $stats_table (
				adunit_id  INT NOT NULL,
				eventType  VARCHAR (25) NOT NULL,
				eventDate  DATE NOT NULL,
				PRIMARY KEY  ( adunit_id, eventType, eventDate )
			) $charset_collate;";
		dbDelta( $sql );
		// Update option to keep track of the version of the database, seperately from the plugin
		update_option( parent::$plugin_name . '_dbv', '1.0.1' );
	}

	public static function remove_ads( $pid ) {
		global $wpdb;
		$table = $wpdb->prefix . self::$display_table;
		$query = sprintf( "DELETE FROM %s WHERE adunit_id = %s", $table, $pid );
		$wpdb->query( $query );
	}

	public static function get_all_ids() {
		global $wpdb;
		$table = $wpdb->prefix . self::$display_table;
		// Merge with defaults
		$args = wp_parse_args( $args, $default_args );
		// Construct Query
		$query = sprintf( "SELECT adunit_id FROM %s", $table );
		$ids = $wpdb->get_results( $query );
		return $ids;
	}

	public static function get_eligable_ads( $args = array() ) {
		global $wpdb;
		$table = $wpdb->prefix . self::$display_table;
		$default_args = array(
			'rule'          => null,
			'relation_id'   => null,
			'relation_type' => null,
		);
		// Merge with defaults
		$args = wp_parse_args( $args, $default_args );
		// Construct Query
		$query = sprintf( "SELECT adunit_id FROM %s WHERE relation_id = %s AND relation_type = '%s' AND rule = '%s'", $table, $args['relation_id'], $args['relation_type'], $args['rule'] );
		$ids = $wpdb->get_results( $query );
		return $ids;
	}

	public static function update_display_rules( $args = array() ) {
		global $wpdb;
		$default_args = array(
			'type'      => null,
			'adunit'    => null,
			'relations' => array(),
			'rule'      => null,
		);
		// Merge with defaults
		$args = wp_parse_args( $args, $default_args );
		// Set prefix
		$table = $wpdb->prefix . self::$display_table;
		// Construct query to delete unneded display rules that no longer exist
		$query = sprintf( "DELETE FROM %s WHERE adunit_id = %s AND rule = '%s' AND relation_type = '%s'", $table, $args['adunit'], $args['rule'], $args['type'] );
		if( !empty( $args['relations'] ) ) {
			$relations = implode( ',', array_map( 'intval', $args['relations'] ) );
			$query .= sprintf( " AND relation_id NOT IN ( %s )", $relations );
		}
		// First we need to remove rules we are no longer using.
		$delete = $wpdb->query( $query );
		// Next we can add rules
		foreach( $args['relations'] as $index => $relation ) {
			$wpdb->insert( $table, array( 'adunit_id' => $args['adunit'], 'relation_id' => $relation, 'relation_type' => $args['type'], 'rule' => $args['rule'] ), array( '%s', '%d' ) );
		}
	}
}