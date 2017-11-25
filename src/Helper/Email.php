<?php

/**
 * @class Email
 *
 * Contains functionality related to sending emails
 *
 * @since 1.0
 *
 */

namespace ChronosWP\Helper;

class Email {

	/**
	 * Send an email
	 *
	 * @param string $to
	 * @param string $subject
	 * @param string $content
	 * @param bool $enableHTML
	 *
	 * @access public
	 * @return bool
	 * @static
	 */
	public static function sendEmail( $to, $subject, $content, $enableHTML = true ) {

		if ( $enableHTML ) {
			add_filter( 'wp_mail_content_type', function() { return 'text/html'; } );
		}

		$result = wp_mail( $to, $subject, $content );

		return $result;

	}

}


