<?php
class BSC_PPU_MediaManager
{
    public function uploadImage($filePath)
    {
        $filetype = wp_check_filetype(basename($filePath), null);
        $attachment = [
            'guid'           => $filePath,
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name(basename($filePath)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attachmentId = wp_insert_attachment($attachment, $filePath);

        if (!is_wp_error($attachmentId)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachmentData = wp_generate_attachment_metadata($attachmentId, $filePath);
            wp_update_attachment_metadata($attachmentId, $attachmentData);
            return $attachmentId;
        }

        return null;
    }
}

?>