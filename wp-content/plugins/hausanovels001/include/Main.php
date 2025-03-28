<?php

class Main {

    public static function showPage($content)
    {
        if (is_singular('ebook')) {

            $ebook_url = get_post_meta(get_the_ID(), '_ebook_file', true);
            $attachment_id = attachment_url_to_postid($ebook_url);
            $post = get_post(get_the_ID());

            self::updateViews($post->ID);

            $authors = get_the_terms($post->ID, 'ebook_author');
            $groups = get_the_terms($post->ID, 'ebook_group');
            $compilers = get_the_terms($post->ID, 'ebook_compiler');
            $cats = get_the_terms($post->ID, 'category');

            $download_url = "/download/{$post->ID}";
            $read_url = "/read/{$post->ID}";

    
            if ($ebook_url) {
                $file_size =  get_attachment_filesize($attachment_id);
                $file_info = pathinfo($ebook_url);
                $extension = strtoupper($file_info['extension']);
    
                $ebook_details = '<div class="ebook-details">';
                $ebook_details .= '<h3>eBook Details</h3>';
                $ebook_details .= '<p><strong>File Name:</strong> ' . $post->post_title . '</p>';
                $ebook_details .= '<p><strong>Category :</strong> ' . getTaxonomy($cats)  . '</p>';
                $ebook_details .= '<p><strong>Author: </strong> ' . getTaxonomy($authors)  . '</p>';
                $ebook_details .= '<p><strong>Group: </strong> ' . getTaxonomy($groups)  . '</p>';
                $ebook_details .= '<p><strong>Compiler: </strong> ' . getTaxonomy($compilers)  . '</p>';
                $ebook_details .= '<p><strong>File Type:</strong> ' . $extension . '</p>';
                $ebook_details .= '<p><strong>File Size:</strong> ' . $file_size . '</p>';
                $ebook_details .= '<p><strong>Views: </strong> ' . $post->views . ' </p>';
                $ebook_details .= '<p><strong>Downloads: </strong> ' . $post->download . ' </p>';
                $ebook_details .= '<p><strong>Date: </strong> ' . date('D m, Y', strtotime($post->post_date)) . ' </p>';
                $ebook_details .= '<a href="' . esc_url($download_url) . '" class="button" download>Download eBook</a>';
                $ebook_details .= '<a href="' . esc_url($read_url) . '" class="button" style="margin-left:10px;">Read</a>';
                $ebook_details .= '</div>';
    
                return $content . $ebook_details;
            } else if ($post->old == 1) {


                
                $ebook_details = '<img src="' . WP_CONTENT_URL . '/uploads/450x650' . $post->img_folder . '/' . $post->image . '" width="300"/>';
                $ebook_details .= '<div class="ebook-details">';
                $ebook_details .= '<h3>eBook Details</h3>';
                $ebook_details .= '<p><strong>File Name:</strong> ' . $post->post_title . '</p>';
                $ebook_details .= '<p><strong>Category :</strong> ' . getTaxonomy($cats)  . '</p>';
                $ebook_details .= '<p><strong>Author: </strong> ' . getTaxonomy($authors)  . '</p>';
                $ebook_details .= '<p><strong>Group: </strong> ' . getTaxonomy($groups)  . '</p>';
                $ebook_details .= '<p><strong>Compiler: </strong> ' . getTaxonomy($compilers)  . '</p>';
                $ebook_details .= '<p><strong>Phone Number: </strong> ' . $post->phone . '</p>';
                $ebook_details .= '<p><strong>File Size: </strong> ' . formatSize($post->size) . ' </p>';
                $ebook_details .= '<p><strong>Views: </strong> ' . $post->views . ' </p>';
                $ebook_details .= '<p><strong>Downloads: </strong> ' . $post->download . ' </p>';
                $ebook_details .= '<p><strong>Date: </strong> ' . date('D m, Y', strtotime($post->post_date)) . ' </p>';
                $ebook_details .= '<a href="' . esc_url($download_url) . '" class="button" download>Download eBook</a>';
                $ebook_details .= '<a href="' . esc_url($read_url) . '" class="button" style="margin-left:10px;">Read</a>';
                $ebook_details .= '</div>';
    
                return $content . $ebook_details;
            }
        }
        return $content;
    }



    public static function updateViews($id)
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->posts} SET views = views + 1 WHERE ID = %d",
                $id
            )
            );
    }

    public static function updateDownloads($id)
    {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "UPDATE {$wpdb->posts} SET download = download + 1 WHERE ID = %d",
                $id
            )
            );
    }
}