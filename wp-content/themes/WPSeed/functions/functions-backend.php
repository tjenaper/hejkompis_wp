<?php
/**
 * Backend related functions
 *
 * @author      Flurin Dürst
 * @version     1.2.0
 * @since       WPSeed 0.11.3
 *
 */


/* SHORTCODES
/===================================================== */

  /* BUTTON
  /------------------------*/
  // Usage: [button link="https://twitter.com" text="Twitter"]
  function shortcode_button($atts) {
     $link = $atts['link'];
     $text = $atts['text'];
     return '<a href="'.$link.'" class="button">'.$text.'</a>';
  }
  add_shortcode('button', 'shortcode_button');

/* WYSIWYG EDITOR
/===================================================== */

  /* TINY_MCE CUSTOM STYLES DROPDOWN
  /---------------------------------*/
  // » http://www.wpbeginner.com/wp-tutorials/how-to-add-custom-styles-to-wordpress-visual-editor/
  // Show "Styles" Dropdown
  add_filter( 'mce_buttons_2', 'mce_editor_buttons' );
  function mce_editor_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
  }
  // Add classes to the "Styles" Dropdown
  add_filter( 'tiny_mce_before_init', 'mce_before_init' );
  function mce_before_init( $settings ) {

    $style_formats = [
      [
        'title' => 'Image Example',
        'selector' => 'img',
        'classes' => 'imgexample'
      ],
      [
        'title' => 'Example',
        'selector' => 'p',
        'classes' => 'example',
      ]
    ];
    $settings['style_formats'] = json_encode( $style_formats );
    return $settings;
  }

/* MEDIA
/===================================================== */

  /* Allow svg uploads
  /------------------------*/
  function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter('upload_mimes', 'cc_mime_types');

/* MISC
/===================================================== */

  /* Hide core update info for non-admins
  /------------------------*/
  function onlyadmin_update() {
    if (!current_user_can('update_core')) {
        remove_action( 'admin_notices', 'update_nag', 3 );
    }
  }
  add_action( 'admin_head', 'onlyadmin_update', 1 );

  /* Hide Editor on Elements Templates
  /-----------------------------------*/
  add_action( 'admin_head', 'hide_editor' );
  function hide_editor() {
    if (isset($_GET['post']) or isset($_POST['post_ID'])) {
      // get post ID
      $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
      if( !isset( $post_id ) ) return;
      // get template_file
      $template_file = get_post_meta($post_id, '_wp_page_template', true);
      // disable editor at template
      if ($template_file == 'templates/temp-elements.php') {
        remove_post_type_support('page', 'editor');
      }
    }
  }

?>
