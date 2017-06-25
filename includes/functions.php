<?php
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

function add_custom_taxonomies() {
	// Add new "Locations" taxonomy to Posts
	register_taxonomy('location', 'vwa-container', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Waste Arts', 'taxonomy general name' ),
			'singular_name' => _x( 'Waste Art', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Waste Arts' ),
			'all_items' => __( 'All Waste Arts' ),
			'parent_item' => __( 'Parent Waste Art' ),
			'parent_item_colon' => __( 'Parent Waste Art:' ),
			'edit_item' => __( 'Edit Waste Art' ),
			'update_item' => __( 'Update Waste Art' ),
			'add_new_item' => __( 'Add New Waste Art' ),
			'new_item_name' => __( 'New Waste Art Name' ),
			'menu_name' => __( 'Waste Arts' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'abfallarten', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => false // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action( 'init', 'add_custom_taxonomies', 0 );



function vetters_waste_container_cpt() {
	$labels = array(
		'name'                => _x( 'Containers', 'Post Type General Name', 'waste_art' ),
		'singular_name'       => _x( 'Container', 'Post Type Singular Name', 'waste_art' ),
		'menu_name'           => __( 'Containers', 'waste_art' ),
		'parent_item_colon'   => __( 'Parent Container', 'waste_art' ),
		'all_items'           => __( 'All Containers', 'waste_art' ),
		'view_item'           => __( 'View Container', 'waste_art' ),
		'add_new_item'        => __( 'Add New Container', 'waste_art' ),
		'add_new'             => __( 'Add New', 'waste_art' ),
		'edit_item'           => __( 'Edit Container', 'waste_art' ),
		'update_item'         => __( 'Update Container', 'waste_art' ),
		'search_items'        => __( 'Search Container', 'waste_art' ),
		'not_found'           => __( 'Not Found', 'waste_art' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'waste_art' ),
	);

	$args = array(
		'label'               => __( 'container', 'waste_art' ),
		'description'         => __( 'Container news and reviews', 'waste_art' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title',  'thumbnail', 'custom-fields' ),
		// You can associate this CPT with a taxonomy or custom taxonomy.
		'taxonomies'          => array( 'genres' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/
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
	);

	// Registering your Custom Post Type
	register_post_type( 'vwa-container', $args );

}

add_action( 'init', 'vetters_waste_container_cpt', 0 );

function wpdocs_register_meta_boxes(){
	add_meta_box( 'container-size', __( 'Container sizes', 'waste_art' ), 'container_sizes_callback', 'vwa-container', 'advanced', 'high' );
}


add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
function container_sizes_callback($post){
	$value = '';
	if(get_post_meta($post->ID, 'container_sizes', true)){
		$value = get_post_meta($post->ID, 'container_sizes', true);
	}
	?>
	<input name="container_sizes" type="text" class="regular-text" style="width: 100%" value="<?php echo $value; ?>">
	<p><?php _e('Separate by comma. eg 10mm, 200m3');?></p>
	<?php
}

add_action('save_post_vwa-container', 'save_vwa_container_values');
function save_vwa_container_values($post_id){
	if ( get_post_type($post_id) !== 'vwa-container' ) return;
	if ( isset($_POST['container_sizes']) ){
		update_post_meta($post_id, 'container_sizes', sanitize_text_field($_POST['container_sizes']));
	}else{
		delete_post_meta($post_id, 'container_sizes');
	}
}