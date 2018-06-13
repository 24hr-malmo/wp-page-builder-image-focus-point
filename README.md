# wp-page-builder-image-focus-point
New image parameter for WPBakery pagebuilder with focus point

## How to use:

First make sure that the plugin is activated in WP.

When creating a custom module for WKBakery Page Builder, instead of using ```attached_image``` as param type, use ```focus_image```.
The following code is not a complete example, it just shows how to use the parameter.

```php
$settings = array(
    'name' => __('Spot Element', 'rawb'),
    'base' => 'vc_spot',
    'class' => '',
    'icon' => 'icon-wpb-single-image',
    'description' => __('Spot element', 'rawb'),
    'category' => __('Content', 'rawb'),

    'params' => array(

        array(
            'type' => 'focus_image', // <------------------ THIS IS THE NEW TYPE
            'heading' => __( 'Image', 'rawb' ),
            'param_name' => 'image',
            'value' => '',
            'description' => __( 'Select image from media library.', 'js_composer' )
        ),

    ),

    ...

);

vc_map( $settings );

