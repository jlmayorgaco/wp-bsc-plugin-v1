<?php

class BSC_WC_Filter_Form_Renderer {
    public function render($self, $title){
        ?>
        <p>
            <label for="<?php echo $self->get_field_id('title'); ?>">
                <?php _e('Title:', 'textdomain'); ?>
            </label>
            <input class="widefat" id="<?php echo $self->get_field_id('title'); ?>" name="<?php echo $self->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }
}