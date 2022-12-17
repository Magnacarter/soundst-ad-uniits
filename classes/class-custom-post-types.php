<?php
/**
 * Class create a custom post types.
 */
namespace Soundst\create_cpt;

class Create_Custom_Post_Type {

  // Set options for Custom Post Type
  private $args = [];

  // Constructor function to initialize the custom post type
  public function __construct( $args = [] ) {
    $this->set_args( $args );
    add_action( 'init', array( $this, 'create_post_type' ) );
  }

  public function set_args( $args ) {
    return $this->args = $args;
  }
 
  // Register the custom post type
  public function create_post_type() {
    register_post_type( 'ad_unit', $this->args );
  }
}

$labels = [
  'name'                => _x( 'Ad Units', 'Post Type General Name' ),
  'singular_name'       => _x( 'Ad Unit', 'Post Type Singular Name' ),
  'menu_name'           => __( 'Ad Units' ),
  'parent_item_colon'   => __( 'Parent Ad Unit' ),
  'all_items'           => __( 'All Ad Units' ),
  'view_item'           => __( 'View Ad Unit' ),
  'add_new_item'        => __( 'Add New Ad Unit' ),
  'add_new'             => __( 'Add New' ),
  'edit_item'           => __( 'Edit Ad Unit' ),
  'update_item'         => __( 'Update Ad Unit' ),
  'search_items'        => __( 'Search Ad Unit' ),
  'not_found'           => __( 'Not Found' ),
  'not_found_in_trash'  => __( 'Not found in Trash' )
];

$args = [
  'label'               => __( 'Ad units' ),
  'description'         => __( 'Ad Units' ),
  'labels'              => $labels,
  'supports'            => array( 'title', 'author', 'revisions', 'custom-fields', ), 
  'taxonomies'          => array( '' ),
  'hierarchical'        => false,
  'public'              => true,
  'show_ui'             => true,
  'show_in_menu'        => true,
  'show_in_nav_menus'   => true,
  'show_in_admin_bar'   => true,
  'menu_position'       => 5,
  'can_export'          => true,
  'has_archive'         => true,
  'exclude_from_search' => false,
  'publicly_queryable'  => true,
  'capability_type'     => 'post',
  'show_in_rest'        => true
];

// Initialize ad unit post type
new Create_Custom_Post_Type( $args );
