<?php
/**
 * GoFood Theme Helpers Functions.
 *
 * @package goFood
 */

if ( ! function_exists( 'gf_get_my_account_url' ) ) {
	/**
	 * Retrieves the URL of the "My Account" page.
	 *
	 * @return string The permalink of the "My Account" page.
	 */
	function gf_get_my_account_url() {
		return get_the_permalink( get_option( 'gofood_myaccount_page_id' ) );
	}
}

if ( ! function_exists( 'gf_if_my_account_page' ) ) {
	/**
	 * Checks if the current page is the "My Account" page.
	 *
	 * @return bool True if the current page is the "My Account" page, false otherwise.
	 */
	function gf_if_my_account_page() {
		return is_page( get_option( 'gofood_myaccount_page_id' ) );
	}
}

if ( ! function_exists( 'gf_get_checkout_url' ) ) {
	/**
	 * Retrieves the URL of the "Checkout" page.
	 *
	 * @return string The permalink of the "Checkout" page.
	 */
	function gf_get_checkout_url() {
		return get_the_permalink( get_option( 'gofood_checkout_page_id' ) );
	}
}

if ( ! function_exists( 'gf_if_checkout_page' ) ) {
	/**
	 * Checks if the current page is the "Checkout" page.
	 *
	 * @return bool True if the current page is the "Checkout" page, false otherwise.
	 */
	function gf_if_checkout_page() {
		return is_page( get_option( 'gofood_checkout_page_id' ) );
	}
}


if ( ! function_exists( 'gf_price' ) ) {
	/**
	 * Formats a given price into a string with HTML structure for displaying.
	 *
	 * The formatted price includes a dollar currency symbol and ensures two decimal places.
	 *
	 * @param float $price The price to be formatted.
	 * @param float $discounted_price The discounted price to be formatted.
	 * @return string The formatted price as an HTML string.
	 */
	function gf_price( $price, $discounted_price = 0 ) {

		if ( $discounted_price ) {
			return '<span class="price"><del aria-hidden="true"><span class="amount">' . gf_format_price( $price ) . '</span></del> <ins aria-hidden="true"><span class="gofood-Price-amount amount"><bdi><span class="gofood-Price-currencySymbol">$</span>' . number_format( $discounted_price, 2 ) . '</bdi></span></ins></span>';
		}

		return '<span class="gofood-Price-amount amount">' . gf_format_price( $price ) . '</span>';
	}
}

if ( ! function_exists( 'gf_format_price' ) ) {
	/**
	 * Formats a given price into a string with HTML structure for displaying.
	 *
	 * The formatted price includes a dollar currency symbol and ensures two decimal places.
	 *
	 * @param float $price The price to be formatted.
	 * @return string The formatted price as an HTML string.
	 */
	function gf_format_price( $price ) {
		return '<bdi><span class="gofood-Price-currencySymbol">$</span>' . number_format( $price, 2 ) . '</bdi>';
	}
}

if ( ! function_exists( 'gf_partials_path' ) ) {
	/**
	 * Retrieves the full path of a partial in the public directory.
	 *
	 * @param string $partial The name of the partial.
	 * @param bool   $is_public Whether the partial is in the public directory or not.
	 * @param bool   $full Whether to return the full path or not.
	 * @return string The full path of the partial.
	 */
	function gf_partials_path( $partial, $is_public = true, $full = false ) {
		$path = '/app/' . ( $is_public ? 'public' : 'admin' ) . '/partials/' . ltrim( $partial, '/' );

		if ( $full ) {
			return get_template_directory() . $path . '.php';
		}

		return $path;
	}
}

if ( ! function_exists( 'gf_get_product_categories' ) ) {
	/**
	 * Retrieves all terms from the 'product_category' taxonomy, including their title, slug, and associated image URL.
	 *
	 * @param bool $hide_empty Whether to hide empty terms or not.
	 * @param bool $with_image Whether to include the image URL or not.
	 * @return array An array of associative arrays containing 'title', 'slug', and 'image_url' for each term.
	 */
	function gf_get_product_categories( $hide_empty = false, $with_image = false ) {
		// Fetch all terms from the 'product_category' taxonomy.
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_category',
				'hide_empty' => $hide_empty,
			)
		);

		// Initialize an array to hold the category data.
		$categories = array();

		// Check for errors or empty result.
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $categories;
		}

		// Loop through each term to retrieve necessary data.
		foreach ( $terms as $term ) {

			$category = array(
				'id'    => $term->term_id,
				'title' => $term->name,
				'slug'  => $term->slug,
			);

			if ( $with_image ) {
				// Retrieve the image ID from the ACF field.
				$image_id = get_term_meta( $term->term_id, 'product_category_image_id', true );

				// Get the image URL; adjust the size as needed (e.g., 'thumbnail', 'medium', 'large').
				$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';

				// Add the image URL to the category data.
				$category['image_url'] = $image_url;
			}

			// Append the term data to the categories array.
			$categories[] = $category;
		}

		return $categories;
	}
}

if ( ! function_exists( 'gf_get_category_url' ) ) {
	/**
	 * Retrieves the URL for a given term ID, adding a query argument to filter by that category.
	 *
	 * @param int    $term_id The term ID to generate a URL for.
	 * @param string $fragment The fragment to append to the URL.
	 * @return string The URL with the query argument 'gf_filter_category_id' set to the given term ID.
	 */
	function gf_get_category_url( $term_id, $fragment = '' ) {
		global $wp;
		$current_slug = add_query_arg( array( 'gf_filter_category_id' => $term_id ), $wp->request );

		return home_url( $current_slug . '#' . ltrim( $fragment, '#' ) );
	}
}


if ( ! function_exists( 'gf_get_discount_percentage' ) ) {
	/**
	 * Calculates the percentage discount between a price and its discounted value.
	 *
	 * @param float $price The original price.
	 * @param float $discounted_price The discounted price.
	 * @return int The percentage discount, rounded to the nearest whole number.
	 */
	function gf_get_discount_percentage( $price, $discounted_price ) {
		if ( empty( $discounted_price ) ) {
			return 0;
		}

		$price = $price ? $price : 0;

		return round( ( $price - $discounted_price ) / $price * 100 );
	}
}


if ( ! function_exists( 'gf_get_paginate_links' ) ) {
	/**
	 * Generates pagination links with customizable HTML classes.
	 *
	 * The function takes the same arguments as `paginate_links` and returns an array of links.
	 * The links are then converted to an unordered list with the given classes.
	 *
	 * Note that the 'type' argument is automatically set to 'array' to retrieve an array of links.
	 *
	 * @param array $args      Arguments passed to `paginate_links`.
	 * @param array $classes   Classes to apply to the pagination links.
	 *                          The keys should match the following elements:
	 *                          - 'ul'       : The unordered list element.
	 *                          - 'li'       : The list item element.
	 *                          - 'a'        : The link element.
	 *                          - 'span'     : The span element (for 'current' links).
	 *                          - 'active'   : The class to apply to the active link.
	 *                          - 'disabled' : The class to apply to disabled links.
	 * @return string The pagination HTML.
	 */
	function gf_get_paginate_links( $args = array(), $classes = array() ) {

		// Default classes.
		$default_classes = array(
			'ul'     => 'pagination',
			'li'     => 'page-item',
			'a'      => 'page-link',
			'span'   => 'page-link',
			'active' => 'active',
		);

		// Merge default and custom classes.
		$classes = array_merge( $default_classes, $classes );

		// Ensure 'type' is 'array' to get an array of links.
		$args['type'] = 'array';

		// Generate pagination links.
		$links = paginate_links( $args );

		if ( empty( $links ) ) {
			return '';
		}

		// Start building the pagination HTML.
		$pagination = '<ul class="' . esc_attr( $classes['ul'] ) . '">';

		foreach ( $links as $link ) {
			// Initialize class array for <li>.
			$li_classes = array( $classes['li'] );

			// Determine if the link is active.
			if ( strpos( $link, 'current' ) !== false ) {
				$li_classes[] = $classes['active'];
			}

			// Build the <li> element.
			$pagination .= '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';

			// Modify the inner <a> or <span> classes.
			$link = preg_replace(
				'/class="([^"]*)page-numbers([^"]*)"/',
				'class="' . esc_attr( $classes['a'] ) . ' $1$2"',
				$link
			);

			$pagination .= $link;
			$pagination .= '</li>';
		}

		$pagination .= '</ul>';

		return $pagination;
	}
}


if ( ! function_exists( 'gf_get_product_snapshot' ) ) {
	/**
	 * Retrieves a snapshot of a product.
	 *
	 * @param int $product_id The ID of the product.
	 *
	 * @return array|null {
	 *     The product data array.
	 *
	 *     @type int          $id               The product ID.
	 *     @type string       $title            The product title.
	 *     @type string       $permalink        The product permalink.
	 *     @type string       $image            The product image URL (medium size).
	 *     @type float        $price            The product price.
	 *     @type float|null   $discounted_price The discounted price (if applicable).
	 *     @type int|null     $stock_quantity   The stock quantity (if applicable).
	 * }
	 */
	function gf_get_product_snapshot( $product_id ) {
		// Validate the product ID.
		$product_id = absint( $product_id );
		if ( ! $product_id || get_post_type( $product_id ) !== 'product' ) {
			return null;
		}

		// Retrieve the post object.
		$post = get_post( $product_id );
		if ( ! $post || 'publish' !== $post->post_status ) {
			return null;
		}

		// Retrieve custom fields.
		$price            = get_post_meta( $product_id, '_product_price', true );
		$discounted_price = get_post_meta( $product_id, '_product_discount_price', true );
		$stock_quantity   = get_post_meta( $product_id, '_product_stock_quantity', true );

		// Retrieve the product image URL.
		$image_url = get_the_post_thumbnail_url( $product_id, 'medium' );

		// Construct the product data array.
		$product_data = array(
			'id'               => $product_id,
			'title'            => get_the_title( $post ),
			'permalink'        => get_permalink( $post ),
			'image'            => $image_url ? esc_url( $image_url ) : '',
			'price'            => $price ? floatval( $price ) : 0.0,
			'discounted_price' => ! empty( $discounted_price ) ? floatval( $discounted_price ) : null,
			'stock_quantity'   => ! empty( $stock_quantity ) ? intval( $stock_quantity ) : 0,
		);

		return $product_data;
	}
}


if ( ! function_exists( 'format_hero_title' ) ) {
	/**
	 * Formats a hero title to wrap the first 2 or 3 words in a <span> with a class of "text-dark-red".
	 *
	 * @param string $title The title to format.
	 *
	 * @return string The formatted title.
	 */
	function format_hero_title( $title ) {
		// Split the title into words.
		$words = explode( ' ', $title );

		// Determine how many words to wrap (first 2 or 3 words).
		$wrap_count = ( count( $words ) >= 3 ) ? 3 : 2;

		// Extract the words to wrap.
		$wrapped_words   = array_slice( $words, 0, $wrap_count );
		$remaining_words = array_slice( $words, $wrap_count );

		// Create the formatted title.
		$formatted = '<span class="text-dark-red">' . implode( ' ', $wrapped_words ) . '</span>';

		// Add line break after first wrapped word if we have 3+ words.
		if ( $wrap_count >= 2 && count( $words ) > 2 ) {
			$formatted       = '<span class="text-dark-red">' . $words[0] . ' ' . $words[1] . ' <br> ' . $words[2] . '</span>';
			$remaining_words = array_slice( $words, 3 );
		}

		// Combine with remaining words.
		if ( ! empty( $remaining_words ) ) {
			$formatted .= ' ' . implode( ' ', $remaining_words );
		}

		return $formatted;
	}
}
