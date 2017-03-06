<?php
/**
 * ls template functions.
 *
 */

if ( ! function_exists( 'ls_display_comments' ) ) {
	/**
	 * Theme display comments
	 *
	 */
	function ls_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || '0' != get_comments_number() ) :
			comments_template();
		endif;
	}
}

if ( ! function_exists( 'ls_comment' ) ) {
	/**
	 * ls comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args    the comment args.
	 * @param int   $depth   the comment depth.
	 */
	function ls_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-body">
		<div class="comment-meta commentmetadata">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 128 ); ?>
				<?php printf( wp_kses_post( '<cite class="fn">%s</cite>', 'light-store' ), get_comment_author_link() ); ?>
			</div>
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'light-store' ); ?></em>
				<br/>
			<?php endif; ?>

			<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date">
				<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
			</a>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-content">
	<?php endif; ?>
		<div class="comment-text">
			<?php comment_text(); ?>
		</div>
		<div class="reply">
			<?php comment_reply_link( array_merge( $args, [
				'add_below' => $add_below,
				'depth'     => $depth,
				'max_depth' => $args['max_depth']
			] ) ); ?>
			<?php edit_comment_link( __( 'Edit', 'light-store' ), '  ', '' ); ?>
		</div>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
			</div>
		<?php endif; ?>
		<?php
	}
}

if ( ! function_exists( 'ls_footer_widgets' ) ) {
	/**
	 * Display the footer widget regions
	 *
	 * @since  1.0.0
	 * @return  void
	 */
	function ls_footer_widgets() {
		if ( is_active_sidebar( 'footer-4' ) ) {
			$widget_columns = apply_filters( 'ls_footer_widget_regions', 4 );
		} elseif ( is_active_sidebar( 'footer-3' ) ) {
			$widget_columns = apply_filters( 'ls_footer_widget_regions', 3 );
		} elseif ( is_active_sidebar( 'footer-2' ) ) {
			$widget_columns = apply_filters( 'ls_footer_widget_regions', 2 );
		} elseif ( is_active_sidebar( 'footer-1' ) ) {
			$widget_columns = apply_filters( 'ls_footer_widget_regions', 1 );
		} else {
			$widget_columns = apply_filters( 'ls_footer_widget_regions', 0 );
		}

		if ( $widget_columns > 0 ) { ?>

			<div class="footer-widgets col-<?php echo intval( $widget_columns ); ?> fix">

				<?php
				$i = 0;
				while ( $i < $widget_columns ) : $i ++;
					if ( is_active_sidebar( 'footer-' . $i ) ) : ?>

						<div class="block footer-widget-<?php echo intval( $i ); ?>">
							<?php dynamic_sidebar( 'footer-' . intval( $i ) ); ?>
						</div>

					<?php endif;
				endwhile; ?>

			</div><!-- /.footer-widgets  -->

		<?php } else {

			ls_credit();
		}
	}
}

if ( ! function_exists( 'ls_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @return void
	 */
	function ls_credit() {
		?>
		<div class="site-info">
			<?php echo  apply_filters( 'ls_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ); ?>
			<?php if ( apply_filters( 'ls_credit_link', true ) ) { ?>
				<br/> <?php printf( __( 'Proudly powered by %s', 'light-store' ), '<a href="' . esc_url( "https://wordpress.org/" ) . '">WordPress</a>' ); ?>
			<?php } ?>
		</div><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'ls_header_widget_region' ) ) {
	/**
	 * Display header widget region
	 *
	 */
	function ls_header_widget_region() {
		if ( is_active_sidebar( 'header-1' ) ) {
			?>
			<div class="header-widget-region" role="complementary">
				<div class="col-full">
					<?php dynamic_sidebar( 'header-1' ); ?>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'ls_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @return void
	 */
	function ls_site_branding() {
		?>
		<div class="site-branding">
			<?php ls_site_title_or_logo(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @param bool $echo Echo the string or return it.
	 *
	 * @return string
	 */
	function ls_site_title_or_logo( $echo = true ) {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
			$html = is_home() ? '<h1 class="logo">' . $logo . '</h1>' : $logo;
		} else {

			$html = '<h1 class="site-title-logo"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></h1>';
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}
}

if ( ! function_exists( 'ls_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 *
	 * @return void
	 */
	function ls_primary_navigation() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return false;
		}
		if ( class_exists( 'Mega_Menu' ) ) {

			wp_nav_menu( [
				'theme_location' => 'primary',
			] );
		} else {
			?>
			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'light-store' ); ?>">
				<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false" title="<?php echo esc_attr( apply_filters( 'ls_menu_toggle_text', __( 'Menu', 'light-store' ) ) ); ?>"></button>
				<?php
				wp_nav_menu( [
					'theme_location'  => 'primary',
					'container_class' => 'primary-navigation',
				] );

				wp_nav_menu( [
					'theme_location'  => 'handheld',
					'container_class' => 'handheld-navigation',
				] );
				?>
			</nav><!-- #site-navigation -->
			<?php
		}
	}
}

if ( ! function_exists( 'ls_right_navigation' ) ) {
	/**
	 * Display Secondary Navigation
	 *
	 * @return void
	 */
	function ls_right_navigation() {

		echo '<ul class="navbar-block-right">';

		do_action( 'ls_right_navigation' );

		echo '</ul>';
	}
}

if ( ! function_exists( 'ls_navigation_cart' ) ) {
	/**
	 * Cart in secondary navigation
	 *
	 */
	function ls_navigation_cart() {

		if ( is_woocommerce_activated() ) {

			$mini_cart_layout_type = ls_get_theme_mod( 'mini-cart-layout');

			$cart_class          = ( is_cart() || is_checkout() ) ? 'is-cart-page' : '';
			$cart_contents_count = WC()->cart->get_cart_contents_count();

			switch ( $mini_cart_layout_type ) {
				case 1:
					$cart_layout_class = 'cart-popup';
					ob_start();

					the_widget( "WC_Widget_Cart" );

					$cart_content = ob_get_clean();
					break;
				case 2:
					add_action( 'ls_before_content', 'ls_mini_cart', 10 );
					$cart_layout_class = 'cart-sidebar';
					$cart_content      = '';
					break;
				default:
					$cart_layout_class = '';
					$cart_content      = '';
					break;
			}
			?>
			<li class="cart">


				<div class="header-cart <?php echo esc_attr( $cart_layout_class ); ?>">
					<a class="cart-link <?php echo esc_attr( $cart_class ); ?>" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'light-store' ); ?>">
						<span <?php ls_get_basket_icon_classes(); ?>></span>
						(<span class="total-count"><?php echo $cart_contents_count; ?></span>)
					</a>
					<?php echo $cart_content; ?>
				</div>
			</li>
			<?php
		}
	}
}
if ( ! function_exists( 'ls_mini_cart' ) ) {
	/**
	 * Mini cart area
	 *
	 */
	function ls_mini_cart() {
		$cart_contents_count = WC()->cart->get_cart_contents_count();
		?>
		<div class="mini-cart">
			<div class="mini-cart-bar">
				<a class="mini-cart-close" href="#">Close</a>
				<div class="mini-cart-title"><?php esc_attr_e( 'Cart', 'light-store' ); ?>
					<span>(<span class="total-count"><?php echo $cart_contents_count; ?></span>)</span></div>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</div>
			<div class="mini-cart-background"></div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ls_navigation_search' ) ) {
	/**
	 * Search in secondary navigation
	 *
	 */
	function ls_navigation_search() {
		if ( is_woocommerce_activated () ) {
			add_action( 'ls_before_content', 'ls_search_area', 10 );

			?>
			<li class="search-box-wrapper">
				<div id="site-search"></div>
			</li>
			<?php
		}

	}
}

if ( ! function_exists( 'ls_wishlist_info' ) ) {
	/**
	 * Wishlist info widget in secondary navigation
	 *
	 */
	function ls_wishlist_info() {

		$count = YITH_WCWL()->count_products();
		$link  = YITH_WCWL()->get_wishlist_url();
		?>
		<li class="wishlist-info">
			<a href="<?php echo $link; ?>" class="wishlist-icon">
				(<span class="count"><?php echo $count; ?></span>)
			</a>
		</li>
		<?php
	}
}

if ( ! function_exists( 'ls_search_area' ) ) {
	/**
	 * Search area
	 *
	 */
	function ls_search_area() {
		?>

		<div id="search-area">
			<div class="search-area-bar">
				<div id="search-area-close"></div>
				<form role="search" method="get" class="woocommerce-product-search" action="/">
					<input type="search"
					       id="searchinput"
					       class="search-field"
					       placeholder="<?php esc_attr_e( 'Search Products...', 'light-store' ); ?>"
					       value=""
					       name="s"
					       autocomplete="OfF"
					       autocorrect="off"
					       autocapitalize="off">
					<input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'light-store' ); ?>">
					<input type="hidden" name="post_type" value="product">
				</form>
			</div>
		</div>

		<?php
	}
}

if ( ! function_exists( 'ls_navigation_account' ) ) {
	/**
	 * Account in secondary navigation
	 *
	 */
	function ls_navigation_account() {
		if ( is_woocommerce_activated () ) {
			echo '<li class="account">';

			if ( is_user_logged_in() ) {
				global $current_user;
				wp_get_current_user(); ?>

				<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="account-name" title="<?php _e( 'My Account', 'light-store' ); ?>"><span class="icon icon-login"></span> Hi, <?php echo $current_user->user_login; ?>
				</a>
				(<a class="orange" href="<?php echo  esc_url( wp_logout_url( home_url() ) ); ?>" title="<?php _e( 'Logout', 'light-store' ); ?>"><?php _e( 'Logout', 'light-store' ); ?></a>)

			<?php } else { ?>

				<a data-toggle="modal" data-target="#login-modal" title="<?php _e( 'Login / Register', 'light-store' ); ?>">
					<span class="icon icon-login"></span>
					<span class="">Log In</span>
				</a>

				<?php
				add_action( 'ls_after_footer', 'ls_modal_login', 10 );
			}

			echo '</li>';
		}

	}
}

if ( ! function_exists( 'ls_modal_login' ) ) {
	/**
	 * Modal window Login User
	 *
	 */
	function ls_modal_login() {
		?>
		<div class="modal fade  in" id="login-modal" tabindex="-1" role="dialog" aria-hidden="true">

			<div class="modal-dialog modal-md" style="max-width: 500px; ">
				<div class="modal-content">
					<div class="modal-body">
						<?php echo ls_login_form() ?>
					</div>
					<a href="#close" data-dismiss="modal" class="modal-close" title="Close">x</a>
				</div>
			</div>
		</div>


		<?php
	}
}

if ( ! function_exists( 'ls_login_form' ) ) {
	/**
	 * Light Store Login Form
	 *
	 */
	function ls_login_form() {
		ob_start();
		if ( is_user_logged_in() ) {
			?>
			<p>You are already Logged
				<a class="orange" href="<?php echo  esc_url(wp_logout_url( home_url() )); ?>"><?php _e( 'Logout', 'light-store' ); ?></a>
			</p>
			<?php
		} else {
			?>
			<form method="post" class="post-form" id="user-login-modal">

				<h2 class="text-center"><?php _e( 'Login', 'light-store' ); ?></h2>

				<?php if(get_option('users_can_register') && get_page_by_path( 'registration' )){ ?>
					<p class="text-center"><?php _e( 'Don&rsquo;t have an account?', 'light-store' ); ?>
						<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>"><?php _e( 'Register', 'light-store' ); ?></a>
					</p>
				<?php } ?>

				<div class="message"></div>

				<div class="form-group">
					<label for="username"><?php _e( 'Login or email address', 'light-store' ); ?>
						<span class="required">*</span></label>
					<input id="username" class="form-control required" type="text" name="username">
				</div>
				<p class="lost_password text-right">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'light-store' ); ?></a>
				</p>
				<div class="form-group">
					<label for="password"><?php _e( 'Password', 'light-store' ); ?>
						<span class="required">*</span></label>
					<span class="show-pass eye fa fa-eye"></span>
					<span class="hide-pass eye fa fa-eye-slash"></span>
					<input id="password" class="form-control required" type="password" name="password">
				</div>

				<div class="form-group text-center">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="btn btn-primary text-uppercase submit"><?php esc_attr_e( 'Login', 'light-store' ); ?></button>
				</div>
			</form>

			<?php
		}

		return ob_get_clean();
	}
}
if ( ! function_exists( 'ls_registration_form' ) ) {
	/**
	 * Light Store Registration Form
	 *
	 */
	function ls_registration_form() {
		ob_start();
		if ( is_user_logged_in() ) {
			?>
			<p>You are already Logged
				<a class="orange" href="<?php echo  esc_url(wp_logout_url( home_url() ) ); ?>"><?php _e( 'Logout', 'light-store' ); ?></a>
			</p>
			<?php
		} else {
			?>

			<h2 class="text-center"><?php _e( 'Register', 'light-store' ); ?></h2>

			<form method="post" class="register primary-form" id="user-registration">

				<!--message wrapper-->
				<div class="message woocommerce"></div>

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--first form-row form-row-first">
					<label for="reg_sr_firstname"><?php _e( 'First Name', 'light-store' ); ?></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="firstname" id="reg_sr_firstname" value="<?php if ( ! empty( $_POST['firstname'] ) ) {
						echo esc_attr( $_POST['firstname'] );
					} ?>"/>
				</p>

				<p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-last">
					<label for="reg_sr_lastname"><?php _e( 'Last Name', 'light-store' ); ?></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="lastname" id="reg_sr_lastname" value="<?php if ( ! empty( $_POST['lastname'] ) ) {
						echo esc_attr( $_POST['lastname'] );
					} ?>"/>
				</p>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_email"><?php _e( 'Email address', 'light-store' ); ?>
						<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text required validate-email" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) {
						echo esc_attr( $_POST['email'] );
					} ?>"/>
				</p>


				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide form-group">
					<label for="reg_password"><?php _e( 'Password', 'light-store' ); ?>
						<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text required" name="password" id="reg_password"/>
					<span class="show-pass eye fa fa-eye"></span>
					<span class="hide-pass eye fa fa-eye-slash"></span>
				</p>


				<?php do_action( 'woocommerce_register_form' ); ?>
				<?php do_action( 'register_form' ); ?>

				<p class="woocomerce-FormRow form-row text-center">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<input type="submit" class="woocommerce-Button button submit" name="register" value="<?php esc_attr_e( 'Register', 'light-store' ); ?>"/>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>

			<?php
		}
		?>
		<?php return ob_get_clean();
	}
}

if ( ! function_exists( 'ls_header_shim' ) ) {
	/**
	 * Wrap for fixed header
	 *
	 */
	function ls_header_shim() {
		?>

		<div id="header-shim"></div>

		<?php
	}
}

if ( ! function_exists( 'ls_skip_links' ) ) {
	/**
	 * Skip links
	 *
	 * @return void
	 */
	function ls_skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'light-store' ); ?></a>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'light-store' ); ?></a>
		<?php
	}
}

if ( ! function_exists( 'ls_page_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 */
	function ls_page_header() {
		?>
		<header class="entry-header">
			<?php
			ls_post_thumbnail( 'full' );
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'ls_page_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 */
	function ls_page_content() {
		?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( [
				'before' => '<div class="page-links">' . __( 'Pages:', 'light-store' ),
				'after'  => '</div>',
			] );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'ls_post_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function ls_post_header() {
		?>
		<header class="entry-header">
			<?php
			if ( is_single() ) {
				ls_posted_on();
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				if ( 'post' == get_post_type() ) {
					ls_posted_on();
				}

				the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			}
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'ls_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function ls_post_content() {
		?>
		<div class="entry-content">
			<?php

			/**
			 * Functions hooked in to ls_post_content_before action.
			 *
			 * @hooked ls_post_thumbnail - 10
			 */
			do_action( 'ls_post_content_before' );

			the_content( sprintf( __( 'Continue reading %s', 'light-store' ), '<span class="screen-reader-text">' . get_the_title() . '</span>' ) );

			do_action( 'ls_post_content_after' );

			wp_link_pages( [
				'before' => '<div class="page-links">' . __( 'Pages:', 'light-store' ),
				'after'  => '</div>',
			] );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'ls_post_meta' ) ) {
	/**
	 * Display the post meta
	 *
	 */
	function ls_post_meta() {
		?>
		<aside class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search.

				?>
				<div class="author">
					<?php
					echo get_avatar( get_the_author_meta( 'ID' ), 128 );
					echo '<div class="label">' . esc_attr( __( 'Written by', 'light-store' ) ) . '</div>';
					the_author_posts_link();
					?>
				</div>
				<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'light-store' ) );

				if ( $categories_list ) : ?>
					<div class="cat-links">
						<?php
						echo '<div class="label">' . esc_attr( __( 'Posted in', 'light-store' ) ) . '</div>';
						echo wp_kses_post( $categories_list );
						?>
					</div>
				<?php endif; // End if categories.
				?>

				<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'light-store' ) );

				if ( $tags_list ) : ?>
					<div class="tags-links">
						<?php
						echo '<div class="label">' . esc_attr( __( 'Tagged', 'light-store' ) ) . '</div>';
						echo wp_kses_post( $tags_list );
						?>
					</div>
				<?php endif; // End if $tags_list.
				?>

			<?php endif; // End if 'post' == get_post_type(). ?>

			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<div class="comments-link">
					<?php echo '<div class="label">' . esc_attr( __( 'Comments', 'light-store' ) ) . '</div>'; ?>
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'light-store' ), __( '1 Comment', 'light-store' ), __( '% Comments', 'light-store' ) ); ?></span>
				</div>
			<?php endif; ?>
		</aside>
		<?php
	}
}

if ( ! function_exists( 'ls_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function ls_paging_nav() {
		global $wp_query;

		$args = [
			'type'      => 'list',
			'next_text' => _x( 'Next', 'Next post', 'light-store' ),
			'prev_text' => _x( 'Previous', 'Previous post', 'light-store' ),
		];

		the_posts_pagination( $args );
	}
}

if ( ! function_exists( 'ls_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function ls_post_nav() {
		$args = [
			'next_text' => '%title',
			'prev_text' => '%title',
		];
		the_post_navigation( $args );
	}
}

if ( ! function_exists( 'ls_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function ls_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string, esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ), esc_attr( get_the_modified_date( 'c' ) ), esc_html( get_the_modified_date() ) );

		$posted_on = sprintf( _x( 'Posted on %s', 'post date', 'light-store' ), '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );

		echo wp_kses( apply_filters( 'ls_single_post_posted_on_html', '<span class="posted-on">' . $posted_on . '</span>', $posted_on ), [
			'span' => [
				'class' => [ ],
			],
			'a'    => [
				'href'  => [ ],
				'title' => [ ],
				'rel'   => [ ],
			],
			'time' => [
				'datetime' => [ ],
				'class'    => [ ],
			],
		] );
	}
}

if ( ! function_exists( 'ls_product_categories' ) ) {
	/**
	 * Display Product Categories
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_product_categories( $args ) {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_product_categories_args', [
				'limit'            => 3,
				'columns'          => 3,
				'child_categories' => 0,
				'orderby'          => 'name',
				'title'            => __( 'Shop by Category', 'light-store' ),
			] );

			echo '<section class="ls-product-section ls-product-categories" aria-label="Product Categories">';

			do_action( 'ls_homepage_before_product_categories' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_after_product_categories_title' );

			echo ls_do_shortcode( 'product_categories', [
				'number'  => intval( $args['limit'] ),
				'columns' => intval( $args['columns'] ),
				'orderby' => esc_attr( $args['orderby'] ),
				'parent'  => esc_attr( $args['child_categories'] ),
			] );

			do_action( 'ls_homepage_after_product_categories' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_recent_products' ) ) {
	/**
	 * Display Recent Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_recent_products( $args ) {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_recent_products_args', [
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'New In', 'light-store' ),
			] );

			echo '<section class="ls-product-section ls-recent-products" aria-label="Recent Products">';

			do_action( 'ls_homepage_before_recent_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_after_recent_products_title' );

			echo ls_do_shortcode( 'recent_products', [
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			] );

			do_action( 'ls_homepage_after_recent_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_featured_products' ) ) {
	/**
	 * Display Featured Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_featured_products( $args ) {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_featured_products_args', [
				'limit'   => 4,
				'columns' => 4,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => __( 'We Recommend', 'light-store' ),
			] );

			echo '<section class="ls-product-section ls-featured-products" aria-label="Featured Products">';

			do_action( 'ls_homepage_before_featured_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_after_featured_products_title' );

			echo ls_do_shortcode( 'featured_products', [
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
				'orderby'  => esc_attr( $args['orderby'] ),
				'order'    => esc_attr( $args['order'] ),
			] );

			do_action( 'ls_homepage_after_featured_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_popular_products' ) ) {
	/**
	 * Display Popular Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_popular_products( $args ) {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_popular_products_args', [
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'Fan Favorites', 'light-store' ),
			] );

			echo '<section class="ls-product-section ls-popular-products" aria-label="Popular Products">';

			do_action( 'ls_homepage_before_popular_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_after_popular_products_title' );

			echo ls_do_shortcode( 'top_rated_products', [
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			] );

			do_action( 'ls_homepage_after_popular_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_on_sale_products' ) ) {
	/**
	 * Display On Sale Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_on_sale_products( $args ) {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_on_sale_products_args', [
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'On Sale', 'light-store' ),
			] );

			echo '<section class="ls-product-section ls-on-sale-products" aria-label="On Sale Products">';

			do_action( 'ls_homepage_before_on_sale_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_after_on_sale_products_title' );

			echo ls_do_shortcode( 'sale_products', [
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			] );

			do_action( 'ls_homepage_after_on_sale_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_best_selling_products' ) ) {
	/**
	 * Display Best Selling Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_best_selling_products( $args ) {
		if ( is_woocommerce_activated() ) {
			$args = apply_filters( 'ls_best_selling_products_args', [
				'limit'   => 4,
				'columns' => 4,
				'title'   => esc_attr__( 'Best Sellers', 'light-store' ),
			] );
			echo '<section class="ls-product-section ls-best-selling-products" aria-label="Best Selling Products">';
			do_action( 'ls_homepage_before_best_selling_products' );
			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
			do_action( 'ls_homepage_after_best_selling_products_title' );
			echo ls_do_shortcode( 'best_selling_products', [
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			] );
			do_action( 'ls_homepage_after_best_selling_products' );
			echo '</section>';
		}
	}
}

if ( ! function_exists( 'ls_homepage_content' ) ) {
	/**
	 * Display homepage content
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @return  void
	 */
	function ls_homepage_content() {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'content', 'page' );
		} // end of the loop.
	}
}

if ( ! function_exists( 'ls_homepage_masonry_categories' ) ) {
	/**
	 * Display masonry grid Product Categories
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 *
	 * @return void
	 */
	function ls_homepage_masonry_categories() {

		if ( is_woocommerce_activated() ) {

			$args = apply_filters( 'ls_masonry_product_categories_args', [
				'limit'                     => 4,
				'child_categories'          => 0,
				'orderby'                   => 'name',
				'show_count'                => 1,
				'hide_empty'                => 1,
				'title'                     => __( 'Featured Categories', 'light-store' ),
				'category_grid_sizer_class' => 'category-grid-sizer col-xs-6 col-sm-6 col-md-6 col-lg-3',
				'categories_classes'        => [
					'col-xs-6 col-sm-12 col-md-12  col-lg-6 category-grid-item',
					'col-xs-6 col-sm-6 col-md-6  col-lg-3 category-grid-item',
					'col-xs-6 col-sm-6 col-md-6  col-lg-3 category-grid-item category-grid-item--height2',
					'col-xs-6 col-sm-6 col-md-6  col-lg-3 category-grid-item  category-grid-item--height2',
				]
			] );

			$categories = get_categories( [
				'taxonomy'   => 'product_cat',
				'parent'     => esc_attr( $args['child_categories'] ),
				'orderby'    => esc_attr( $args['orderby'] ),
				'show_count' => esc_attr( $args['show_count'] ),
				'hide_empty' => esc_attr( $args['hide_empty'] ),
			] );

			do_action( 'ls_homepage_before_masonry_product_categories' );

			echo '<h2 class="home-page-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'ls_homepage_masonry_product_categories_title' );

			echo '<div class="categories-masonry row" aria-label="Product Categories Masonry">';

			echo '<div class="' . esc_attr( $args['category_grid_sizer_class'] ) . '"></div>';

			foreach ( $categories as $key => $category ) {
				$cat          = get_term_by( 'slug', $category->slug, 'product_cat' );
				$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
				$image_url    =  esc_url(wp_get_attachment_url( $thumbnail_id ));
				$link         =  esc_url(get_term_link( $category->slug, 'product_cat' ));
				$cat_classes  = $args['categories_classes'][ $key ];

				ob_start(); ?>
				<div class="<?php echo $cat_classes; ?>">
					<a href="<?php echo $link; ?>" class="category-link">
						<div class="category-content">
							<div class="product-category-thumbnail" style="background-image: url(<?php echo $image_url; ?>)"></div>

							<div class="hover-mask">
								<h3><?php echo $cat->name; ?></h3>
								<span class="products-cat-number"><?php echo $cat->count; ?> products</span>
							</div>

						</div>
					</a>
				</div>
				<?php echo ob_get_clean();
			}

			echo '</div>';

			do_action( 'ls_homepage_after_masonry_product_categories' );
		}
	}
}

if ( ! function_exists( 'ls_get_sidebar' ) ) {
	/**
	 * Display ls sidebar
	 *
	 * @uses  get_sidebar()
	 * @since 1.0.0
	 */
	function ls_get_sidebar() {
		get_sidebar();
	}
}

if ( ! function_exists( 'ls_post_thumbnail' ) ) {
	/**
	 * Display post thumbnail
	 *
	 * @var          $size thumbnail size. thumbnail|medium|large|full|$custom
	 * @uses has_post_thumbnail()
	 * @uses the_post_thumbnail
	 *
	 * @param string $size the post thumbnail size.
	 */
	function ls_post_thumbnail( $size = 'full' ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $size );
		}
	}
}

if ( ! function_exists( 'ls_init_structured_data' ) ) {
	/**
	 * Generates structured data.
	 *
	 * Hooked into the following action hooks:
	 *
	 * - `ls_loop_post`
	 * - `ls_single_post`
	 * - `ls_page`
	 *
	 * Applies `ls_structured_data` filter hook for structured data customization
	 */
	function ls_init_structured_data() {

		// Post's structured data.
		if ( is_home() || is_category() || is_date() || is_search() || is_single() && ( is_woocommerce_activated() && ! is_woocommerce() ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'normal' );
			$logo  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

			$json['@type'] = 'BlogPosting';

			$json['mainEntityOfPage'] = [
				'@type' => 'webpage',
				'@id'   => get_the_permalink(),
			];

			$json['publisher'] = [
				'@type' => 'organization',
				'name'  => get_bloginfo( 'name' ),
				'logo'  => [
					'@type'  => 'ImageObject',
					'url'    => $logo[0],
					'width'  => $logo[1],
					'height' => $logo[2],
				],
			];

			$json['author'] = [
				'@type' => 'person',
				'name'  => get_the_author(),
			];

			if ( $image ) {
				$json['image'] = [
					'@type'  => 'ImageObject',
					'url'    => $image[0],
					'width'  => $image[1],
					'height' => $image[2],
				];
			}

			$json['datePublished'] = get_post_time( 'c' );
			$json['dateModified']  = get_the_modified_date( 'c' );
			$json['name']          = get_the_title();
			$json['headline']      = $json['name'];
			$json['description']   = get_the_excerpt();
			// Page's structured data.
		} elseif ( is_page() ) {
			$json['@type']       = 'WebPage';
			$json['url']         = get_the_permalink();
			$json['name']        = get_the_title();
			$json['description'] = get_the_excerpt();
		}

		if ( isset( $json ) ) {
			ls::set_structured_data( apply_filters( 'ls_structured_data', $json ) );
		}
	}
}
