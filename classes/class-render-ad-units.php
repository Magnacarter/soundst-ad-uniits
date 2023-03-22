<?php
/**
 * Class Render all ad units.
 */
namespace Soundst\render_ad_units;
use Soundst\parse_ad_unit_post as PAU;

class Render_Ad_Units extends PAU\Parse_Ad_Unit_Post {
	
	/**
	 * @var object class instance
	 */
	private static $instance = null;

    /**
     * @var array ad_acfs
     */
    private $ad_acfs;

    /**
     * @var array header_array
     */
    private $header_array = [];
    
    /**
     * @var array sidebar_array
     */
    private $sidebar_array = [];
    
    /**
     * @var array content_array
     */
    private $content_array = [];

    /**
     * Constructor function
     */
    public function __construct() {
        parent::__construct();

        // Set ad_acfs.
        $this->set_ad_acfs();
		
		// Parse the ads that are not shortcodes
		$this->parse_ads();
		
		// Hook into the header for header ad units.
        add_action( 'header_adunit', [$this, 'build_header_ad'] );

        // Hook into the sidebar for sidebar ad units.
       	add_shortcode( 'sidebar-adunit', [$this, 'build_sidebar_ad'] );

        // Hook into the content for content ad units.
        add_shortcode( 'content-adunit', [$this, 'build_content_ad'] );
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
     * Parse ad, for shortcodes
     * 
     * Merge the values into one array and send each to the 
     * appropreiate location hook function.
     * 
     * @return void
     */
    public function parse_ad( $ad_id ) {
		$acfs                           = array_column( $this->ad_acfs, $ad_id );
        $placement_ids                  = [];
        $ad_fields                      = [];
        $location                       = [];
		$placement_ids['placement_ids'] = $acfs[0]['ad_placement'];
		$ad_fields['ad']                = $acfs[0]['ad'];
		$location['location']           = $acfs[0]['position'];
		$ad_id['ad_id']                 = $ad_id;
		$scroll['scroll']               = ($acfs['scroll_speed']) ? $acfs['scroll_speed'] : '2000';
		$ad[]                           = [ $placement_ids, $location, $scroll, $ad_fields, $ad_id ];

        if ( ! empty( $ad ) ){
            $this->find_ad_location( $ad );
        }
    }
	
    /**
     * Parse ads, for action hooks
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
			$key = key( $acf );

            if ( ! empty( $acf[$key]['ad_placement'] ) ) {
				$placement_ids['placement_ids'] = $acf[$key]['ad_placement'];
				$ad_fields['ad']                = $acf[$key]['ad'];
				$location['location']           = $acf[$key]['position'];
				$scroll['scroll']               = ( $acf[$key]['scroll_speed'] ) ? $acf[$key]['scroll_speed'] : '2000';
				$ad_id['ad_id']                 = $key;
				$ads[]                          = [$placement_ids, $location, $scroll, $ad_fields, $ad_id];
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
        $this->sidebar_array[] = $this->loop_over_ads( $ad );
    }

    /**
     * Loop over ad units
     * 
     * @param array ad
     * @return array ad[2]
     */
    public function loop_over_ads( $ad ) {
        if ( ! is_null( $ad ) ) {
            global $post;
            $ids      = array_unique( $ad[0] );
			$ready_ad = [];
            foreach ( $ids as $id_arr ) {
                if ( empty( $id_arr ) ) {
                    return;
                }

                foreach ( $id_arr as $id ) {
                    if ( 
						is_single( $id )
						||
						is_page( $id )
					) {
						$ad[3]['scroll'] = ($ad[2]['scroll']) ? $ad[2]['scroll'] : '2000';
						$ad[3]['ad_id'] = $ad[4];
						$ready_ad[] = $ad[3];
                    }
                }
            }
			return $ready_ad;
        }
    }
	
    /**
     * Build sidebar ad.
     * 
     * @param array $ad_arr
     * @return void
     */
    public function build_sidebar_ad( $atts, $content = null ) {
		foreach ( $atts as $ad_id ) {
			$this->parse_ad( $ad_id );
			$arrs = $this->sidebar_array;
		
			foreach ( $arrs as $arr ) {
				if ( empty( $arr ) ) {
					continue;
				} else {
					$arr = $arr[0];
				}
			}

			if ( empty( $arr ) ) {
				return;
			}

			$this->scroll_speed_sidebar( $arr['scroll'] );

			ob_start();
			?>
			<div class="slick-slider-sidebar">
			<?php
				foreach ( $arr['ad'] as $ad ) {
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
			$output = ob_get_clean();
			print( $output . $content );
		}
    }

    /**
     * Build header ad.
     * 
     * @param array $ad_arr
     * @return void
     */
    public function build_header_ad() {
		$arrs = $this->header_array;

		foreach ( $arrs as $arr ) {
			if ( empty( $arr ) ) {
				continue;
			} else {
				$arr = $arr;
			}
		}

		if ( empty( $arr ) ) {
			return;
		}

		$this->scroll_speed_header( $arr['scroll'] );

		ob_start();
		?>
		<div class="row text-center" style="margin: 30px 0;">
			<div class="slick-slider-header header-adunit">
				<?php
				foreach ( $arr['ad'] as $ad ) {
					$ad_link = $ad['ad_link'];
					$img_url = $ad['ad_image']['url'];
					$img_alt = $ad['ad_image']['alt'];
				?>
				<div class="slide">
					<a href="<?php echo esc_url( $ad_link ); ?>">
						<img
							 height="780px" width="90px"
							 src="<?php echo esc_url( $img_url ) ?>"
							 alt="<?php echo esc_attr( $img_alt ); ?>"
							 />
					</a>
				</div>
				<?php           
				}
				?>
			</div>
		</div>
		<?php
		$output = ob_get_clean();
		print( $output );
    }

    /**
     * Build content ad.
     * 
     * @param object $content
     * @return object $content
     */
    public function build_content_ad( $atts, $content = null ) {
		foreach ( $atts as $ad_id ) {
			$this->parse_ad( $ad_id );

			$arrs = $this->content_array;
		
			foreach ( $arrs as $arr ) {
				if ( empty( $arr ) ) {
					continue;
				} else {
					$arr = $arr[0];
				}
			}

			if ( empty( $arr ) ) {
				return;
			}

			$this->scroll_speed_content( $arr['scroll'] );

			ob_start();
			?>
			<div class="slick-slider-content">
			<?php
				foreach ( $arr['ad'] as $ad ) {
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
			$output = ob_get_clean();
			print( $output . $content );
		}
    }

	/**
	 * If the class isn't instantiated, initialize it.
	 *
	 * @return object
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new Render_Ad_Units();
		}
 
		return self::$instance;
	}
	
	/**
	 *
	 */
	public function scroll_speed_header( $speed ) {
		?>
		<script>
			const $j = jQuery.noConflict();
			$j(function(){
				$j('.slick-slider-header').slick({
					autoplay: true,
					autoplaySpeed: <?php echo $speed; ?>,
					arrows: false,
					fade: true,
					centerMode: true
				});
			});
		</script>
		<?php
	}
	
	/**
	 *
	 */
	public function scroll_speed_sidebar( $speed ) {
		?>
		<script>
			const $jq = jQuery.noConflict();
			$jq(function(){
				$jq('.slick-slider-sidebar').slick({
					autoplay: true,
					autoplaySpeed: <?php echo $speed; ?>,
					arrows: false,
					fade: true,
					centerMode: true
				});
			});
		</script>
		<?php
	}
	
	/**
	 *
	 */
	public function scroll_speed_content( $speed ) {
		?>
		<script>
			const $jqw = jQuery.noConflict();
			$jqw(function(){
				$jqw('.slick-slider-content').slick({
					autoplay: true,
					autoplaySpeed: <?php echo $speed; ?>,
					arrows: false,
					fade: true,
					centerMode: true
				});
			});
		</script>
		<?php
	}
}

$ad_unit = Render_Ad_Units::getInstance();
