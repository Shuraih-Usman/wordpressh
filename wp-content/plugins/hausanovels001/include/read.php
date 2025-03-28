<?php

function custom_read_rewrite_rule() {
    add_rewrite_rule('^read/([0-9]+)/?$', 'index.php?read_ebook=$matches[1]', 'top');
}
add_action('init', 'custom_read_rewrite_rule');


function custom_read_query_vars($vars) {
    $vars[] = 'read_ebook';
    return $vars;
}
add_filter('query_vars', 'custom_read_query_vars');


function custom_read_template_redirect() {
    $ebook_id = get_query_var('read_ebook');
    if ($ebook_id) {
        include get_template_directory() . '/ebook-reader.php';
        exit;
    }
}
add_action('template_redirect', 'custom_read_template_redirect');





function extractEbookText($post_id)
{
    $attachment_id = File::getAttachmentId($post_id);
    if (!$attachment_id) return false;

    $file_path = get_attached_file($attachment_id);
    if (!file_exists($file_path)) return false;

    $mime_type = mime_content_type($file_path);
    $text = '';

    if ($mime_type === 'application/pdf') {
        $parser = new Parser();
        $pdf = $parser->parseFile($file_path);
        $text = $pdf->getText();
    } elseif (in_array($mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
        $phpWord = IOFactory::load($file_path);
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }
    } elseif ($mime_type === 'text/plain') {
        $text = file_get_contents($file_path);
    }

    return $text;
}

function extractEbookText2($post_id)
{
    $post = get_post($post_id);

    $file_path = WP_CONTENT_DIR . "/uploads/files/{$post->file_dir}/{$post->file_name}";
    if (!file_exists($file_path)) return false;

    $mime_type = mime_content_type($file_path);
    $text = '';

    if ($mime_type === 'application/pdf') {
        $parser = new Parser();
        $pdf = $parser->parseFile($file_path);
        $text = $pdf->getText();
    } elseif (in_array($mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
        $phpWord = IOFactory::load($file_path);
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }
    } elseif ($mime_type === 'text/plain') {
        $text = file_get_contents($file_path);
    }

    return $text;
}


function paginateText($text, $page = 1, $per_page = 2000)
{
    $words = explode(' ', $text);
    $total_pages = ceil(count($words) / $per_page);
    
    $start = ($page - 1) * $per_page;
    $paginated_text = implode(' ', array_slice($words, $start, $per_page));

    return [
        'content' => nl2br($paginated_text),
        'total_pages' => $total_pages
    ];
}


function ReadPost($post_id) {
    $post = get_post($post_id);
    Main::updateViews($post_id);
    
    if($post->old == 1) {
        return extractEbookText2($post_id);
    } else {

        return extractEbookText($post_id);
    }
}

