<?php

require_once 'settings.php';

if (!empty($_POST['audiof'])) {
	try {
		$audio_file = trim($_POST['audiof']);
		if (filter_var($audio_file, FILTER_VALIDATE_URL)) {

/* START WORDPRESS VERSION 
			$attr = array(
				'src'		=> $audio_file,
				'loop'		=> $autoplay,
				'autoplay'	=> $loop,
				'preload'	=> 'none'
			);
			echo wp_audio_shortcode($attr);
			exit;
END WORDPRESS VERSION */

			$attr = array(
				'src'		=> $audio_file,
				'preload'	=> 'none'
			);

			echo get_audio_player_html($attr);
		} else {
			echo '[Error] Invalid audio file URL';		
		}
	} catch(Exception $err) {
		echo '[Error] ' . $err->getMessage();
	}	
}

function get_audio_player_html($attr) {
	
	global $autoplay, $loop;
	
	$audio = null;
	$default_types = array( 'mp3', 'ogg', 'flac', 'm4a', 'wav' );
	$defaults_atts = array(
		'src'      => '',
		'preload'  => 'none',
		'class'    => 'wp-audio-shortcode',
		'style'    => 'width: 100%;',
	);

	foreach ( $default_types as $type ) {
		$defaults_atts[ $type ] = '';
	}

	$atts = shortcode_atts( $defaults_atts, $attr);
	array_unshift( $default_types, 'src' );
	$html_atts = array(
		'class'    => $atts['class'],
		'id'       => 'audio-1-1',
		'preload'  => $atts['preload'],
		'style'    => $atts['style'],
	);

	$attr_strings = array();

	foreach ( $html_atts as $k => $v ) {
		$attr_strings[] = $k . '="' . $v . '"';
	}

	if ($autoplay)
		$attr_strings[] = 'autoplay';
	
	if ($loop)
		$attr_strings[] = 'loop';

	$html = "<!--[if lt IE 9]><script>document.createElement('audio');</script><![endif]-->\n";
	$html .= sprintf( '<audio %s controls="controls">', implode( ' ', $attr_strings ) );
	$html .= sprintf( '<source type="%s" src="%s" />', 'audio/mp3', $atts['src'] );
	$html .= sprintf( '<a href="%1$s">%1$s</a>', $atts['src']);
	$html .= '</audio>';
	return $html;
	
}

function shortcode_atts( $pairs, $atts) {
	
	$atts = (array) $atts;
	$out  = array();
	
	foreach ( $pairs as $name => $default ) {
		if ( array_key_exists( $name, $atts ) ) {
			$out[ $name ] = $atts[ $name ];
		} else {
			$out[ $name ] = $default;
		}
	}

	return $out;
	
}
