/**
 * This script adds the jquery effects to the Altitude Pro Theme.
 *
 * @package Altitude\JS
 * @author StudioPress
 * @license GPL-2.0+
 */

jQuery(function( $ ){

	if( $( document ).scrollTop() > 0 ){
		$( '.site-header' ).addClass( 'dark' );
	}

	// Add opacity class to site header.
	$( document ).on('scroll', function(){

		if ( $( document ).scrollTop() > 0 ){
			$( '.site-header' ).addClass( 'dark' );

		} else {
			$( '.site-header' ).removeClass( 'dark' );
		}

	});

	$('.left-person').mouseover(function(){
		$('.left-person-description').css('opacity', 1);
	});
	$('.right-person').mouseover(function(){
		$('.right-person-description').css('opacity', 1);
	});
	$('.left-person').mouseout(function(){
		$('.left-person-description').css('opacity', 0);
	});
	$('.right-person').mouseout(function(){
		$('.right-person-description').css('opacity', 0);
	});
	$('.single-post .entry-content p img').parent('p').css('column-span', 'all');

});