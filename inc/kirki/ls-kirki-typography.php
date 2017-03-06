<?php
/**
 * Add Typography
 */
Kirki::add_section( 'typography', array(
	'title'       => __( 'Typography', 'light-store' ),
	'priority'    => 20,
	'panel'          => '',
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
) );

Kirki::add_field( 'ls_theme', array(
	'type'        => 'typography',
	'settings'    => 'base_typography_font_family',
	'label'       => __( 'Typography Control', 'light-store' ),
	'section'     => 'typography',
	'default'     => array(
		'font-family'    => 'Lato',
		'font-size'      => '16',
		'font-weight'    => 'regular',
		'letter-spacing' => 'inherit',
	),
	'priority'    => 10,
	'choices'     => array(
		'font-family'    => true,
		'font-size'      => true,
		'font-weight'      => false,
		'letter-spacing' => true,
	),
	'output'   => array(
		'element'  => 'body',
	),
) );
