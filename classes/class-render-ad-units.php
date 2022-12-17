<?php
/**
 * Class Render all ad units.
 */
namespace Soundst\render_ad_units;
use Soundst\parse_ad_unit_post as PAU;

// Init class.
new Render_Ad_Units();

class Render_Ad_Units extends PAU\Parse_Ad_Unit_Post {
    /**
     * @var array ad_acfs
     */
    private $ad_acfs;

    /**
     * Constructor function
     */
    public function __construct() {
        parent::__construct();

        // Set ad_acfs.
        $this->set_ad_acfs();

        //
        $this->parse_ads();
    }

    /**
     * Parse ads
     * 
     * Merge the values into one array and send each to the 
     * appropreiate location hook function.
     * 
     * @return void
     */
    public function parse_ads() {
        $acfs = $this->ad_acfs;
        $placement_ids = [];
        $ad_fields = [];
        $location = [];
        foreach( $acfs as $acf ) {
            $placement_ids['placement_ids'] = $acf['ad_placement'];
            $ad_fields['ad'] = $acf['ad'];
            $location['location'] = $acf['position'];
            $ad[] = [ $placement_ids, $location, $ad_fields ];
        }

        if ( ! empty( $ad ) ){
            $this->header_ad( $ad );
        }
    }

    /**
     * Ad for header.
     */
    public function header_ad( $ad ) {
        error_log(print_r($ad, true));
    }

    /**
     * Ad for bottom of content.
     */
    public function bottom_content_ad( $ad ) {
        error_log(print_r($ad, true));
    }

    /**
     * Ad for sidebar.
     */
    public function sidebar_ad( $ad ) {
        error_log(print_r($ad, true));
    }

    /**
     * Set the ad acfs class prop.
     * 
     * @return void
     */
    public function set_ad_acfs() {
        $this->ad_acfs = parent::get_ad_unit_acfs();
    }
}
