<?php
/**
 * Fix Duplicate Products - deletes duplicates, keeps the latest one per title
 */
define('WP_USE_THEMES', false);
require_once(__DIR__ . '/wp-load.php');

$products = get_posts([
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'orderby'        => 'ID',
    'order'          => 'ASC',
]);

echo "Total products found: " . count($products) . "\n\n";

// Group by title
$grouped = [];
foreach ($products as $p) {
    $title = trim($p->post_title);
    $grouped[$title][] = $p->ID;
}

$deleted = 0;
foreach ($grouped as $title => $ids) {
    if (count($ids) > 1) {
        // Keep the last (highest ID), delete the rest
        $keep = array_pop($ids);
        echo "Title: \"$title\"\n";
        echo "  -> Keeping ID: $keep\n";
        foreach ($ids as $dup_id) {
            wp_delete_post($dup_id, true); // true = force delete (bypass trash)
            echo "  -> Deleted duplicate ID: $dup_id\n";
            $deleted++;
        }
    } else {
        echo "Title: \"$title\" (ID: {$ids[0]}) - OK, no duplicate\n";
    }
}

echo "\nDone! Deleted $deleted duplicate(s).\n";
