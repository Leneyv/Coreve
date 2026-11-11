<?php
/**
 * Plugin Name: Coreve SMTP Mailer
 * Description: Secure authenticated SMTP delivery for coreve.in
 */

if ( ! defined( "ABSPATH" ) ) {
	exit;
}

add_action( "phpmailer_init", function( $phpmailer ) {
	if ( defined( "COREVE_SMTP_HOST" ) && ! empty( COREVE_SMTP_HOST ) ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = COREVE_SMTP_HOST;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = defined( "COREVE_SMTP_PORT" ) ? COREVE_SMTP_PORT : 465;
		$phpmailer->SMTPSecure = defined( "COREVE_SMTP_SECURE" ) ? COREVE_SMTP_SECURE : "ssl";
		$phpmailer->Username   = defined( "COREVE_SMTP_USER" ) ? COREVE_SMTP_USER : "";
		$phpmailer->Password   = defined( "COREVE_SMTP_PASS" ) ? COREVE_SMTP_PASS : "";
		if ( defined( "COREVE_SMTP_FROM" ) && ! empty( COREVE_SMTP_FROM ) ) {
			$phpmailer->From     = COREVE_SMTP_FROM;
		} elseif ( defined( "COREVE_SMTP_USER" ) && ! empty( COREVE_SMTP_USER ) ) {
			$phpmailer->From     = COREVE_SMTP_USER;
		}
		if ( defined( "COREVE_SMTP_FROM_NAME" ) && ! empty( COREVE_SMTP_FROM_NAME ) ) {
			$phpmailer->FromName = COREVE_SMTP_FROM_NAME;
		}
	}
} );
