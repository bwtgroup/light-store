<?php
/**
 * Add Layout Customizer
 */
Kirki::add_section( 'layout', array(
	'title'       => __( 'Layout', 'light-store' ),
	'priority'    => 20,
	'panel'          => '',
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
) );

Kirki::add_field( 'ls_theme', array(
	'type'        => 'radio-image',
	'settings'    => 'content-layout',
	'label'       => esc_html__( 'Content Layout', 'light-store' ),
	'section'     => 'layout',
	'default'     => 'container',
	'priority'    => 10,
	'choices'     => array(
		'container'   =>  trailingslashit( get_template_directory_uri() ) . 'assets/img/layout/3cm.png',
		'fluid'   =>  trailingslashit( get_template_directory_uri() ) . 'assets/img/layout/1c.png',
	),
) );