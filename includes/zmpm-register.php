<?php
defined( 'ABSPATH' ) || exit;

// register options page
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_sub_page( array(
		'page_title'  => 'Permissions Settings',
		'menu_title'  => 'Permissions',
		'menu_slug'   => 'zingmap-permissions',
		'parent_slug' => 'options-general.php',
	) );
}

// register custom post type
function zmpm_register_post_type() {
	
	$labels = array(
		'name'                  => 'Permissions',
		'singular_name'         => 'Permission',
		'menu_name'             => 'Permissions',
		'name_admin_bar'        => 'Permission',
		'archives'              => 'Permission Archives',
		'attributes'            => 'Permission Attributes',
		'parent_item_colon'     => 'Parent Permission:',
		'all_items'             => 'Permissions',
		'add_new_item'          => 'Add New Permission',
		'add_new'               => 'Add New',
		'new_item'              => 'New Permission',
		'edit_item'             => 'Edit Permission',
		'update_item'           => 'Update Permission',
		'view_item'             => 'View Permission',
		'view_items'            => 'View Permissions',
		'search_items'          => 'Search Permission',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not Found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into permission',
		'uploaded_to_this_item' => 'Uploaded to this permission',
		'items_list'            => 'Permissions list',
		'items_list_navigation' => 'Permissions list navigation',
		'filter_items_list'     => 'Filter permissions list',
	);
	$args   = array(
		'label'               => 'Permission',
		'description'         => 'ZingMap permissions',
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'users.php',
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-lock',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'zmpm_permission', $args );
	
}

add_action( 'init', 'zmpm_register_post_type' );


if( function_exists('acf_add_local_field_group') ):
	
	acf_add_local_field_group(array(
		'key' => 'group_5cac05252f01e',
		'title' => 'Permissions',
		'fields' => array(
			array(
				'key' => 'field_5cac052c165e9',
				'label' => '',
				'name' => 'zmpm_permissions',
				'type' => 'checkbox',
				'instructions' => 'Restrict access to users with specific permissions or leave blank to allow all.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
				),
				'allow_custom' => 0,
				'default_value' => array(
				),
				'layout' => 'vertical',
				'toggle' => 0,
				'return_format' => 'value',
				'save_custom' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	acf_add_local_field_group(array(
		'key' => 'group_5cad216806713',
		'title' => 'Permissions Settings',
		'fields' => array(
			array(
				'key' => 'field_5cabd93b60761',
				'label' => 'Behavior',
				'name' => 'zmpm_behavior',
				'type' => 'radio',
				'instructions' => 'Control the behavior when an unauthorized user tries to access a restricted page.',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'error' => 'Display an error message.',
					'redirect_page' => 'Redirect to another page on the website.',
					'redirect_url' => 'Redirect to a custom URL.',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'default_value' => '',
				'layout' => 'vertical',
				'return_format' => 'value',
				'save_other_choice' => 0,
			),
			array(
				'key' => 'field_5cad55c7de7cc',
				'label' => 'Error Title',
				'name' => 'zmpm_error_title',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5cabd93b60761',
							'operator' => '==',
							'value' => 'error',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Access Forbidden',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_5cabdae660762',
				'label' => 'Error Message',
				'name' => 'zmpm_error_message',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5cabd93b60761',
							'operator' => '==',
							'value' => 'error',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Sorry, you are not authorized to access this content.',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_5cabdb0060763',
				'label' => 'Redirect Page',
				'name' => 'zmpm_redirect_page',
				'type' => 'page_link',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5cabd93b60761',
							'operator' => '==',
							'value' => 'redirect_page',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => '',
				'taxonomy' => '',
				'allow_null' => 0,
				'allow_archives' => 1,
				'multiple' => 0,
			),
			array(
				'key' => 'field_5cabdb4c60764',
				'label' => 'Redirect URL',
				'name' => 'zmpm_redirect_url',
				'type' => 'url',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5cabd93b60761',
							'operator' => '==',
							'value' => 'redirect_url',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'zingmap-permissions',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	acf_add_local_field_group(array(
		'key' => 'group_5cac22ca7aff0',
		'title' => 'User Permissions',
		'fields' => array(
			array(
				'key' => 'field_5cad2de8d013d',
				'label' => '',
				'name' => 'zmpm_user_permissions',
				'type' => 'checkbox',
				'instructions' => 'Grant access to content restricted by user-based permissions.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
				),
				'allow_custom' => 0,
				'default_value' => array(
				),
				'layout' => 'vertical',
				'toggle' => 0,
				'return_format' => 'value',
				'save_custom' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'user_form',
					'operator' => '==',
					'value' => 'edit',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	acf_add_local_field_group(array(
		'key' => 'group_5cabd79bae45b',
		'title' => 'ZingMap Permissions',
		'fields' => array(
			array(
				'key' => 'field_5cabd7a36075f',
				'label' => 'Type',
				'name' => 'zmpm_type',
				'type' => 'radio',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'user' => 'User-based permission. Access is controlled by a checkbox on individual user pages.',
					'role' => 'Role-based permission. Access is controlled at the user role level.',
				),
				'allow_null' => 0,
				'other_choice' => 0,
				'default_value' => '',
				'layout' => 'vertical',
				'return_format' => 'value',
				'save_other_choice' => 0,
			),
			array(
				'key' => 'field_5cabd88560760',
				'label' => 'Roles',
				'name' => 'zmpm_roles',
				'type' => 'checkbox',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5cabd7a36075f',
							'operator' => '==',
							'value' => 'role',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
				),
				'allow_custom' => 0,
				'default_value' => array(
				),
				'layout' => 'vertical',
				'toggle' => 0,
				'return_format' => 'value',
				'save_custom' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'zmpm_permission',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

endif;
