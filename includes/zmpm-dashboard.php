<?php
defined( 'ABSPATH' ) || exit;

// hooks and functions to add Permissions column to pages, posts, users, and taxonomy terms
add_filter( 'manage_pages_columns', 'zmpm_add_permissions_column' );
add_filter( 'manage_users_columns', 'zmpm_add_permissions_column' );
add_filter( 'manage_posts_columns', 'zmpm_add_permissions_column_if_post_type_is_public', 10, 2 );
add_action( 'manage_pages_custom_column', 'zmpm_add_permissions_column_content_posts_pages', 10, 2 );
add_action( 'manage_posts_custom_column', 'zmpm_add_permissions_column_content_posts_pages', 10, 2 );
add_filter( 'manage_users_custom_column', 'zmpm_add_permissions_column_content_users', 10, 3 );

$taxonomies = get_taxonomies( array(
	'public' => true,
) );
foreach ( $taxonomies as $taxonomy ) {
	add_filter( 'manage_edit-' . $taxonomy . '_columns', 'zmpm_add_permissions_column' );
	add_filter( 'manage_' . $taxonomy . '_custom_column', 'zmpm_add_permissions_column_content_taxonomies', 10, 3 );
}


// TAXONOMIES/PAGES/USERS: add a column for Permissions
function zmpm_add_permissions_column( $columns ) {
	$columns['zmpm_permissions'] = 'Permissions';
	
	return $columns;
}

// POSTS: add a column for Permissions, only on public post types
function zmpm_add_permissions_column_if_post_type_is_public( $columns, $post_type_slug ) {
	if ( $post_type = get_post_type_object( $post_type_slug ) ) {
		if ( $post_type->public == true ) {
			$columns['zmpm_permissions'] = 'Permissions';
		}
	}
	
	return $columns;
}

// POSTS/PAGES: add the Permissions column content as an action
function zmpm_add_permissions_column_content_posts_pages( $column_name, $post_id ) {
	if ( $column_name == 'zmpm_permissions' ) {
		echo zmpm_add_permissions_column_content( 'zmpm_permissions', $post_id );
	}
}

// USERS: add the Permissions column content as a filter
function zmpm_add_permissions_column_content_users( $output, $column_name, $user_id ) {
	if ( $column_name == 'zmpm_permissions' ) {
		$output = zmpm_add_permissions_column_content( 'zmpm_user_permissions', 'user_' . $user_id );
	}
	
	return $output;
}

// TAXONOMIES: add the Permissions column content as a filter
function zmpm_add_permissions_column_content_taxonomies( $output, $column_name, $term_id ) {
	if ( $column_name == 'zmpm_permissions' ) {
		$tax_name = substr( current_filter(), 7, - 14 );
		$output   = zmpm_add_permissions_column_content( 'zmpm_permissions', $tax_name . '_' . $term_id );
	}
	
	return $output;
}

// formats acf metadata nicely for output in a column
function zmpm_add_permissions_column_content( $acf_field, $acf_post_id ) {
	ob_start();
	if ( $permissions = get_field( $acf_field, $acf_post_id ) ) {
		foreach ( $permissions as $permission ) {
			echo "&bull;&nbsp;" . get_the_title( $permission ) . "<br>";
		}
	} else {
		echo '&mdash;';
	}
	
	return ob_get_clean();
}


/*
// PERMISSIONS: add User Count column to the Permissions post type

function zmpm_add_permissions_column_to_zmpm_permission( $columns ) {
	$columns['users_with_access'] = 'Users with Access';
	
	return $columns;
}

add_filter( 'manage_zmpm_permission_posts_columns', 'zmpm_add_permissions_column_to_zmpm_permission' );

// PERMISSIONS: add the User Count column content to the Permissions post type
function zmpm_add_permissions_column_content_zmpm_permission( $column_name, $post_id ) {
	if ( $column_name == 'users_with_access' ) {
		$type = get_field( "zmpm_type", $post_id );
		if ($type == 'role') {
			$roles = get_field( "zmpm_roles", $post_id );
		} elseif ($type == 'user') {
		
		}
	}
}
add_action( 'manage_zmpm_permission_posts_custom_column', 'zmpm_add_permissions_column_content_zmpm_permission', 10, 2 );

*/

// adds a settings link to the plugins list
function zmpm_add_settings_link( $links ) {
	array_unshift( $links, '<a href="options-general.php?page=zingmap-permissions">Settings</a>' );
	
	return $links;
}

add_filter( "plugin_action_links_zingmap-permissions/zingmap-permissions.php", 'zmpm_add_settings_link' );
