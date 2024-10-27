<?php
/**
 *
 * @since             1.0.1
 * @package           Aggregate_Rating
 *
 * @wordpress-plugin
 * Plugin Name:       Aggregate Rating
 * Plugin URI:        http://example.com/seo-dynamic-pages-uri/
 * Description:       Aggregate Rating is a plugin which will help user to show star rating on google search result of webpage.
 * Version:           1.0.3
 * Author:            118GROUP Web Design
 * Author URI:        https://www.118group.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aggregate-rating
 * Domain Path:       /languages
 */

// don't call the file directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * AggregateRating class
 *
 * @class AggregateRating The class that holds the entire AggregateRating plugin
 */
class Aggregate_Rating {

	/**
	 * Initializes the Aggregate_Rating() class
	 *
	 * Checks for an existing Aggregate_Rating() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = false;

		if (!$instance) {
			$instance = new Aggregate_Rating();

			$instance->plugin_init();
		}

		return $instance;
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function plugin_init() {
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'register_settings'));

		add_action('wp_footer', array($this, 'register_function'));

	}

	/**
	 *
	 *
	 * @return
	 */
	public function admin_menu() {
		add_menu_page(__('Aggregate Rating', 'aggregate-rating'), __('Aggregate Rating', 'aggregate-rating'), 'manage_options', 'aggregate-rating', array($this, 'aggregate_rating_cb_func'), 'dashicons-heart', 6);
	}

	/**
	 *
	 *
	 * @return
	 */
	public function get_d_val($op, $dop, $slug) {

		if (null !== get_option($op)) {
			if (isset(get_option($op)[$slug]) && get_option($op)[$slug]) {
				return get_option($op)[$slug];
			}
		}
		return get_option($dop) ? get_option($dop) : '';
	}

	/**
	 *
	 *
	 * @return
	 */
	public function is_act_sl($slug) {
		if (null !== get_option('ps_aggr_slug_act')) {
			if (isset(get_option('ps_aggr_slug_act')[$slug]) && get_option('ps_aggr_slug_act')[$slug]) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 *
	 * @return
	 */
	public function aggregate_rating_cb_func() {
		?>
        <style type="text/css">
            .form-table {
                border: 1px solid #d5d5d5;
            }
            .form-table td,
            .form-table th {
                padding: 5px !important;
                line-height: 29px;
            }
        </style>
        <div class="wrap">
            <h3>Aggregate Rating</h3>
            <form method="post" action="options.php">
                <?php settings_fields('aggregate-rating-settings-group');?>
                <?php do_settings_sections('aggregate-rating-settings-group');?>
                <br>
                <strong>Basic Info</strong>
                <table class="form-table" style="width: 700px;">
                    <tr valign="top">
                        <th scope="row">Business / Company Name *</th>
                        <td><input style="width: 100%" type="text" name="aggr_company_name" value="<?php echo esc_attr(get_option('aggr_company_name')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Street Address *</th>
                        <td><input style="width: 100%" type="text" name="aggr_street_address" value="<?php echo esc_attr(get_option('aggr_street_address')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Address Locality *</th>
                        <td><input style="width: 100%" type="text" name="aggr_address_locality" value="<?php echo esc_attr(get_option('aggr_address_locality')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Address Region *</th>
                        <td><input style="width: 100%" type="text" name="aggr_address_region" value="<?php echo esc_attr(get_option('aggr_address_region')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Phone *</th>
                        <td><input style="width: 100%" type="text" name="aggr_phone" value="<?php echo esc_attr(get_option('aggr_phone')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Logo Url *</th>
                        <td><input style="width: 100%" type="text" name="aggr_logo_url" value="<?php echo esc_attr(get_option('aggr_logo_url')); ?>"></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Rating * </th>
                    <td>
                        Rating * - <input type="text" size="2" name="rating_count" value="<?php echo esc_attr(get_option('rating_count')); ?>" />
                        Scale * - <input size="2" type="text" name="rating_scale" value="<?php echo esc_attr(get_option('rating_scale')); ?>" />
                        Votes * - <input size="4" type="text" name="rating_votes" value="<?php echo esc_attr(get_option('rating_votes')); ?>" /> </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Price Range</th>
                    <td>
                        <input type="text" size="15" name="price_range" value="<?php echo esc_attr(get_option('price_range')); ?>" />
                    </tr>
                </table>

                <br>
                <br>
                <strong>Pages</strong>

                <?php
$query = new WP_Query(array('post_type' => array('post', 'page'), 'posts_per_page' => -1));
		?>
                <?php
if ($query->posts) {
			foreach ($query->posts as $key => $post) {
				?>
                        <table class="form-table" style="width: 700px;">
                            <tr valign="top">
                                <th scope="row">Page slug *</th>
                                <td><input type="text" name="page_slug[]" value="<?php echo $post->post_name; ?>" />
                                    <input type="checkbox" name="ps_aggr_slug_act[<?php echo $post->post_name; ?>]" <?php echo $this->is_act_sl($post->post_name) ? 'checked' : ''; ?> /> Check to Disable
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Business / Company Name *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_company_name[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_company_name', 'aggr_company_name', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Street Address *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_street_address[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_street_address', 'aggr_street_address', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Address Locality *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_address_locality[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_address_locality', 'aggr_address_locality', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Address Region *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_address_region[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_address_region', 'aggr_address_region', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Phone *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_phone[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_phone', 'aggr_phone', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Logo Url *</th>
                                <td><input style="width: 100%" type="text" name="ps_aggr_logo_url[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_aggr_logo_url', 'aggr_logo_url', $post->post_name); ?>"></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Rating * </th>
                            <td>
                                Rating * - <input type="text" size="2" name="ps_rating_count[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_rating_count', 'rating_count', $post->post_name); ?>" />
                                Scale * - <input size="2" type="text" name="ps_rating_scale[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_rating_scale', 'rating_scale', $post->post_name); ?>" />
                                Votes * - <input size="4" type="text" name="ps_rating_votes[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_rating_votes', 'rating_votes', $post->post_name); ?>" /> </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Price Range</th>
                            <td>
                                <input type="text" size="15" name="ps_price_range[<?php echo $post->post_name; ?>]" value="<?php echo $this->get_d_val('ps_price_range', 'price_range', $post->post_name); ?>" />
                            </tr>
                        </table>
                        <?php
}
		}
		?>
                <?php submit_button();?>
            </form>
        </div>




        <?php
}

	/**
	 *
	 *
	 * @return
	 */
	public function register_settings() {
		//register our settings

		register_setting('aggregate-rating-settings-group', 'aggr_company_name');
		register_setting('aggregate-rating-settings-group', 'aggr_street_address');
		register_setting('aggregate-rating-settings-group', 'aggr_address_locality');
		register_setting('aggregate-rating-settings-group', 'aggr_address_region');
		register_setting('aggregate-rating-settings-group', 'aggr_phone');
		register_setting('aggregate-rating-settings-group', 'aggr_logo_url');
		register_setting('aggregate-rating-settings-group', 'rating_count');
		register_setting('aggregate-rating-settings-group', 'rating_scale');
		register_setting('aggregate-rating-settings-group', 'rating_votes');
		register_setting('aggregate-rating-settings-group', 'price_range');

		register_setting('aggregate-rating-settings-group', 'page_slug');

		register_setting('aggregate-rating-settings-group', 'ps_aggr_slug_act');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_company_name');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_street_address');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_address_locality');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_address_region');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_phone');
		register_setting('aggregate-rating-settings-group', 'ps_aggr_logo_url');
		register_setting('aggregate-rating-settings-group', 'ps_rating_count');
		register_setting('aggregate-rating-settings-group', 'ps_rating_scale');
		register_setting('aggregate-rating-settings-group', 'ps_rating_votes');
		register_setting('aggregate-rating-settings-group', 'ps_price_range');

	}

	/**
	 *
	 *
	 * @return
	 */
	public function register_function() {

		global $post;

		if (!$post) {
			return;
		}

		$slug = $post->post_name;

		if (!$this->is_act_sl($slug)) {

			$company_name = $this->get_d_val('ps_aggr_company_name', 'aggr_company_name', $slug);
			$street_address = $this->get_d_val('ps_aggr_street_address', 'aggr_street_address', $slug);
			$address_locality = $this->get_d_val('ps_aggr_address_locality', 'aggr_address_locality', $slug);
			$address_region = $this->get_d_val('ps_aggr_address_region', 'aggr_address_region', $slug);
			$phone = $this->get_d_val('ps_aggr_phone', 'aggr_phone', $slug);
			$logo_url = $this->get_d_val('ps_aggr_logo_url', 'aggr_logo_url', $slug);
			$rating_count = $this->get_d_val('ps_rating_count', 'rating_count', $slug);
			$rating_scale = $this->get_d_val('ps_rating_scale', 'rating_scale', $slug);
			$rating_votes = $this->get_d_val('ps_rating_votes', 'rating_votes', $slug);
			$price_range = $this->get_d_val('ps_price_range', 'price_range', $slug);

			?>
                <style>
                    #Aggregate_Rating {
                        position: absolute;
                        font-size: 0px;
                        z-index: -500000;
                    }
                    #Aggregate_Rating img {
                        width:0px;
                        height:0px;
                        opacity: 0;
                    }
                </style>
                <div id="Aggregate_Rating" itemscope itemtype="http://schema.org/LocalBusiness">
                    <?php if($logo_url){ ?>
                       <img itemprop="image" src="<?php echo $logo_url; ?>" alt="118GROUP" />
                    <?php } ?>
                    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                        Rated <span itemprop="ratingValue"><?php echo $rating_count; ?></span>/
                        <span itemprop="bestRating"><?php echo $rating_scale; ?></span>
                        based on <span itemprop="reviewCount"><?php echo $rating_votes; ?></span> customer reviews
                    </div>
                    <span itemprop="name"><?php echo $company_name; ?></span>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <span itemprop="streetAddress"><?php echo $street_address; ?></span>
                        <span itemprop="addressLocality"><?php echo $address_locality; ?></span>,
                        <span itemprop="addressRegion"><?php echo $address_region; ?></span>
                    </div>
                    Phone: <span itemprop="telephone"><?php echo $phone; ?></span>
                    <?php if ($price_range) {?>
                    <span itemprop="priceRange"><?php echo $price_range; ?></span>
                    <?php }?>
                </div>
                <?php

		}

	}
}

/**
 * Initialize the plugin
 *
 * @return \Aggregate_Rating
 */
function Aggregate_Rating() {
	return Aggregate_Rating::init();
}

// kick it off
Aggregate_Rating();
