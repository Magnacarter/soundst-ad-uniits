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

        error_log(print_r($this->ad_acfs, true));
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
