<?php

/*
 * Check to make sure this file is being called on deactivation.
 * Directly calling this file will fail.
/**/
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( __( "You're doin' it wrong", 'puppykibble' ) );

/*
 * Delete the Puppy Kibble Transient
/**/
delete_transient( '_puppykibble' );
