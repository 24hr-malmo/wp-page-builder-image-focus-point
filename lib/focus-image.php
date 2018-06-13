<?php

if ( ! class_exists( 'PageBuilderFocusImage' ) ) {

    class PageBuilderFocusImage {

        static $version;
        protected $dir;
        protected $plugin_dir;
        protected $content_draft_url;
        protected $init = false;
        protected $short_init = false;
        static $singleton;

        static function getInstance() {
            if (is_null(PageBuilderFocusImage::$singleton)) {
                throw new Exception('PageBuilderFocusImage not instanciated');
            }
            return PageBuilderFocusImage::$singleton;
        }

        function __construct($dir, $version) {
            PageBuilderFocusImage::$version = $version;
            $this->dir = $dir;
            $this->plugin_dir = basename( $this->dir );
            $this->init();
            PageBuilderFocusImage::$singleton = $this;
        }

        public function init() {

            // Disable double initialization.
            if ( $this->init ) {
                return $this;
            }

            $this->init = true;
            
            add_action( 'admin_enqueue_scripts', array(&$this, 'enqueue_admin_scripts' ));

            // add_filter('vc_before_init', array(&$this, 'register_custom_code_param'));
            vc_add_shortcode_param( 'focus_image', array(&$this, 'focus_image_param'), plugins_url( '../js/focus-image.js', __FILE__ )  );

        }

        function enqueue_admin_scripts($hook) {
            wp_enqueue_style( 'page-builder-focus-image-css', plugins_url( '../css/style.css', __FILE__ ) );
        }

        function fieldAttachedImages( $images = array(), $focus_x, $focus_y ) { 

            $output = '';

            foreach ( $images as $image ) {
                if ( is_numeric( $image ) ) {
                    $thumb_src = wp_get_attachment_image_src( $image, 'medium' );
                    $thumb_src = isset( $thumb_src[0] ) ? $thumb_src[0] : '';
                } else {
                    $thumb_src = $image;
                }

                if ( $thumb_src ) {
                    $output .= '
                    <li class="added">

                        <img rel="' . esc_attr( $image ) . '" src="' . esc_url( $thumb_src ) . '" />

                        <a href="#" class="pbfi--focus-image--icon pbfi--focus-image--icon-remove vc_icon-remove">
                            <i class="pbfi--focus-image--icon-close"></i>
                        </a>

                        <div class="pbfi-focus-box">
                            <div style="margin-bottom: 5px;"><label>focus point (in %)</label></div>
                            <div style="padding-bottom: 5px;">x: <input style="width: 50px; height: 20px;" id="pbfi-focus-image-value-x" type="text" value="' . $focus_x . '"/></div>
                            <div>y: <input style="width: 50px; height: 20px;" id="pbfi-focus-image-value-y" type="text" value="' . $focus_y . '"/></div>

                        </div>

                    </li>';
                }
            }

            return $output;

        }

        function focus_image_param($settings, $value){

            $values_array = explode('|', $value); 
            $images = $values_array[0];
            $focus = isset($values_array[1]) ? $values_array[1] : '0.5,0.5';
            $focus_values = explode(',', $focus);

            $focus_x = intval($focus_values[0] * 100) . '%';
            $focus_y = intval($focus_values[1] * 100) . '%';

            $output = '';
            $param_value = wpb_removeNotExistingImgIDs( $images );
            $output .= '<input type="hidden" class="wpb_vc_param_value gallery_widget_attached_images_ids '
                . $settings['param_name'] . ' '
                . $settings['type'] . '" name="' . $settings['param_name'] . '" value="' . $value . '"/>';
            $output .= '<div class="gallery_widget_attached_images">';

            $output .= '<ul class="pbfi-gallery_widget_attached_images_list">';
            $output .= ( '' !== $param_value )
                ?   $this->fieldAttachedImages( explode( ',', $images), $focus_x, $focus_y  )
                : '<a class="pbfi--gallery-widget--add-images" href="#" use-single="true" title="'
                . __( 'Add image', 'js_composer' ) . '"><i class="pbfi--focus-image--icon pbfi--focus-image--icon-add"></i>' . __( 'Add image', 'js_composer' ) . '</a>'; //class: button;


            $output .= '</ul>';
            $output .= '</div>';

            return $output;
        }



    }

}
    
