<?php
defined( 'ABSPATH' ) || exit;


// populates the user role field with the list of user roles
function zmpm_acf_set_roles_as_choices( $field ) {
	
	// reset choices
	$field['choices'] = array();
	
	// populate the field choices with the list of user roles
	$roles = wp_roles()->roles;
	
	foreach( $roles as $role_id => $role_obj ) {
		$field['choices'][ $role_id ] = $role_obj['name'];
	}
	
	return $field;
}

add_filter( 'acf/load_field/name=zmpm_roles', 'zmpm_acf_set_roles_as_choices' );


function zmpm_acf_set_permissions_as_choices( $field ) {
	
	// if this is running on a user profile page, we'll show only user-based permissions
	$show_only_users = $field['name'] == 'zmpm_user_permissions' ? true : false;
	
	// reset choices
	$field['choices'] = array();
	
	// populate the field choices with the list of permissions
	$permissions = get_posts( array(
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_type'      => 'zmpm_permission',
		'fields'         => 'ids',
	) );
	foreach ( $permissions as $permission ) {
		if ( $show_only_users && get_field( "zmpm_type", $permission ) != 'user' ) {
			// don't show this permission as an option
			continue;
		}
		
		$field['choices'][ $permission ] = get_the_title( $permission );
	}
	
	return $field;
}

add_filter( 'acf/load_field/name=zmpm_permissions', 'zmpm_acf_set_permissions_as_choices' );
add_filter( 'acf/load_field/name=zmpm_user_permissions', 'zmpm_acf_set_permissions_as_choices' );


function zmpm_acf_set_visibility_for_zmpm_user_permissions_fieldgroup( $field_group ) {
	
	// only interested in the user permissions fieldgroup
	if ( $field_group['key'] != 'group_5cac22ca7aff0' ) {
		return $field_group;
	}
	
	// only show the metabox if some user-based permissions have been created
	$args = array(
		'posts_per_page' => 1,
		'post_type'      => 'zmpm_permission',
		'fields'         => 'ids',
		'meta_key'       => 'zmpm_type',
		'meta_value'     => 'user',
		'meta_compare'   => '=',
	);
	
	if ( ! get_posts( $args ) ) {
		// hide the field group
		$field_group['location'] = array( array() );
	}
	
	return $field_group;
}

add_filter( 'acf/load_field_group', 'zmpm_acf_set_visibility_for_zmpm_user_permissions_fieldgroup' );


function zmpm_acf_set_visibility_for_zmpm_permissions_fieldgroup( $field_group ) {
	
	// only interested in the permissions fieldgroup
	if ( $field_group['key'] != 'group_5cac05252f01e' ) {
		return $field_group;
	}
	
	// only show the metabox if some permissions have been created
	$args = array(
		'posts_per_page' => 1,
		'post_type'      => 'zmpm_permission',
		'fields'         => 'ids',
	);
	
	if ( ! get_posts( $args ) ) {
		// hide the field group
		$field_group['location'] = array( array() );
		
		return $field_group;
	}
	
	// show on all public post types
	$post_types = get_post_types( array(
		'public' => true,
	) );
	
	$location = array();
	foreach ( $post_types as $post_type ) {
		$location[] = array(
			array(
				"param"    => "post_type",
				"operator" => "==",
				"value"    => $post_type,
			),
		);
	}
	
	$location[] = array(
		array(
			'param'    => 'taxonomy',
			'operator' => '==',
			'value'    => 'all',
		),
	);
	
	$field_group['location'] = $location;
	
	return $field_group;
}

add_filter( 'acf/load_field_group', 'zmpm_acf_set_visibility_for_zmpm_permissions_fieldgroup' );
