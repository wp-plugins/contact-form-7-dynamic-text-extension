<?php

/*
Plugin Name: Contact Form 7 - Dynamic Text Extension
Plugin URI: http://sevenspark.com/wordpress-plugins/contact-form-7-dynamic-text-extension
Description: Provides a dynamic text field that accepts any shortcode to generate the content.  Requires Contact Form 7
Version: 1.0.2
Author: Chris Mavricos, SevenSpark
Author URI: http://sevenspark.com
License: GPL2
*/

/*  Copyright 2010  Chris Mavricos, SevenSpark (email : chris@sevenspark.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 ** A base module for [dynamictext], [dynamictext*]
 **/
function wpcf7_dynamictext_init(){

	if(function_exists('wpcf7_add_shortcode')){

		/* Shortcode handler */		
		wpcf7_add_shortcode( 'dynamictext', 'wpcf7_dynamictext_shortcode_handler', true );
		wpcf7_add_shortcode( 'dynamictext*', 'wpcf7_dynamictext_shortcode_handler', true );
	
	}
	
	add_filter( 'wpcf7_validate_dynamictext', 'wpcf7_dynamictext_validation_filter', 10, 2 );
	add_filter( 'wpcf7_validate_dynamictext*', 'wpcf7_dynamictext_validation_filter', 10, 2 );
	
	add_action( 'admin_init', 'wpcf7_add_tag_generator_dynamictext', 15 );
	
}
add_action( 'plugins_loaded', 'wpcf7_dynamictext_init');

function wpcf7_dynamictext_shortcode_handler( $tag ) {
	global $wpcf7_contact_form;

	if ( ! is_array( $tag ) )
		return '';

	$type = $tag['type'];
	$name = $tag['name'];
	$options = (array) $tag['options'];
	$values = (array) $tag['values'];

	if ( empty( $name ) )
		return '';

	$atts = '';
	$id_att = '';
	$class_att = '';
	$size_att = '';
	$maxlength_att = '';
	$tabindex_att = '';

	$class_att .= ' wpcf7-text';

	if ( 'dynamictext*' == $type )
		$class_att .= ' wpcf7-validates-as-required';

	foreach ( $options as $option ) {
		if ( preg_match( '%^id:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
			$id_att = $matches[1];

		} elseif ( preg_match( '%^class:([-0-9a-zA-Z_]+)$%', $option, $matches ) ) {
			$class_att .= ' ' . $matches[1];

		} elseif ( preg_match( '%^([0-9]*)[/x]([0-9]*)$%', $option, $matches ) ) {
			$size_att = (int) $matches[1];
			$maxlength_att = (int) $matches[2];

		} elseif ( preg_match( '%^tabindex:(\d+)$%', $option, $matches ) ) {
			$tabindex_att = (int) $matches[1];

		}
	}

	if ( $id_att )
		$atts .= ' id="' . trim( $id_att ) . '"';

	if ( $class_att )
		$atts .= ' class="' . trim( $class_att ) . '"';

	if ( $size_att )
		$atts .= ' size="' . $size_att . '"';
	else
		$atts .= ' size="40"'; // default size

	if ( $maxlength_att )
		$atts .= ' maxlength="' . $maxlength_att . '"';

	if ( '' !== $tabindex_att )
		$atts .= sprintf( ' tabindex="%d"', $tabindex_att );

	// Value
	if ( is_a( $wpcf7_contact_form, 'WPCF7_ContactForm' ) && $wpcf7_contact_form->is_posted() ) {
		if ( isset( $_POST['_wpcf7_mail_sent'] ) && $_POST['_wpcf7_mail_sent']['ok'] )
			$value = '';
		else
			$value = stripslashes_deep( $_POST[$name] );
	} else {
		$value = isset( $values[0] ) ? $values[0] : '';
	}
	
	$value = do_shortcode('['.$value.']');
	//echo '<pre>'; print_r($options);echo '</pre>';
	$readonly = '';
	if(in_array('uneditable', $options)){
		$readonly = 'readonly="readonly"';
	}

	$html = '<input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '"' . $atts . ' '. $readonly.' />';

	$validation_error = '';
	if ( is_a( $wpcf7_contact_form, 'WPCF7_ContactForm' ) )
		$validation_error = $wpcf7_contact_form->validation_error( $name );

	$html = '<span class="wpcf7-form-control-wrap ' . $name . '">' . $html . $validation_error . '</span>';

	return $html;
}


/* Validation filter */

function wpcf7_dynamictext_validation_filter( $result, $tag ) {
	global $wpcf7_contact_form;

	$type = $tag['type'];
	$name = $tag['name'];

	$_POST[$name] = trim( strtr( (string) $_POST[$name], "\n", " " ) );

	if ( 'dynamictext*' == $type ) {
		if ( '' == $_POST[$name] ) {
			$result['valid'] = false;
			$result['reason'][$name] = $wpcf7_contact_form->message( 'invalid_required' );
		}
	}

	return $result;
}


/* Tag generator */

function wpcf7_add_tag_generator_dynamictext() {
	if(function_exists('wpcf7_add_tag_generator')){
		wpcf7_add_tag_generator( 'dynamictext', __( 'Dynamic Text field', 'wpcf7' ),
			'wpcf7-tg-pane-dynamictext', 'wpcf7_tg_pane_dynamictext_' );
	}
}

function wpcf7_tg_pane_dynamictext_( &$contact_form ) {
	wpcf7_tg_pane_dynamictext( 'dynamictext' );
}

function wpcf7_tg_pane_dynamictext( $type = 'dynamictext' ) {
?>
<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
<form action="">
<table>
<tr><td><input type="checkbox" name="required" />&nbsp;<?php echo esc_html( __( 'Required field?', 'wpcf7' ) ); ?></td></tr>
<tr><td><?php echo esc_html( __( 'Name', 'wpcf7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
</table>

<table>
<tr>
<td><code>id</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="id" class="idvalue oneline option" /></td>

<td><code>class</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="class" class="classvalue oneline option" /></td>
</tr>

<tr>
<td><code>size</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="size" class="numeric oneline option" /></td>

<td><code>maxlength</code> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br />
<input type="text" name="maxlength" class="numeric oneline option" /></td>
</tr>

<tr>
<td>
<input type="checkbox" name="uneditable" class="option" />&nbsp;<?php echo esc_html( __( "Make this field Uneditable", 'wpcf7' ) ); ?><br />
</td>

<td><?php echo esc_html( __( 'Dynamic value', 'wpcf7' ) ); ?> (<?php echo esc_html( __( 'optional', 'wpcf7' ) ); ?>)<br /><input type="text" name="values" class="oneline" />
<?php echo esc_html( __( 'You can enter any short code.  Just leave out the square brackets ([]) and only use single quotes (\' not ")', 'wpcf7' )); ?>
</td>
</tr>
</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'wpcf7' ) ); ?><br /><input type="text" name="<?php echo $type; ?>" class="tag" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'wpcf7' ) ); ?><br /><span class="arrow">&#11015;</span>&nbsp;<input type="text" class="mail-tag" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}



/*
 * Used like this:
 * 
 * CF7_GET val='value'
 * 
 * No [] and single quotes ' rather than double "
 * 
 */
function cf7_get($atts){
	extract(shortcode_atts(array(
		'key' => 0,
	), $atts));
	$auction = urldecode($_GET[$key]);
	return $auction;
}
add_shortcode('CF7_GET', 'cf7_get');

/* See http://codex.wordpress.org/Function_Reference/get_bloginfo */
function cf7_bloginfo($atts){
	extract(shortcode_atts(array(
		'show' => 'name'
	), $atts));
	
	return get_bloginfo($show);
}
add_shortcode('CF7_bloginfo', 'cf7_bloginfo');

?>
