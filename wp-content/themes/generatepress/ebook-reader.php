<?php
// Get the eBook ID from the URL
$ebook_id = get_query_var('read_ebook');

if (!$ebook_id || !is_numeric($ebook_id)) {
    wp_die('Invalid eBook ID');
}

// Extract the text from the attachment
$text = ReadPost($ebook_id);
if (!$text) {
    wp_die('No readable content found.');
}

// Paginate the extracted text
$page = isset($_GET['chapter']) ? intval($_GET['chapter']) : 1;
$pagination = paginateText($text, $page);
$content = $pagination['content'];
$total_pages = $pagination['total_pages'];

get_header();
?>

<div class="ebook-reader">
    <h2><?php echo get_the_title($ebook_id); ?></h2>
    <div class="ebook-content"><?php echo $content; ?></div>

    <div class="pagination">
    <?php
    $visible_pages = 5; // Number of visible pages before showing "..."
    $start = max(1, $page - floor($visible_pages / 2));
    $end = min($total_pages, $start + $visible_pages - 1);

    if ($page > 1) {
        echo '<a href="' . home_url("/read/$ebook_id?chapter=" . ($page - 1)) . '">‹ Prev</a>';
    }

    if ($start > 1) {
        echo '<a href="' . home_url("/read/$ebook_id?chapter=1") . '">1</a>';
        if ($start > 2) echo '<span class="dots">...</span>';
    }

    for ($i = $start; $i <= $end; $i++) {
        $active_class = ($i == $page) ? 'active' : '';
        echo '<a href="' . home_url("/read/$ebook_id?chapter=$i") . '" class="' . $active_class . '">' . $i . '</a>';
    }

    if ($end < $total_pages) {
        if ($end < $total_pages - 1) echo '<span class="dots">...</span>';
        echo '<a href="' . home_url("/read/$ebook_id?chapter=$total_pages") . '">' . $total_pages . '</a>';
    }

    if ($page < $total_pages) {
        echo '<a href="' . home_url("/read/$ebook_id?chapter=" . ($page + 1)) . '">Next ›</a>';
    }
    ?>
</div>

</div>

<style>
    .ebook-reader { max-width: 600px; margin: auto; text-align: justify; }
    .pagination { margin-top: 10px; text-align: center; }
    .pagination a { padding: 5px 10px; text-decoration: none; }
    .pagination .active { font-weight: bold; }
    .pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a, .pagination .dots {
    padding: 8px 12px;
    margin: 0 4px;
    text-decoration: none;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    background: #f8f8f8;
}

.pagination a:hover {
    background: #ddd;
}

.pagination .active {
    font-weight: bold;
    background:rgb(1, 67, 110);
    color: #fff;
}

.pagination .dots {
    background: transparent;
    border: none;
    color: #666;
    font-weight: bold;
}

</style>

<?php
get_footer();
?>
