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
    private $ad_units = [];

    /**
     * Constructor function
     */
    public function __construct() {
        // Get the ad units.
        $ads = $this->get_ad_units();

        // Set the ad units class prop.
        $this->set_ad_units( $ads );

        error_log(print_r($this->ad_units, true));
    }

    /**
     * Get the ad unit posts with wp_query
     * 
     * @return object $ads
     */
    public function get_ad_units() {
        $args = array(
            'post_type'      => 'ad_unit',
            'posts_per_page' => 10
        );
        return $ads = new WP_Query( $args );
    }

    /**
     * Set ad units
     * 
     * @param array ads
     * @return void
     */
    public function set_ad_units( $ads ) {
        $this->ad_units = $ads;
    }
}