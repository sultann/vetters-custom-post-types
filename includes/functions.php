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
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/
		'hierarchical'        => false,
		'public'              => false,
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

add_shortcode('waste_art', 'waste_art_callback');

function waste_art_callback(){
	ob_start();
    include VWA_INCLUDES . '/template.php';
	return ob_get_clean();
}


class VWA_Container{
	public $title;
	public $image;
	public $dropdown = array();

	public function __set($property, $value) {
		if ( property_exists( $this, $property ) ) {
			$this->$property = $value;
		}
	}
}


add_action('wp_ajax_get_waste_art_containers', 'get_waste_art_containers');
add_action('wp_ajax_nopriv_get_waste_art_containers', 'get_waste_art_containers');

function get_waste_art_containers(){

	$containers= get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'vwa-container',
			'tax_query' => array(
				array(
					'taxonomy' => 'location',
					'field' => 'term_id',
					'terms' => sanitize_key($_GET['term_id'])
				)
			)
		)
	);
    $response = [];


    foreach ($containers as $container ){
            $contrn = new VWA_Container();
            $contrn->__set('title', $container->post_title);
            if ( has_post_thumbnail($container->ID) ) {
	            $image =  get_the_post_thumbnail_url($container->ID, 'full');
	            $contrn->__set('image', $image);
            }

            $dropdown = get_post_meta($container->ID, 'container_sizes', true);
            if($dropdown){
	            $contrn->__set('dropdown', explode('|', $dropdown));
            }
	        $response[] = $contrn;
    }



    wp_send_json_success($response);
}



add_shortcode('waste_art_form', 'waste_art_form_callback');

function waste_art_form_callback($attr){
	ob_start();
	$params = shortcode_atts(array(
		'emails' => get_option('admin_email')
    ),$attr);

	include VWA_INCLUDES . '/form-template.php';
	return ob_get_clean();
}

add_action('wp_ajax_get_waste_art_form_submit', 'waste_art_form_submit');
add_action('wp_ajax_nopriv_get_waste_art_form_submit', 'waste_art_form_submit');

function waste_art_form_submit(){
    $emails = sanitize_text_field($_POST['emails']);

	$message = '';
	$message .= 'Hallo,<br/>';
	$message .= 'folgender Auftrag wurde erteilt<br/>';

    foreach ($_POST['form_fields'] as $key => $arr){
	    $message .= '<stong>'.ucfirst($arr['name']).': </stong>'.$arr['value'].'<br/>';
    }


    $subject = "Vetters Container-Bestellung";
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From: Vetters Containerservice <dispo@vetters-containerservice.de>';
	$headers[] = 'Reply-To:Vetters <dispo@vetters-containerservice.de>';

	wp_mail($emails, $subject, $message, $headers);



    wp_send_json_success();

}


add_action('wp_ajax_get_customer_name_by_id', 'get_customer_name_by_id');
add_action('wp_ajax_nopriv_get_customer_name_by_id', 'get_customer_name_by_id');

function get_customer_name_by_id(){
    $customer_id = $_GET['customer_id'];
    $customers = array();

	while(has_sub_field('customer_detail', 'options')){
	  $customers[get_sub_field('customer_number', 'options')] = get_sub_field('customer_name', 'options');
    }

    if(isset($customers[$customer_id])){
	    wp_send_json_success(array('name' => $customers[$customer_id]));
    }else{
	    wp_send_json_error();
    }

}