<?php
class BSC_PPU_MediaCleaner
{
    /**
     * Clean WordPress media library by deleting attachments matching the given filename patterns.
     */
    public static function cleanMediaLibrary()
    {
        global $wpdb;

        // Patterns to match filenames
        $patterns = ['SK_*_PHOTO_*', 'HC_*_PHOTO_*', 'MK_*_PHOTO_*'];

        // Convert patterns to SQL `LIKE` statements
        $likeClauses = array_map(function ($pattern) {
            return str_replace('*', '%', $pattern); // Convert wildcard to SQL wildcard
        }, $patterns);

        // Prepare the SQL query to find matching media
        $queryPlaceholders = implode(' OR ', array_fill(0, count($likeClauses), 'guid LIKE %s'));
        $sql = "
            SELECT ID, guid
            FROM {$wpdb->prefix}posts
            WHERE post_type = 'attachment'
        ";

        $likePatterns = [
            'SK_%', // Matches SK_...
            'HC_%', // Matches HC_...
            'MK_%', // Matches MK_...
        ];


        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<h1>cleanMediaLibrary :: sql </h1>';
        echo '<br>';
        var_dump($sql);
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';

       
        // Execute the query
        $results = $wpdb->get_results($wpdb->prepare($sql, ...$likePatterns));

        echo '<br>';
        echo '<br>';
        echo '<h1>cleanMediaLibrary :: $results </h1>';
        echo '<br> ---- this is the results: ';
        var_dump($results);
        echo '<br> ----';
        echo '<br>';
 /*
        if (!empty($results)) {
            foreach ($results as $attachment) {
                // Delete the attachment and the associated file
                //wp_delete_attachment($attachment->ID, true);
                var_dump($attachment);
                echo "Deleted media: {$attachment->guid}<br>";
            }
        } else {
            echo "No matching media found.<br>";
        }
        */

        return $sql;
    }
}


?>