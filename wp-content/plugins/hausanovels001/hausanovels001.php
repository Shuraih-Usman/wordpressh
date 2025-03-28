<?php
/*
Plugin Name: Hausa Novels 001
Plugin URI: https://hausanovels001.com.ng
Description: A plugin to add posts using my old script form.
Version: 1.0
Author: Shuraihu Usman
Author URI: https://hausanovels001.com.ng
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
require_once __DIR__ . '/../../../vendor/autoload.php';
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

include(plugin_dir_path(__FILE__) . 'post-form.php');
include(plugin_dir_path(__FILE__) . 'include/File.php');
include(plugin_dir_path(__FILE__) . 'include/Main.php');


function register_ebook_taxonomies()
{

    register_taxonomy('ebook_author', 'ebook', array(
        'label' => __('Authors', 'textdomain'),
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'ebook-author'),
        'public' => true,
        'has_archive' => true
    ));

    register_taxonomy('ebook_compiler', 'ebook', array(
        'label' => __('Compilers', 'textdomain'),
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'compiler'),
        'public' => true,
        'has_archive' => true
    ));

    register_taxonomy('ebook_group', 'ebook', array(
        'label' => __('Groups', 'textdomain'),
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'group'),
        'public' => true,
        'has_archive' => true
    ));
}
add_action('init', 'register_ebook_taxonomies');


function register_ebook_post_type()
{
    $args = array(
        'label'  => __('eBooks', 'textdomain'),
        'public' => true,
        'show_in_menu' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'comments'),
        'taxonomies' => array('category'),
        'rewrite' => array(
            'slug'       => '/', 
            'with_front' => false
        ),
        'query_var' => true,
    );
    register_post_type('ebook', $args);
}
add_action('init', 'register_ebook_post_type');

function custom_pagination_rewrite_rules() {
    add_rewrite_rule('^page/([0-9]+)/?$', 'index.php?paged=$matches[1]', 'top');
}
add_action('init', 'custom_pagination_rewrite_rules');








function ebook_add_meta_box()
{
    add_meta_box(
        'ebook_file_meta',
        __('Upload eBook File', 'textdomain'),
        'ebook_file_meta_callback',
        'ebook',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'ebook_add_meta_box');

function ebook_file_meta_callback($post)
{
    File::fileMeta($post);
}



function ebook_save_meta($post_id)
{
    File::saveFileMeta($post_id);
}
add_action('save_post', 'ebook_save_meta');




function include_ebooks_in_main_query($query)
{
    if (!is_admin() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'ebook'));
    }
}
add_action('pre_get_posts', 'include_ebooks_in_main_query');

function allow_ebook_file_types($mimes)
{
    $mimes['txt']  = 'text/plain';
    $mimes['doc']  = 'application/msword';
    $mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    $mimes['pdf']  = 'application/pdf';
    return $mimes;
}
add_filter('upload_mimes', 'allow_ebook_file_types');


function em_display_ebook_details($content)
{
    return Main::showPage($content);
}
add_filter('the_content', 'em_display_ebook_details');



// get Author 
function getTaxonomy($terms)
{
    // Check if $terms is a WP_Error before processing
    if (is_wp_error($terms)) {
        return 'Error retrieving authors'; // Handle the error gracefully
    }

    if ($terms && is_array($terms)) {
        $author_links = array();

        foreach ($terms as $term) {
            $author_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
        }

        return implode(', ', $author_links);
    }

    return 'Unknown'; // Fallback message if no terms exist
}






function add_featured_image_url_meta_box() {
    add_meta_box(
        'featured_image_url_meta_box',
        __('Featured Image URL', 'textdomain'),
        'render_featured_image_url_meta_box',
        'ebook', // Change this to your post type
        'side',
        'low'
    );
}
function override_featured_image($html, $post_id, $post_thumbnail_id, $size, $attr) {
    $post = get_post($post_id);
    if (!has_post_thumbnail($post_id) && $post->img_folder && $post->image) {
    $custom_image_url = WP_CONTENT_URL . '/uploads/450x650'.$post->img_folder.'/'.$post->image;
    
    return '<img src="' . esc_url($custom_image_url) . '" class="ebook-thumbnail" alt="Custom Ebook Image">';
    }

    return $html;
}
add_filter('post_thumbnail_html', 'override_featured_image', 10, 5);

function get_attachment_filesize($attachment_id)
{
    $file_path = get_attached_file($attachment_id); // Get file path
    if (!$file_path || !file_exists($file_path)) {
        return 'File not found';
    }

    $filesize = filesize($file_path); // Get file size in bytes
    return format_size_units($filesize);
}

function format_size_units($bytes)
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}


function formatSize($size, $type = false, $round = 2)
{
    $sizes = array('BYTES', 'KB', 'MB', 'GB', 'TB');
    $total = count($sizes) - 1;
    for ($i = 0; $size > 1024 && $i < $total; $i++)
        $size /= 1024;
    if ($type == true)
        return array(round($size, $round), $sizes[$i]);
    return round($size, $round) . ' ' . $sizes[$i];
}


/**
 * Register custom rewrite rule.
 */
function custom_download_rewrite_rule()
{
    add_rewrite_rule('^download/([0-9]+)/?$', 'index.php?ebook_id=$matches[1]', 'top');
}
add_action('init', 'custom_download_rewrite_rule');

/**
 * Add custom query variable.
 */
function custom_query_vars($vars)
{
    $vars[] = 'paged';
    $vars[] = 'ebook_id';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

/**
 * Handle the ebook download.
 */
function custom_template_redirect()
{
    $ebook_id = get_query_var('ebook_id');
    if ($ebook_id) {
        Download($ebook_id);
        exit;
    }
}
add_action('template_redirect', 'custom_template_redirect');

/**
 * Flush rewrite rules on activation.
 */
function flush_custom_rewrite_rules()
{
    custom_download_rewrite_rule();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_custom_rewrite_rules');

/**
 * Flush rewrite rules on deactivation.
 */
function remove_custom_rewrite_rules()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'remove_custom_rewrite_rules');

add_action('init', function () {
    if (get_query_var('ebook_id')) {
        ob_clean();
        flush();
    }
});



/**
 * Ebook download function 2.
 */
function Download($attachment_id)
{

    $post = get_post($attachment_id);

    if($post->old == 1) {
        File::Download2($attachment_id);
    } else {
        File::Download($attachment_id);
    }
      
}

include(plugin_dir_path(__FILE__) . 'include/read.php');


