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

        foreach( $acfs as $acf ) {
            if ( $acf['position'] == 'header' ) {
                $h_placement_ids['placement_ids'] = $acf['ad_placement'];
                $h_ad_fields['ad'] = $acf['ad'];
                $h_location['location'] = 'header';
                $h_arrs = array_merge( $h_placement_ids, $h_ad_fields );
                $h_ad   = array_merge( $h_location, $h_arrs );
            } elseif ( $acf['position'] == 'content_footer' ) {
                $c_placement_ids['placement_ids'] = $acf['ad_placement'];
                $c_ad_fields['ad'] = $acf['ad'];
                $c_location['location'] = 'content_footer';
                $c_arrs = array_merge( $c_placement_ids, $c_ad_fields );
                $c_ad   = array_merge( $c_location, $c_arrs );
            } elseif ( $acf['position'] == 'sidebar' ) {
                $s_placement_ids['placement_ids'] = $acf['ad_placement'];
                $s_ad_fields['ad'] = $acf['ad'];
                $s_location['location'] = 'sidebar';
                $s_arrs = array_merge( $s_placement_ids, $s_ad_fields );
                $s_ad   = array_merge( $s_location, $s_arrs );
            }
        }

        if ( ! empty( $h_ad ) ){
            $this->header_ad( $h_ad );
        }
        if ( ! empty( $c_ad ) ){
            $this->bottom_content_ad( $c_ad );
        }
        if ( ! empty( $s_ad ) ){
            $this->sidebar_ad( $s_ad );
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
