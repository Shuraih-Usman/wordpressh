<?php

class File
{

    public static function fileMeta($post)
    {
        $file_url = get_post_meta($post->ID, '_ebook_file', true);
        wp_nonce_field('ebook_file_nonce', 'ebook_file_nonce_field'); // Security nonce
?>

        <div>
            <input type="hidden" id="ebook_file" name="ebook_file" value="<?php echo esc_attr($file_url); ?>" />
            <button type="button" class="button button-primary" id="ebook_upload_button">Select eBook</button>

            <div id="ebook_file_preview">
                <?php if ($file_url): ?>
                    <p><strong>Selected File:</strong>
                        <a href="<?php echo esc_url($file_url); ?>" target="_blank">Download eBook</a>
                    </p>
                    <button type="button" class="button button-secondary" id="remove_ebook_button">Remove eBook</button>
                <?php endif; ?>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                let file_frame;

                $('#ebook_upload_button').on('click', function(e) {
                    e.preventDefault();

                    // If file frame already exists, reopen it
                    if (file_frame) {
                        file_frame.open();
                        return;
                    }

                    // Initialize WordPress media uploader
                    file_frame = wp.media({
                        title: 'Select or Upload an eBook',
                        button: {
                            text: 'Use this file'
                        },
                        library: {
                            type: ['text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf']
                        },
                        multiple: false
                    });

                    // When a file is selected, update the input field and preview
                    file_frame.on('select', function() {
                        let attachment = file_frame.state().get('selection').first().toJSON();

                        // Update the input field with the file URL
                        $('#ebook_file').val(attachment.url);

                        // Show file details dynamically
                        $('#ebook_file_preview').html(`
                    <p><strong>Selected File:</strong> 
                        <a href="${attachment.url}" target="_blank">${attachment.filename}</a>
                    </p>
                    <button type="button" class="button button-secondary" id="remove_ebook_button">Remove eBook</button>
                `);

                        // Bind remove button again
                        $('#remove_ebook_button').on('click', function() {
                            $('#ebook_file').val('');
                            $('#ebook_file_preview').html('');
                        });
                    });

                    file_frame.open();
                });

                // Ensure the remove button works correctly
                $(document).on('click', '#remove_ebook_button', function() {
                    $('#ebook_file').val('');
                    $('#ebook_file_preview').html('');
                });

            });
        </script>

<?php
    }


    public static function saveFileMeta($post_id)
    {
        // Security check
        if (!isset($_POST['ebook_file_nonce_field']) || !wp_verify_nonce($_POST['ebook_file_nonce_field'], 'ebook_file_nonce')) {
            return;
        }

        // Save the selected file URL
        if (isset($_POST['ebook_file'])) {
            update_post_meta($post_id, '_ebook_file', esc_url_raw($_POST['ebook_file']));
        }
    }


    public static function Download2($attachment_id)
    {
        if ($attachment_id && is_numeric($attachment_id)) {
            $post = get_post($attachment_id);

            if (!$post) {
                wp_die('Invalid ebook ID.');
            }

            Main::updateDownloads($attachment_id);

            $file_path = WP_CONTENT_DIR . "/uploads/files/{$post->file_dir}/{$post->file_name}";
            $file_url  = content_url("/uploads/files/{$post->file_dir}/{$post->file_name}");

            error_log('Attempting to download: ' . $file_path);

            if (!file_exists($file_path) || !is_readable($file_path)) {
                wp_die('File not found: ' . $file_path);
            }

            $file_info = pathinfo($file_path);
            $extension = strtoupper($file_info['extension']);
            $filename = $post->post_title . ' by HausaNovels001.com.ng.' . $extension;

            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
            header("Content-Transfer-Encoding: binary");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
            header("Content-Length: " . filesize($file_path));
            flush();
            readfile($file_path);
            exit;
        }
    }

    public static function Download($attachment_id)
    {



        if ($attachment_id && is_numeric($attachment_id)) {


            Main::updateDownloads($attachment_id);

            $file_id = self::getAttachmentId($attachment_id);
            $file_path = get_attached_file($file_id);

            if (file_exists($file_path)) {
                //download
                $file = get_post($attachment_id);
                $name = $file->post_title;
                $sitename = "HausaNovels001.com.ng";

                $attachment_url = wp_get_attachment_url($file_id);
                $file_info = pathinfo($attachment_url);
                $extension = strtoupper($file_info['extension']);

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $name . ' by ' . $sitename . '.' . $extension . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                ob_clean();
                flush();
                readfile($file_path);
            } else {
                wp_die('File not found.');
            }
        }
    }


    /**
     * Get the attachment ID for a given post.
     */
    public static function getAttachmentId($post_id)
    {
        $attachments = get_posts([
            'post_parent'    => $post_id,
            'post_type'      => 'attachment',
            'post_mime_type' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'],
            'numberposts'    => 1,
            'fields'         => 'ids',
        ]);

        return $attachments ? $attachments[0] : false;
    }
}
