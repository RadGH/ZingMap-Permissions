<?php
defined( 'ABSPATH' ) || exit;


// depending on whether we'll redirect or give an error, check access on template redirect or include
$behavior = get_field( "zmpm_behavior", "options" );
if ( in_array( $behavior, array( 'redirect_url', 'redirect_page' ) ) ) {
	add_action( 'template_redirect', 'zmpm_check_if_authorized_on_template_redirect' );
} elseif ( $behavior == 'error' ) {
	add_filter( 'template_include', 'zmpm_check_if_authorized_on_template_include', 99999 );
}


function zmpm_check_if_authorized_on_template_redirect() {
	if ( zmpm_is_user_authorized() ) {
		return;
	}
	
	$behavior = get_field( "zmpm_behavior", "options" );
	if ( $behavior == 'redirect_url' ) {
		$destination = get_field( "zmpm_redirect_url", "options" );
	} elseif ( $behavior == 'redirect_page' ) {
		$destination = get_field( "zmpm_redirect_page", "options" );
	}
	
	if ( isset( $destination ) ) {
		wp_redirect( $destination, 307 );
		die;
	}
}

function zmpm_check_if_authorized_on_template_include( $template ) {
	
	if ( zmpm_is_user_authorized() ) {
		return $template;
	}
	
	// if not authorized, use the theme's page template and filter the title and content
	header( "HTTP/1.1 401 Unauthorized" );
	add_filter( 'wp_title', 'zpmp_get_error_title', 99999 ); // site <title>
	add_filter( 'wpseo_title', 'zpmp_get_error_title', 99999 ); // yoast <title> filter
	add_filter( 'wpseo_metadesc', 'zpmp_get_error_content', 99999 ); // yoast meta description
	
	add_filter( 'the_title', 'zmpm_replace_title_with_error_message', 99999 ); // page title
	add_filter( 'the_content', 'zmpm_replace_content_with_error_message', 99999 ); // page content
	
	add_filter( 'comments_open', '__return_false', 99999 ); // disable comments
	
	// Allow placing a custom error template in your theme.
	$zm_template = locate_template( array(
		'zmpm-unauthorized.php'
	), false );
	
	if ( $zm_template ) return $zm_template;
	
	// Allow placing hooks here before anything is displayed, to customize things in the <head>
	do_action( 'zmpm/begin_unauthorized_page', $template );
	
	// If no template to handle the error, use a custom page.
	get_header();
	do_action( 'zmpm/get_header', $template );
	
	?>
	
	<article class="zmpm-error-page">
		<h1 class="page-title"><?php echo zpmp_get_error_title(); ?></h1>
		
		<div class="page-content"><?php echo zpmp_get_error_content(); ?></div>
	</article>
	
	<?php
	
	do_action( 'zmpm/get_footer', $template );
	get_footer();
	exit;
}

// Get the error page title from the settings, allow filtering
function zpmp_get_error_title() {
	$title = get_field( "zmpm_error_title", "options" );
	return apply_filters( "zmpm_error_title", $title );
}

// Get the error page content from the settings, allow filtering
function zpmp_get_error_content() {
	$content = get_field( "zmpm_error_message", "options" );
	return apply_filters( "zmpm_error_message", $content );
}

function zmpm_replace_title_with_error_message( $title ) {
	if ( in_the_loop() ) {
		remove_filter( 'the_title', 'zmpm_replace_title_with_error_message' );
		return zpmp_get_error_title();
	}
	
	return $title;
}

function zmpm_replace_content_with_error_message( $content ) {
	if ( in_the_loop() ) {
		remove_filter( 'the_content', 'zmpm_replace_content_with_error_message' );
		return zpmp_get_error_content();
	}
	
	return $content;
}


function zmpm_is_user_authorized() {
	
	// determine what was queried and fetch its permissions
	if ( is_singular() ) {
		// post or page
		$permissions = get_field( "zmpm_permissions" );
	} elseif ( is_tag() || is_category() || is_tax() ) {
		// term
		$object      = get_queried_object();
		$acf_post_id = $object->taxonomy . '_' . $object->term_id;
		$permissions = get_field( "zmpm_permissions", $acf_post_id );
	}
	
	// if there were no permissions, the queried object is public
	if ( ! isset( $permissions ) ) {
		return true;
	}
	
	// note: users who are not logged in definitely do not have access
	if ( $user = wp_get_current_user() ) {
		
		$user_permissions = ( array ) get_field( "zmpm_user_permissions", 'user_' . $user->ID );
		$user_roles       = ( array ) $user->roles;
		
		foreach ( $permissions as $permission ) {
			$type = get_field( "zmpm_type", $permission );
			if ( $type == 'user' ) {
				if ( in_array( $permission, $user_permissions ) ) {
					// the user has this user-based permission
					return true;
				}
				
			} elseif ( $type == 'role' ) {
				$permission_roles = get_field( "zmpm_roles", $permission );
				
				if ( count( array_intersect( $permission_roles, $user_roles ) ) ) {
					// the user has a role permitted by this role-based permission
					return true;
				}
			}
		}
	}
	
	return false;
}