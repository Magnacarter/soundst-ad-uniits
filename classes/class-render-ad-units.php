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
     * @var array header_array
     */
    private $header_array;
    
    /**
     * @var array sidebar_array
     */
    private $sidebar_array;
    
    /**
     * @var array content_array
     */
    private $content_array;

    /**
     * Constructor function
     */
    public function __construct() {
        parent::__construct();

        // Set ad_acfs.
        $this->set_ad_acfs();

        // Create an array for each ad with all the data we need for rendering them.
        $this->parse_ads();

        // Hook into the header for header ad units.
        add_action( 'wp_head', [$this, 'build_header_ad'] );

        // Hook into the sidebar for sidebar ad units.
        add_action( 'get_sidebar', [$this, 'build_sidebar_ad'] );

        // Hook into the content for content ad units.
        add_filter( 'the_content', [$this, 'build_content_ad'], 1 );
    }

    /**
     * Set the ad acfs class prop.
     * 
     * @return void
     */
    public function set_ad_acfs() {
        $this->ad_acfs = parent::get_ad_unit_acfs();
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
        $acfs          = $this->ad_acfs;
        $placement_ids = [];
        $ad_fields     = [];
        $location      = [];

        foreach( $acfs as $acf ) {
            if ( ! empty( $acf['ad_placement'] ) ) {
                $placement_ids['placement_ids'] = $acf['ad_placement'];
                $ad_fields['ad']                = $acf['ad'];
                $location['location']           = $acf['position'];
                $ads[]                          = [ $placement_ids, $location, $ad_fields ];
            }
        }

        if ( ! empty( $ads ) ){
            $this->find_ad_location( $ads );
        }
    }

    /**
     * Find where the ad is placed.
     */
    public function find_ad_location( $ads ) {
        foreach ( $ads as $ad ) {
            switch ( $ad[1]['location'] ) {
                case 'header':
                    $this->set_header_array( $ad );
                  break;
                case 'content-footer':
                    $this->set_content_array( $ad );
                  break;
                case 'sidebar':
                    $this->set_sidebar_array( $ad );
                  break;
              }
        }
    }

    /**
     * Ad for header.
     * 
     * @param array ad
     */
    public function set_header_array( $ad ) {
        $this->header_array = $this->loop_over_ads( $ad );
    }

    /**
     * Ad for bottom of content.
     * 
     * @param array ad
     */
    public function set_content_array( $ad ) {
        $this->content_array = $this->loop_over_ads( $ad );
    }

    /**
     * Ad for sidebar.
     * 
     * @param array ad
     */
    public function set_sidebar_array( $ad ) {
        $this->sidebar_array = $this->loop_over_ads( $ad );
    }

    /**
     * Loop over ad units
     * 
     * @param array ad
     * @return array ad[2]
     */
    public function loop_over_ads( $ad ) {
        if ( ! empty( $ad ) ) {
            global $post;
            $ids = $ad[0];

            foreach ( $ids as $id_arr ) {
                if ( empty( $id_arr ) ) {
                    return;
                }

                foreach ( $id_arr as $id ) {
                    if ( is_single( (int)$id ) || is_page ( (int)$id ) ) {
                        return $ad[2];
                    }
                }
            }
        }
    }

    /**
     * Build header ad.
     * 
     * @param array $ad_arr
     * @return void
     */
    public function build_header_ad() {
        $ad_arr = $this->header_array;

        if ( empty( $ad_arr ) ) {
            return;
        }

        ob_start();
        ?>
            <div class="slick-slider">
        <?php
        foreach ( $ad_arr['ad'] as $ad ) {
            $ad_link = $ad['ad_link'];
            $img_url = $ad['ad_image']['url'];
            $img_alt = $ad['ad_image']['alt'];
            ?>
                <div class="slide">
                    <a href="<?php echo esc_url( $ad_link ); ?>">
                        <img 
                            src="<?php echo esc_url( $img_url ) ?>"
                            alt="<?php echo esc_attr( $img_alt ); ?>"
                        />
                    </a>
                </div>
            <?php           
        }
        ?>
            </div>
        <?php
        return $output = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Build sidebar ad.
     * 
     * @param array $ad_arr
     * @return void
     */
    public function build_sidebar_ad() {
        $ad_arr = $this->sidebar_array;

        if ( empty( $ad_arr ) ) {
            return;
        }

        ob_start();
        ?>
            <div class="slick-slider">
        <?php
        foreach ( $ad_arr['ad'] as $ad ) {
            $ad_link = $ad['ad_link'];
            $img_url = $ad['ad_image']['url'];
            $img_alt = $ad['ad_image']['alt'];
            ?>
                <div class="slide">
                    <a href="<?php echo esc_url( $ad_link ); ?>">
                        <img 
                            src="<?php echo esc_url( $img_url ) ?>"
                            alt="<?php echo esc_attr( $img_alt ); ?>"
                        />
                    </a>
                </div>
            <?php           
        }
        ?>
            </div>
        <?php
        return $output = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Build content ad.
     * 
     * @param object $content
     * @return object $content
     */
    public function build_content_ad( $content ) {
        $ad_arr = $this->content_array;

        if ( empty( $ad_arr ) ) {
            return $content;
        }

        ob_start();
        ?>
            <div class="slick-slider">
        <?php
        foreach ( $ad_arr['ad'] as $ad ) {
            $ad_link = $ad['ad_link'];
            $img_url = $ad['ad_image']['url'];
            $img_alt = $ad['ad_image']['alt'];
            ?>
                <div class="slide">
                    <a href="<?php echo esc_url( $ad_link ); ?>">
                        <img 
                            src="<?php echo esc_url( $img_url ) ?>"
                            alt="<?php echo esc_attr( $img_alt ); ?>"
                        />
                    </a>
                </div>
            <?php           
        }
        ?>
            </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $content .= $output;
    }
}
