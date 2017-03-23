<?php

/**
 * Custom customizer control to extend select boxes w/ multiselect option
 * @since 1.2.0
 */

if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Multiselect_Control extends WP_Customize_Control {
        /**
         * The type of customize control being rendered.
         *
         * @since Cakifo 1.5.0
         */
        public $type = 'multiple-select';
        /**
         * Displays the multiple select on the customize screen.
         *
         * @since Cakifo 1.5.0
         */
        public function render_content() {
            if ( empty( $this->choices ) ) {
                return;
            }

            include MPRESS_INC_DIR . 'partials/multiselect_output.php';
        }
    }
}