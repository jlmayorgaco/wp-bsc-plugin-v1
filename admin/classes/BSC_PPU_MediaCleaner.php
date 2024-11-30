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
            AND (guid LIKE %s OR guid LIKE %s OR guid LIKE %s)
        ";
        $likePatterns = [
            '%SK\_%\_PHOTO\_%', // Matches SK_<number>_PHOTO_<number>
            '%HC\_%\_PHOTO\_%', // Matches HC_<number>_PHOTO_<number>
            '%MK\_%\_PHOTO\_%', // Matches MK_<number>_PHOTO_<number>
        ];

        // Execute the query
        $results = $wpdb->get_results($wpdb->prepare($sql, ...$likePatterns));

        if (!empty($results)) {
            foreach ($results as $attachment) {
                // Delete the attachment and the associated file
                wp_delete_attachment($attachment->ID, true);
            }
        } else {
            echo "No matching media found.<br>";
        }
  
        return $sql;
    }

     /**
     * Clean WordPress media library by deleting attachments without files.
     */
    public static function cleanIncompleteMedia()
    {
        global $wpdb;

        // SQL query to find attachments without files
        $sql = "
            SELECT ID, guid
            FROM {$wpdb->prefix}posts
            WHERE post_type = 'attachment'
              AND (guid LIKE %s AND post_title = '')
        ";

        // Pattern to match URLs that end with `/`
        $likePattern = '%/';

        // Execute the query
        $results = $wpdb->get_results($wpdb->prepare($sql, $likePattern));

        var_dump($results);

        if (!empty($results)) {
            foreach ($results as $attachment) {
                // Delete the attachment (doesn't delete any associated file since there isn't one)
                //wp_delete_attachment($attachment->ID, true);
                echo "Deleted incomplete media: {$attachment->guid}<br>";
            }
        } else {
            echo "No incomplete media found.<br>";
        }

        return $sql;
    }
}


?>