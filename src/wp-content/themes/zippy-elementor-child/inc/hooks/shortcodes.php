<?php

/**
 * [product_carousel] shortcode
 *
 * Renders a 3-slide Slick carousel with a synced thumbnail strip.
 * The centre (active) slide is larger; flanking slides are dimmed.
 * Powered by Slick carousel — infinite loop, rapid-click safe.
 *
 * Attributes:
 *   category  — WooCommerce product category slug (optional)
 *   limit     — number of products to show (default: 5)
 *   orderby   — date | title | rand | menu_order (default: menu_order)
 *   autoplay  — true | false (default: false)
 *
 * Usage:
 *   [product_carousel]
 *   [product_carousel category="accessories" limit="5"]
 *   [product_carousel category="clothing" limit="8" orderby="date"]
 *   [product_carousel autoplay="true"]
 */

if (!function_exists('zippy_product_carousel_shortcode')) {

  function zippy_product_carousel_shortcode($atts)
  {
    if (!class_exists('WooCommerce')) {
      return '';
    }

    $atts = shortcode_atts(
      [
        'category' => '',
        'limit'    => 10,
        'orderby'  => 'menu_order',
        'autoplay' => 'false',
      ],
      $atts,
      'product_carousel'
    );

    $limit    = max(1, (int) $atts['limit']);
    $orderby  = in_array($atts['orderby'], ['date', 'title', 'rand', 'menu_order'], true)
      ? $atts['orderby'] : 'menu_order';
    $autoplay = $atts['autoplay'] === 'true' ? 'true' : 'false';

    // ----------------------------------------------------------------
    // Query products
    // ----------------------------------------------------------------
    $query_args = [
      'post_type'      => 'product',
      'post_status'    => 'publish',
      'posts_per_page' => $limit,
      'orderby'        => $orderby,
      'order'          => 'ASC',
    ];

    if (!empty($atts['category'])) {
      $query_args['tax_query'] = [[
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => sanitize_text_field($atts['category']),
      ]];
    }

    $products = new WP_Query($query_args);
    if (!$products->have_posts()) return '';

    $items = [];
    while ($products->have_posts()) {
      $products->the_post();
      $product = wc_get_product(get_the_ID());
      if (!$product) continue;

      $slide_src = has_post_thumbnail()
        ? get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_single')
        : wc_placeholder_img_src('woocommerce_single');

      $thumb_src = has_post_thumbnail()
        ? get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_thumbnail')
        : wc_placeholder_img_src('woocommerce_thumbnail');

      $items[] = [
        'name'       => get_the_title(),
        'price_html' => $product->get_price_html(),
        'link'       => get_permalink(),
        'slide_src'  => esc_url($slide_src),
        'thumb_src'  => esc_url($thumb_src),
      ];
    }
    wp_reset_postdata();

    if (empty($items)) return '';

    // Unique ID so multiple carousels on the same page don't clash
    static $instance = 0;
    $instance++;
    $uid = 'pc-' . $instance;

    ob_start();
    ?>
    <div class="product-carousel" data-autoplay="<?php echo esc_attr($autoplay); ?>">

      <?php /* ---- Main slide track ---- */ ?>
      <div class="product-carousel__main" id="<?php echo esc_attr($uid . '-main'); ?>">
        <?php foreach ($items as $item) : ?>
          <div class="product-carousel__slide">
            <div class="product-carousel__img-wrap">
              <img
                src="<?php echo esc_url($item['slide_src']); ?>"
                alt="<?php echo esc_attr($item['name']); ?>"
                loading="lazy"
              />
            </div>
            <div class="product-carousel__slide-info">
              <a class="product-carousel__slide-link" href="<?php echo esc_url($item['link']); ?>">
                <h3 class="product-carousel__slide-name">
                  <?php echo esc_html($item['name']); ?>
                </h3>
                <div class="product-carousel__slide-price">
                  <?php echo wp_kses_post($item['price_html']); ?>
                </div>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div><!-- /.product-carousel__main -->

      <?php /* ---- Thumbnail nav strip ---- */ ?>
      <div class="product-carousel__thumbs" id="<?php echo esc_attr($uid . '-thumbs'); ?>">
        <?php foreach ($items as $item) : ?>
          <div class="product-carousel__thumb">
            <div class="product-carousel__thumb-image-wrap">
              <img
                src="<?php echo esc_url($item['thumb_src']); ?>"
                alt="<?php echo esc_attr($item['name']); ?>"
                loading="lazy"
              />
            </div>
            <span class="product-carousel__thumb-name">
              <?php echo esc_html($item['name']); ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div><!-- /.product-carousel__thumbs -->

    </div><!-- /.product-carousel -->
    <?php
    return ob_get_clean();
  }

  add_shortcode('product_carousel', 'zippy_product_carousel_shortcode');
}
