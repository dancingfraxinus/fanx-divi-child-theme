<?php
//GROWTIX INTEGRATION - GUEST PAGES 

//key=5a346da2-a767-437e-b316-3f2ef581be74

$request = wp_remote_get( 'https://api-melupufoagt.stackpathdns.com/api/people?key=5a346da2-a767-437e-b316-3f2ef581be74' );

if( is_wp_error( $request ) ) {
	return false; // Bail early
}

$body = wp_remote_retrieve_body( $request );

$data = json_decode( $body );

?> 
