<?php
/**
 * Class Parse Ad Unit Post.
 */
namespace Soundst\parse_ad_unit_post;
use WP_Query;

class Parse_Ad_Unit_Post {
    /**
     * @var array ad_units
     */
    private $ad_unit_ids = [];

    /**
     * @var array ad_unit_acfs
     */
    private $ad_unit_acfs;

    /**
     * Constructor function
     */
    public function __construct() {
        // Get the ad units.
        $ads = $this->get_ad_units();

        // Set the ad units class prop.
        $this->set_ad_units( $ads );

        // Set the ad unit's acfs
        $this->set_custom_fields_from_ad_units();
    }

    /**
     * Get acfs from all ad units.
     * 
     * This is the main function we will use in our extended class
     * to retrieve the acfs from all ad unit posts and render them
     * with the child class.
     * 
     * @return array $acfs
     */
    public function get_ad_unit_acfs() {
        return $this->ad_unit_acfs;
    }
    
    /**
     * Set custom field values from ad unit posts.
     */
    public function set_custom_fields_from_ad_units() {
        $ids  = $this->ad_unit_ids;
        $acfs = [];
        foreach ( $ids as $id ) {
            $acfs[] = [$id => get_fields( $id )];
        }
        $this->ad_unit_acfs = $acfs;
    }

    /**
     * Get the ad unit posts with wp_query
     * 
     * @return array $id_array
     */
    public function get_ad_units() {
        $args = array(
            'post_type'      => 'ad_unit',
            'posts_per_page' => 99,
            'post_status'    => 'publish',
            'fields'         => 'ids'
        );
        $ads = new WP_Query( $args );

       	$id_array = $ads->posts;
		
		// Restore original Post Data
        wp_reset_postdata();
		
		return $id_array;
    }

    /**
     * Set ad units
     * 
     * @param array ads
     * @return void
     */
    public function set_ad_units( $ads ) {
        $this->ad_unit_ids = $ads;
    }
}
