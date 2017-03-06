<?php
/**
 * LS Class
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LS' ) ) :

	/**
	 * The main LS class
	 */
	class LS {

		private static $structured_data;

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_head', 'lsHooks' );
			add_action( 'after_setup_theme', [ $this, 'setup' ] );
			add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
			add_action( 'widgets_init', [ $this, 'widgets_init' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 10 );
			add_action( 'wp_enqueue_scripts', [$this, 'after_plugins_scripts'], 30 ); // After WooCommerce.
			add_action( 'wp_footer', [ $this, 'get_structured_data' ] );

			add_filter( 'body_class', [ $this, 'body_classes' ] );
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Theme lang
			 */

			// Loads child theme translate
			load_theme_textdomain( 'light-store', get_stylesheet_directory() . '/languages' );
			// Loads theme translate
			load_theme_textdomain( 'light-store', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );


			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * Enable support for site logo
			 */
			add_theme_support( 'custom-logo', [
				'height'      => 100,
				'width'       => 450,
				'flex-width' => true,
			] );

			/**
			 * This theme uses wp_nav_menu() in two locations.
			 */
			register_nav_menus( [
				'primary'   => __( 'Primary Menu', 'light-store' ),
				'secondary' => __( 'Secondary Menu', 'light-store' )
			] );

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', [
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			] );

			/**
			 * Setup the WordPress core custom background feature.
			 */
			add_theme_support( 'custom-background', apply_filters( 'ls_custom_background_args', [
				'default-color' => apply_filters( 'ls_default_background_color', 'ffffff' ),
				'default-image' => '',
			] ) );

			add_theme_support( 'site-logo', [ 'size' => 'full' ] );

			/*
			 * Declare WooCommerce support.
			 */
			add_theme_support( 'woocommerce' );

			/*
			 * Declare support for title theme feature.
			 */
			add_theme_support( 'title-tag' );

			/*
			 * Declare support for selective refreshing of widgets.
			 */
			add_theme_support( 'customize-selective-refresh-widgets' );
		}

		/**
		 * Set the $content_width global.
		 */
		function content_width(){
			$GLOBALS['content_width'] = apply_filters( 'business_era_content_width', 1200 );
		}

		/**
		 * Register widget area.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {
			$sidebar_args['sidebar'] = [
				'name'        => __( 'Sidebar', 'light-store' ),
				'id'          => 'sidebar-1',
				'description' => ''
			];

			$sidebar_args['header'] = [
				'name'        => __( 'Below Header', 'light-store' ),
				'id'          => 'header-1',
				'description' => __( 'Widgets added to this region will appear beneath the header and above the main content.', 'light-store' ),
			];

			$footer_widget_regions = apply_filters( 'ls_footer_widget_regions', 4 );

			for ( $i = 1; $i <= intval( $footer_widget_regions ); $i ++ ) {
				$footer = sprintf( 'footer_%d', $i );

				$sidebar_args[ $footer ] = [
					'name'        => sprintf( __( 'Footer %d', 'light-store' ), $i ),
					'id'          => sprintf( 'footer-%d', $i ),
					'description' => sprintf( __( 'Widgetized Footer Region %d.', 'light-store' ), $i )
				];
			}

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = [
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<span class="gamma widget-title">',
					'after_title'   => '</span>'
				];

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 *
				 * 'ls_header_widget_tags'
				 * 'ls_sidebar_widget_tags'
				 *
				 * ls_footer_1_widget_tags
				 * ls_footer_2_widget_tags
				 * ls_footer_3_widget_tags
				 * ls_footer_4_widget_tags
				 */
				$filter_hook = sprintf( 'ls_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 */
		public function scripts() {
			$theme_data = wp_get_theme();
			$theme_version = $theme_data->Version;
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			/**
			 * Scripts
			 */
			wp_enqueue_script( 'bootstrap',  get_template_directory_uri() . '/assets/js/bootstrap' . $suffix . '.js', [], '3.3.7', true );
			wp_enqueue_script( 'bootstrap-confirmation', get_template_directory_uri() . '/assets/js/bootstrap-confirmation' . $suffix . '.js', [ 'jquery', 'bootstrap' ], '0.4.5', true );
			wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/js/isotope.pkgd' . $suffix . '.js', [], '3.0.2', true );
			wp_enqueue_script( 'prototype', get_template_directory_uri() . '/assets/js/prototype' . $suffix . '.js', [ 'jquery'], '1.5.0', true );


			wp_enqueue_script( 'ls-validation', get_template_directory_uri() . '/assets/js/validation' . $suffix . '.js', [ 'prototype'], $theme_version, true );

			if ( is_front_page() ) {
				wp_enqueue_script( 'ls-homepage', get_template_directory_uri() . '/assets/js/frontend/homepage' . $suffix . '.js', [ 'ls-validation' ], $theme_version, true );
			}

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			if ( ! is_user_logged_in() ) {
				wp_enqueue_script( 'ls-ajax-login', get_template_directory_uri() . '/assets/js/frontend/ajax-login' . $suffix . '.js', [ 'ls-validation' ], $theme_version, true );
			}

			wp_enqueue_script( 'ls-main-script', get_template_directory_uri() . '/assets/js/main' . $suffix . '.js', [ 'isotope'], $theme_version, true );

		}

		/**
		 * Enqueue stylesheet for customization plugins.
		 *
		 */
		public function after_plugins_scripts() {
			$theme_data = wp_get_theme();
			$theme_version = $theme_data->Version;
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'ls-main-style', get_template_directory_uri() . '/assets/css/style.css', [], $theme_version );
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function body_classes( $classes ) {
			// Adds a class of group-blog to blogs with more than 1 published author.
			if ( is_multi_author() ) {
				$classes[] = 'group-blog';
			}

			if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
				$classes[] = 'no-wc-breadcrumb';
			}

			// If our main sidebar doesn't contain widgets, adjust the layout to be full-width.
			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$classes[] = 'ls-content';
			}

			return $classes;
		}

		/**
		 * Sets `self::structured_data`.
		 *
		 * @param array $json
		 */
		public static function set_structured_data( $json ) {
			if ( ! is_array( $json ) ) {
				return;
			}

			self::$structured_data[] = $json;
		}

		/**
		 * Outputs structured data.
		 *
		 * Hooked into `wp_footer` action hook.
		 */
		public function get_structured_data() {
			if ( ! self::$structured_data ) {
				return;
			}

			$structured_data['@context'] = 'http://schema.org/';

			if ( count( self::$structured_data ) > 1 ) {
				$structured_data['@graph'] = self::$structured_data;
			} else {
				$structured_data = $structured_data + self::$structured_data[0];
			}

			echo '<script type="application/ld+json">' . wp_json_encode( $this->sanitize_structured_data( $structured_data ) ) . '</script>';
		}

		/**
		 * Sanitizes structured data.
		 *
		 * @param  array $data
		 *
		 * @return array
		 */
		public function sanitize_structured_data( $data ) {
			$sanitized = [ ];

			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_value = $this->sanitize_structured_data( $value );
				} else {
					$sanitized_value = sanitize_text_field( $value );
				}

				$sanitized[ sanitize_text_field( $key ) ] = $sanitized_value;
			}

			return $sanitized;
		}
	}
endif;