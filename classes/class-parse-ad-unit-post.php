<?php
/**
 * Class Parse Ad Unit Post.
 */
namespace Soundst\parse_ad_unit_post;
use WP_Query;

// Init class.
new Parse_Ad_Unit_Post();

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

        error_log(print_r($this->ad_unit_acfs, true));
    }
    
    /**
     * Set custom field values from ad unit posts.
     */
    public function set_custom_fields_from_ad_units() {
        $ids = $this->ad_unit_ids;
        $acfs = [];
        foreach ( $ids as $id ) {
            $acfs[] = get_fields( $id );
        }
        //error_log(print_r($acfs, true));
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
            'posts_per_page' => 10,
            'post_status'   => 'publish',
            'fields'        => 'ids'
        );
        $ads = new WP_Query( $args );

        // Restore original Post Data
        wp_reset_postdata();

        return $id_array = $ads->posts;
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