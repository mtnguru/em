<?php
/**
 * @file
 * Custom setting for Edu X Pro theme.
 */
function eduxpro_form_system_theme_settings_alter(&$form, &$form_state) {
  $theme_path = drupal_get_path('theme', 'eduxpro');
  $image_folder = drupal_get_path('theme', 'eduxpro') . '/images/theme-settings/';
  $button = "display: inline-block; background: #0984e3; color: white; margin-bottom: 10px; padding: 5px 10px";
	$form['#attached']['library'][] = 'eduxpro/theme-settings';
  $form['edux'] = [
    '#type'       => 'vertical_tabs',
    '#title'      => '<h3 class="settings-form-title">' . t('Edu X Pro Theme Settings') . '</h3>',
    '#default_tab' => 'general',
  ];
  /**
   * Main Tabs.
   */
  $form['general'] = [
    '#type'  => 'details',
    '#title' => t('General'),
    '#description' => t('<h3>Thank you for using Edu X Pro Theme</h3><strong>Edu X Pro</strong> is a premium Drupal 8, 9 theme designed and developed by <a href="https://www.drupar.com" target="_blank">Drupar.com</a>'),
    '#group' => 'edux',
  ];
  $form['layout'] = [
    '#type'  => 'details',
    '#title' => t('Layout'),
    '#group' => 'edux',
  ];
  $form['slider'] = [
    '#type'  => 'details',
    '#title' => t('Homepage Slider'),
    '#group' => 'edux',
  ];
  $form['header'] = [
    '#type'  => 'details',
    '#title' => t('Header'),
    '#group' => 'edux',
  ];
  $form['sidebar'] = [
    '#type'  => 'details',
    '#title' => t('Sidebar'),
    '#group' => 'edux',
  ];
  $form['content'] = [
    '#type'  => 'details',
    '#title' => t('Content'),
    '#group' => 'edux',
  ];
  $form['footer'] = [
    '#type'  => 'details',
    '#title' => t('Footer'),
    '#group' => 'edux',
  ];
  $form['comment'] = [
    '#type'  => 'details',
    '#title' => t('Comment'),
    '#group' => 'edux',
  ];
  $form['typography'] = [
    '#type'  => 'details',
    '#title' => t('Typography'),
    '#group' => 'edux',
  ];
  $form['elements'] = [
    '#type'  => 'details',
    '#title' => t('Elements'),
    '#group' => 'edux',
  ];
  $form['components'] = [
    '#type'  => 'details',
    '#title' => t('Components'),
    '#group' => 'edux',
  ];
  $form['color'] = [
    '#type'  => 'details',
    '#title' => t('Color'),
    '#group' => 'edux',
  ];
  $form['insert_codes'] = [
    '#type'  => 'details',
    '#title' => t('Insert Codes'),
    '#group' => 'edux',
  ];
  $form['support'] = [
    '#type'  => 'details',
    '#title' => t('Support'),
    '#group' => 'edux',
  ];
  $form['license'] = [
    '#type'  => 'details',
    '#title' => t('Theme License'),
    '#group' => 'edux',
  ];

  /*
   * General
   */
  $form['general']['general_info'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Theme Info'),
    '#description' => t('<a href="https://www.drupar.com/theme/eduxpro" target="_blank">Theme Homepage</a> || <a href="https://demo2.drupar.com/eduxpro/" target="_blank">Theme Demo</a> || <a href="https://www.drupar.com/doc/eduxpro" target="_blank">Theme Documentation</a> || <a href="https://www.drupar.com/doc/eduxpro/support" target="_blank">Theme Support</a>'),
  ];
  $form['general']['theme_version'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Current Theme Version'),
    '#description' => t('8.0.0'),
  ];
  /*
   * Layout
   */
  // Layout -> Container width
  $form['layout']['layout_container'] = [
    '#type'        => 'fieldset',
    '#title'         => t('Container width (px)'),
  ];
  $form['layout']['layout_container']['container_width'] = [
    '#type'          => 'number',
    '#step' => 1,
    '#default_value' => theme_get_setting('container_width', 'eduxpro'),
    '#description'   => t('Set width of the container in px. Default width is 1170px.'),
  ];
  // Layout -> Header Layout
  $form['layout']['layout_header'] = [
    '#type'        => 'fieldset',
    '#title'         => t('Header Layout'),
  ];
  $form['layout']['layout_header']['header_width'] = [
    '#type'          => 'select',
    '#options' => array(
    	'header_width_contained' => t('contained'),
    	'header_width_full' => t('Full Width'),),
    '#default_value' => theme_get_setting('header_width', 'eduxpro'),
  ];
  // Layout -> Main Layout
  $form['layout']['layout_main'] = [
    '#type'        => 'fieldset',
    '#title'         => t('Main Layout'),
  ];
  $form['layout']['layout_main']['main_width'] = [
    '#type'          => 'select',
    '#options' => array(
    	'main_width_contained' => t('contained'),
    	'main_width_full' => t('Full Width'),),
    '#default_value' => theme_get_setting('main_width', 'eduxpro'),
  ];
  // Layout -> Footer Layout
  $form['layout']['layout_footer'] = [
    '#type'        => 'fieldset',
    '#title'         => t('Footer Layout'),
  ];
  $form['layout']['layout_footer']['footer_width'] = [
    '#type'          => 'select',
    '#options' => array(
    	'footer_width_contained' => t('contained'),
    	'footer_width_full' => t('Full Width'),),
    '#default_value' => theme_get_setting('footer_width', 'eduxpro'),
  ];
  /*
   * Homepage slider
   */
  include_once 'inc/settings/homepage-slider.php';
  /*
   * Header
   */
  $form['header']['header_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Header -> Quick Links
  $form['header']['header_links'] = [
    '#type'        => 'details',
    '#title'       => t('Header Links'),
    '#group' => 'header_tab',
  ];
  $form['header']['header_links']['header_links_section'] = [
    '#type'        => 'fieldset',
    '#description'   => t('<a href="https://www.drupar.com/doc/eduxpro/how-manage-website-logo" target="_blank">Change Logo</a> || <a href="https://www.drupar.com/doc/eduxpro/how-change-favicon-icon" target="_blank">Change Favicon Icon</a> || <a href="https://www.drupar.com/doc/eduxpro/header-main-menu" target="_blank">Manage Main Menu</a> || <a href="https://www.drupar.com/doc/eduxpro/sliding-search-form" target="_blank">Sliding Search Form</a>'),
  ];
  // Header -> Login Links
  $form['header']['header_login'] = [
    '#type'        => 'details',
    '#title'       => t('Header Login Links'),
    '#group' => 'header_tab',
  ];
  $form['header']['header_login']['header_login_links'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show login links in header top region.'),
    '#default_value' => theme_get_setting('header_login_links', 'eduxpro'),
    '#description'   => t('Check this option to show login links in header top region.<br />Guest will get links to <strong>login</strong> and <strong>register</strong> while authentic users will get link for <strong>my account</strong> and <strong>logout</strong>.'),
  ];
  // Header -> Sticky header.
  $form['header']['sticky_header_section'] = [
    '#type'        => 'details',
    '#title'       => t('Sticky Header'),
    '#group' => 'header_tab',
  ];
  $form['header']['sticky_header_section']['sticky_header_mobile_section'] = [
    '#type'        => 'details',
    '#title'       => t('Sticky Header in Mobile Devices'),
    '#open' => TRUE,
  ];
  $form['header']['sticky_header_section']['sticky_header_mobile_section']['sticky_header_mobile'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable Sticky Header in Mobile Devices'),
    '#default_value' => theme_get_setting('sticky_header_mobile', 'eduxpro'),
    '#description'   => t("Check this option to enable sticky header in mobile devices. Uncheck to disable."),
  ];
  $form['header']['sticky_header_section']['sticky_header_desktop_section'] = [
    '#type'        => 'details',
    '#title'       => t('Sticky Header in Large Screens'),
    '#open' => TRUE,
  ];
  $form['header']['sticky_header_section']['sticky_header_desktop_section']['sticky_header_desktop'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable Sticky Header in Large Screens'),
    '#default_value' => theme_get_setting('sticky_header_desktop', 'eduxpro'),
    '#description'   => t("Check this option to enable sticky header in large screens. Uncheck to disable."),
  ];
  // Header -> Header Presets
  $form['header']['header_presets'] = [
    '#type'        => 'details',
    '#title'       => t('Header Presets / Styles'),
    '#description'   => t('<strong>Coming Soon..</strong>'),
    '#group' => 'header_tab',
  ];
  $form['header']['header_presets']['header_style'] = [
    '#type'        => 'radios',
    '#title'       => t('Select Header Style'),
    '#options' => array(
    	'header_style_one' => t('Classic'),
      'header_style_two' => t('Inverted'),
      'header_style_three' => t('Centerted'),
      'header_style_four' => t('Spaced'),
    ),
    '#description'   => t('This feature should be available in next version.'),
    '#disabled'   => TRUE,
  ];
  // header -> Header main
  $form['header']['header_main'] = [
    '#type'  => 'details',
    '#title' => t('Header Main'),
    '#group' => 'header_tab',
  ];
  $form['header']['header_main']['header_main_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Header Padding'),
    '#description'   => t('<hr /><br />This header region contains <strong>logo</strong> and <strong>main menu</strong>.')
  ];
  $form['header']['header_main']['header_main_section']['header_main_padding_top'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 10,
    '#step' => 0.1,
    '#title'  => t('Padding Top (rem)'),
    '#default_value' => theme_get_setting('header_main_padding_top', 'eduxpro'),
    '#description'   => t("Default padding top is <strong>1rem</strong> which is equivalent to 16px.<br /><br /><p><hr /></p><br />"),
  ];
  $form['header']['header_main']['header_main_section']['header_main_padding_bottom'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 10,
    '#step' => 0.1,
    '#title'  => t('Padding Bottom (rem)'),
    '#default_value' => theme_get_setting('header_main_padding_bottom', 'eduxpro'),
    '#description'   => t("Default padding bottom is <strong>1rem</strong> which is equivalent to 16px."),
  ];
  // header-> page header
  $form['header']['header_page'] = [
    '#type'  => 'details',
    '#title' => t('Page Header'),
    '#group' => 'header_tab',
  ];
  $form['header']['header_page']['header_page_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Page Header Padding'),
    '#description'   => t('<hr /><br />This page header region contains <strong>Page Title</strong> and <strong>Breadcrumb navigation</strong>.')
  ];
  $form['header']['header_page']['header_page_section']['header_page_padding_top'] = [
    '#type'   => 'number',
    '#min'  => 0.1,
    '#max'  => 10,
    '#step' => 0.1,
    '#title'  => t('Padding Top (rem)'),
    '#default_value' => theme_get_setting('header_page_padding_top', 'eduxpro'),
    '#description'   => t("Default padding top is <strong>5rem</strong><br /><br /><p><hr /></p>"),
  ];
  $form['header']['header_page']['header_page_section']['header_page_padding_bottom'] = [
    '#type'   => 'number',
    '#min'  => 0.1,
    '#max'  => 10,
    '#step' => 0.1,
    '#title'  => t('Padding Bottom (rem)'),
    '#default_value' => theme_get_setting('header_page_padding_bottom', 'eduxpro'),
    '#description'   => t("Default padding bottom is <strong>5rem</strong>."),
  ];
  $form['header']['header_page']['header_page_position_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Content Position'),
    '#description'   => t('<hr /><br />Position of content in <strong>Header Main</strong> region.')
  ];
  $form['header']['header_page']['header_page_position_section']['header_page_content_position'] = [
    '#type'          => 'select',
    '#options' => array(
      'flex-start' => t('Left'),
      'flex-end' => t('Right'),
      'center' => t('center'),
    ),
    '#default_value' => theme_get_setting('header_page_content_position', 'eduxpro'),
    '#description'   => t("Default position is <strong>Center</strong>."),
  ];
  /*
   * Sidebar
   */
  $form['sidebar']['sidebar_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Sidebar -> Frontpage sidebar
  $form['sidebar']['front_sidebars'] = [
    '#type'          => 'details',
    '#title'         => t('Homepage Sidebar'),
    '#group' => 'sidebar_tab',
  ];
  $form['sidebar']['front_sidebars']['front_sidebar_section'] = [
    '#type'        => 'fieldset',
  ];
  $form['sidebar']['front_sidebars']['front_sidebar_section']['front_sidebar'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show Sidebars On Homepage'),
    '#default_value' => theme_get_setting('front_sidebar', 'eduxpro'),
    '#description'   => t('<p>Check this option to enable left and right sidebar on homepage.</p><hr /><br /><strong>Homepage Content Top</strong> and <strong>Homepage Content Bottom</strong> block regions will always be full width.'),
  ];
  // Sidebar -> sidebar width
  $form['sidebar']['sidebar_width'] = [
    '#type'          => 'details',
    '#title'         => t('Sidebar Width'),
    '#group' => 'sidebar_tab',
  ];
  $form['sidebar']['sidebar_width']['sidebar_width_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default Sidebars Width'),
  ];
  $form['sidebar']['sidebar_width']['sidebar_width_default_section']['sidebar_width_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use theme default sidebar width'),
    '#default_value' => theme_get_setting('sidebar_width_default', 'eduxpro'),
    '#description'   => t('Check this option to use theme default value of sidebar width. Uncheck this to set custom value below.'),
  ];
  $form['sidebar']['sidebar_width']['sidebar_width_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Sidebars Width'),
  ];
  $form['sidebar']['sidebar_width']['sidebar_width_section']['sidebar_width_left'] = [
    '#type'          => 'number',
    '#title'         => t('Left Sidebar Width (in percentage)'),
    '#default_value' => theme_get_setting('sidebar_width_left', 'eduxpro'),
    '#description'   => t('Default width of left sidebar is 30%<br /><br /><p><hr /></p>'),
  ];
  $form['sidebar']['sidebar_width']['sidebar_width_section']['sidebar_width_right'] = [
    '#type'          => 'number',
    '#title'         => t('Right Sidebar Width (in percentage)'),
    '#default_value' => theme_get_setting('sidebar_width_right', 'eduxpro'),
    '#description'   => t('Default width of right sidebar is 30%'),
  ];
  // Sidebar -> Animated Sidebar
  $form['sidebar']['animated_sidebar_tab'] = [
    '#type'        => 'details',
    '#title'       => t('Animated Sidebar'),
    '#group' => 'sidebar_tab',
  ];
  $form['sidebar']['animated_sidebar_tab']['animated_sidebar'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable animated sidebar'),
    '#default_value' => theme_get_setting('animated_sidebar', 'eduxpro'),
    '#description'   => t("Check this option to enable animated sidebar feature. Uncheck to hide.<br />Please refer to this tutorial for details. <a href='https://www.drupar.com/doc/eduxpro/animated-sidebar' target='_blank'>How To Create Animated Sidebar</a>"),
  ];
  // Sidebar -> Sidebar Block
  $form['sidebar']['sidebar_block'] = [
    '#type'          => 'details',
    '#title'         => t('Sidebar Blocks'),
    '#group' => 'sidebar_tab',
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default Sidebars Block Settings'),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_default_section']['sidebar_block_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use theme default sidebar block settings.'),
    '#default_value' => theme_get_setting('sidebar_block_default', 'eduxpro'),
    '#description'   => t('Check this option to use theme default value of sidebar block. Uncheck this to set custom value below.'),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Sidebar Block'),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_section']['sidebar_padding'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 50,
    '#step' => 1,
    '#title'  => t('Sidebar Block Padding (px)'),
    '#default_value' => theme_get_setting('sidebar_padding', 'eduxpro'),
    '#description'   => t("Default is 20px.<br /><br /><p><hr /></p>"),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_section']['sidebar_radius'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 50,
    '#step' => 1,
    '#title'  => t('Sidebar Block Border Radius (px)'),
    '#default_value' => theme_get_setting('sidebar_radius', 'eduxpro'),
    '#description'   => t("Default is 6px.<br /><br /><p><hr /></p>"),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_section']['sidebar_margin'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 50,
    '#step' => 1,
    '#title'  => t('Sidebar Block Margin Bottom (rem)'),
    '#default_value' => theme_get_setting('sidebar_margin', 'eduxpro'),
    '#description'   => t("Default value is 2rem which is equivalent to 32px.<br />1rem = 16px"),
  ];
  $form['sidebar']['sidebar_block']['sidebar_title_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Sidebar Block Title'),
  ];
  $form['sidebar']['sidebar_block']['sidebar_title_section']['sidebar_title_font_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 50,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('sidebar_title_font_size', 'eduxpro'),
    '#description'   => t("Default value is 2.2rem<br />1rem = 16px<br /><br /><p><hr /></p>"),
  ];
  $form['sidebar']['sidebar_block']['sidebar_title_section']['sidebar_title_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('sidebar_title_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>."),
  ];
  $form['sidebar']['sidebar_block']['sidebar_block_color'] = [
    '#type'          => 'details',
    '#title'         => t('Sidebar Block Background Color'),
    '#description'   => t('Color settings are available in <strong>Color Tab</strong>'),
    '#open' => TRUE,
  ];
  /*
   * Content
   */
  $form['content']['content_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // content -> RTL
  $form['content']['content_direction'] = [
    '#type'          => 'details',
    '#title'         => t('Content Direction - RTL'),
    '#group' => 'content_tab',
  ];
  $form['content']['content_direction']['rtl'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable RTL (Experimental)'),
    '#default_value' => theme_get_setting('rtl', 'eduxpro'),
    '#description'   => t('Currently not available.'),
    '#disabled'   => TRUE,
    //'#description'   => t('edux theme is Right-to-left (RTL) languages compatible. Check this option to enable RTL. This feature is currently under testing phase. So, this may not work perfectly.'),
  ];

  // Content -> Animated Content.
  $form['content']['animated_content_tab'] = [
    '#type'        => 'details',
    '#title'       => t('Animated Page Content'),
    '#group' => 'content_tab',
  ];
  $form['content']['animated_content_tab']['animated_content_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Animated Page Content'),
    '#description'   => t('<p><hr /></p><p>With animated page content shortcodes, you can create contents with animation effects. These contents will appear with some animation effect when it will come in browser view.</p><p>Please visit this tutorial page for details. <a href="https://www.drupar.com/doc/thexpro/how-create-animated-content" target="_blank">How to create animated content</a>.</p>'),
  ];

  $form['content']['animated_content_tab']['animated_content_section']['animated_content'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable Animated Page Content when in view'),
    '#default_value' => theme_get_setting('animated_content', 'eduxpro'),
    '#description'   => t("Check this option to enable animated page content when in view feature. Uncheck to disable this feature."),
  ];

  // Content-> Submitted Details
  $form['content']['submitted_details'] = [
    '#type'  => 'details',
    '#title' => t('Submitted Details'),
    '#group' => 'content_tab',
  ];
  $form['content']['submitted_details']['node_author_pic_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Author Picture'),
  ];
  $form['content']['submitted_details']['node_author_pic_section']['node_author_pic'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show Node Author Picture in Submitted Details.'),
    '#default_value' => theme_get_setting('node_author_pic', 'eduxpro'),
    '#description'   => t("Check this option to show node author picture in submitted details. Uncheck to hide."),
  ];
  // Show tags in node submitted.
  $form['content']['submitted_details']['node_tags_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Show Node Tags'),
  ];
  $form['content']['submitted_details']['node_tags_section']['node_tags'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show Node Tags in Submitted Details.'),
    '#default_value' => theme_get_setting('node_tags', 'eduxpro'),
    '#description'   => t("Check this option to show node tags (if any) in submitted details. Uncheck to hide."),
  ];
  // Node author picture.

  // Show tags in node submitted.

  /*
   * Footer
   */
  $form['footer']['footer_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Footer -> Footer Presets
  $form['footer']['footer_presets'] = [
    '#type'        => 'details',
    '#title'       => t('Footer Presets / Styles'),
    '#description'   => t('This feature should be available in next version.'),
    '#group' => 'footer_tab',
  ];
  $form['footer']['footer_presets']['footer_style'] = [
    '#type'        => 'radios',
    '#title'       => t('Coming Soon ..'),
    '#options' => array(
    	'footer_style_one' => t('Classic'),
      'footer_style_two' => t('Inverted'),
      'footer_style_three' => t('Centerted'),
      'footer_style_four' => t('Spaced'),
    ),
    '#disabled'   => TRUE
  ];
  // Footer -> Copyright.
  $form['footer']['copyright_tab'] = [
    '#type'        => 'details',
    '#title'       => t('Copyright Text'),
    '#group' => 'footer_tab',
  ];
  $form['footer']['copyright_tab']['copyright_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Website Copyright Text'),
  ];
  $form['footer']['copyright_tab']['copyright_section']['copyright_text'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show website copyright text in footer.'),
    '#default_value' => theme_get_setting('copyright_text', 'eduxpro'),
    '#description'   => t("Check this option to show website copyright text in footer. Uncheck to hide."),
  ];
  // Footer -> copyright -> custom copyright text
  $form['footer']['copyright_tab']['copyright_section']['copyright_text_custom'] = [
    '#type'          => 'textarea',
    '#title'         => t('Custom Copyright Text'),
    '#default_value' => theme_get_setting('copyright_text_custom', 'eduxpro'),
    '#description'   => t("<p>Enter custom copyright text. Leave it blank to show default copyright text.</p><p>For more details, please read this tutorial: <a href='https://www.drupar.com/doc/thexpro/copyright-text-footer' target='_blank'>Copyright Text in Footer</a>"),
  ];
  /**
   * Settings under comment tab.
   */
  // Show user picture in comment.
  $form['comment']['comment_photo'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Comment User Picture'),
  ];

  $form['comment']['comment_photo']['comment_user_pic'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show User Picture in comments'),
    '#default_value' => theme_get_setting('comment_user_pic', 'eduxpro'),
    '#description'   => t("Check this option to show user picture in comment. Uncheck to hide."),
  ];
  // Hightlight Node author comment.
  $form['comment']['comment_author'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Author Comment'),
  ];

  $form['comment']['comment_author']['highlight_author_comment'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Highlight Author Comments'),
    '#default_value' => theme_get_setting('highlight_author_comment', 'eduxpro'),
    '#description'   => t("Check this option to highlight node author comments."),
  ];
  $form['comment']['comment_author']['highlight_author_color'] = [
    '#type'          => 'details',
    '#title'         => t('Highlight Color'),
    '#description'   => t('Color option is available in the premium version of this theme. <a href="https://www.drupar.com/theme/eduxpro" target="_blank">Buy EduX Pro for $29 only.</a>'),
    '#open' => TRUE,
  ];

  /*
   * Typography
   */
  $form['typography']['typography_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Typography -> Body
  $form['typography']['body'] = [
    '#type'  => 'details',
    '#title' => t('Body'),
    '#group' => 'typography_tab',
  ];
  $form['typography']['body']['body_font_size_section'] = [
    '#type'        => 'details',
    '#title'       => t('Font Size'),
    '#open' => TRUE,
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px"),
  ];
  $form['typography']['body']['body_font_size_section']['body_font_size'] = [
    '#type'   => 'number',
    '#min'  => 0.5,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('body_font_size', 'eduxpro'),
    '#description'   => t("Default size is 1rem which is equivalent to 16px."),
  ];
  $form['typography']['body']['body_line_height_section'] = [
    '#type'        => 'details',
    '#title'       => t('Line Height'),
    '#open' => TRUE,
  ];
  $form['typography']['body']['body_line_height_section']['body_line_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#default_value' => theme_get_setting('body_line_height', 'eduxpro'),
    '#description'   => t("Default value is 1.7"),
  ];
  // Typography -> Paragraph
  $form['typography']['paragraph'] = [
    '#type'  => 'details',
    '#title' => t('Paragraph'),
    '#group' => 'typography_tab',
  ];
  $form['typography']['paragraph']['paragraph_section'] = [
    '#type'        => 'details',
    '#title'       => t('Paragraph Margin Bottom (rem)'),
    '#open' => TRUE,
  ];
  $form['typography']['paragraph']['paragraph_section']['paragraph_bottom'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#max'  => 3,
    '#step' => 0.1,
    '#default_value' => theme_get_setting('paragraph_bottom', 'eduxpro'),
    '#description'   => t("1 rem = 16px<br />Default size is <strong>1.2rem</strong>."),
  ];
  // Typography -> Headings
  $form['typography']['headings'] = [
    '#type'  => 'details',
    '#title' => t('Headings'),
    '#group' => 'typography_tab',
  ];
  $form['typography']['headings']['headings_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default Heading Values'),
  ];
  $form['typography']['headings']['headings_default_section']['headings_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use theme default heading values.'),
    '#default_value' => theme_get_setting('headings_default', 'eduxpro'),
    '#description'   => t('Check this option to use theme default value for headings. Uncheck this to set custom value below.'),
  ];
  $form['typography']['headings']['headings_section_info'] = [
    '#type'  => 'details',
    '#title' => t('Please Note'),
    '#description'   => t('<p>Below settings for heading will only apply to large screens (laptop and desktop).</p><p>If you want to modify headings for small devices (mobile and tablet), please use <strong>Custom Styling</strong> section.</p>'),
    '#open' => TRUE,
  ];
  $form['typography']['headings']['h1'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 1 (H1)'),
  ];
  $form['typography']['headings']['h1']['h1_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h1_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h1']['h1_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h1_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h1']['h1_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h1_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h1']['h1_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h1_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['typography']['headings']['h2'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 2 (H2)'),
  ];
  $form['typography']['headings']['h2']['h2_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h2_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h2']['h2_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h2_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h2']['h2_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h2_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h2']['h2_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h2_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['typography']['headings']['h3'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 3 (H3)'),
  ];
  $form['typography']['headings']['h3']['h3_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h3_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h3']['h3_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h3_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h3']['h3_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h3_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h3']['h3_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h3_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['typography']['headings']['h4'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 4 (H4)'),
  ];
  $form['typography']['headings']['h4']['h4_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h4_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h4']['h4_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h4_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h4']['h4_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h4_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h4']['h4_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h4_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['typography']['headings']['h5'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 5 (H5)'),
  ];
  $form['typography']['headings']['h5']['h5_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h5_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h5']['h5_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h5_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h5']['h5_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h5_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h5']['h5_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h5_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['typography']['headings']['h6'] = [
    '#type'        => 'details',
    '#title'       => t('Heading 6 (H6)'),
  ];
  $form['typography']['headings']['h6']['h6_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('h6_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h6']['h6_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('h6_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h6']['h6_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('h6_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['typography']['headings']['h6']['h6_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('h6_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  /*
   * Elements
   */
  $form['elements']['elements_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Elements -> Logo
  $form['elements']['logo'] = [
    '#type'  => 'details',
    '#title' => t('Logo'),
    '#group' => 'elements_tab',
  ];
  $form['elements']['logo']['logo_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default'),
  ];
  $form['elements']['logo']['logo_default_section']['logo_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use Default Logo Settings'),
    '#default_value' => theme_get_setting('logo_default', 'eduxpro'),
    '#description'   => t('Check this option to use default values for sitename and site slogan. Uncheck this to set custom values below.'),
  ];
  $form['elements']['logo']['site_name'] = [
    '#type'        => 'details',
    '#title'       => t('Site Name'),
    '#open' => TRUE,
  ];
  $form['elements']['logo']['site_name']['site_name_size'] = [
    '#type'   => 'number',
    '#min'  => 0.5,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('site_name_size', 'eduxpro'),
    '#description'   => t("Default value is <strong>1rem</strong>.<br />1 rem = 16px<br /><br /><br /><p><hr /></p>"),
  ];
  $form['elements']['logo']['site_name']['site_name_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('site_name_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['elements']['logo']['site_name']['site_name_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('site_name_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['elements']['logo']['site_name']['site_name_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('site_name_height', 'eduxpro'),
    '#description'   => t("Default size is 1.1"),
  ];
  $form['elements']['logo']['slogan'] = [
    '#type'        => 'details',
    '#title'       => t('Slogan'),
    '#open' => TRUE,
  ];
  $form['elements']['logo']['slogan']['slogan_size'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('slogan_size', 'eduxpro'),
    '#description'   => t("Value is in <strong>rem</strong> unit. 1 rem = 16px<br />Default size is 1rem.<br /><br /><p><hr /></p>"),
  ];
  $form['elements']['logo']['slogan']['slogan_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('slogan_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>.<br /><br /><p><hr /></p>"),
  ];
  $form['elements']['logo']['slogan']['slogan_height'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Line Height'),
    '#default_value' => theme_get_setting('slogan_height', 'eduxpro'),
    '#description'   => t("Default size is 1.7"),
  ];
  $form['elements']['logo']['slogan']['slogan_style'] = [
    '#type'          => 'select',
    '#title'  => t('Style'),
    '#options' => array(
    	'normal' => t('Normal'),
      'italic' => t('Italic'),
    ),
    '#default_value' => theme_get_setting('slogan_style', 'eduxpro'),
    '#description'   => t("Default value is <strong>Normal</strong>."),
  ];
  // Elements -> Main menu
  $form['elements']['main_menu'] = [
    '#type'  => 'details',
    '#title' => t('Main Menu'),
    '#group' => 'elements_tab',
  ];
  $form['elements']['main_menu']['main_menu_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default'),
  ];
  $form['elements']['main_menu']['main_menu_default_section']['main_menu_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use Default Main Menu Settings'),
    '#default_value' => theme_get_setting('main_menu_default', 'eduxpro'),
    '#description'   => t('Check this option to use default main menu settings. Uncheck this to set custom values below.'),
  ];
  $form['elements']['main_menu']['main_menu_top'] = [
    '#type'  => 'details',
    '#title' => t('Main Menu'),
    '#open' => TRUE,
  ];
  $form['elements']['main_menu']['main_menu_top']['main_menu_top_size'] = [
    '#type'   => 'number',
    '#min'  => 0.5,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('main_menu_top_size', 'eduxpro'),
    '#description'   => t("Default value is <strong>1rem</strong>.<br />1 rem = 16px<br /><p><hr /></p>"),
  ];
  $form['elements']['main_menu']['main_menu_top']['main_menu_top_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('main_menu_top_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><p><hr /></p>"),
  ];
  $form['elements']['main_menu']['main_menu_top']['main_menu_top_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('main_menu_top_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>."),
  ];
  $form['elements']['main_menu']['main_menu_sub'] = [
    '#type'  => 'details',
    '#title' => t('Main Menu: Dropdowns'),
    '#open' => TRUE,
  ];
  $form['elements']['main_menu']['main_menu_sub']['main_menu_sub_size'] = [
    '#type'   => 'number',
    '#min'  => 0.5,
    '#max'  => 3,
    '#step' => 0.1,
    '#title'  => t('Font Size (rem)'),
    '#default_value' => theme_get_setting('main_menu_sub_size', 'eduxpro'),
    '#description'   => t("Default value is <strong>1rem</strong>.<br />1 rem = 16px<br /><p><hr /></p>"),
  ];
  $form['elements']['main_menu']['main_menu_sub']['main_menu_sub_weight'] = [
    '#type'   => 'select',
    '#title'  => t('Font Weight'),
    '#options' => array(
      '400' => t('400'),
      '700' => t('700'),
    ),
    '#default_value' => theme_get_setting('main_menu_sub_weight', 'eduxpro'),
    '#description'   => t("Default value is <strong>700</strong>.<br /><p><hr /></p>"),
  ];
  $form['elements']['main_menu']['main_menu_sub']['main_menu_sub_transform'] = [
    '#type'          => 'select',
    '#title'  => t('Transform'),
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('main_menu_sub_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>."),
  ];
  $form['elements']['main_menu']['main_menu_color'] = [
    '#type'          => 'details',
    '#title'         => t('Main Menu Color'),
    '#description'   => t('Color option is available in the premium version of this theme. <a href="https://www.drupar.com/theme/eduxpro" target="_blank">Buy EduX Pro for $29 only.</a>'),
    '#open' => TRUE,
  ];
  // Elements -> Page Title
  $form['elements']['page_title'] = [
    '#type'  => 'details',
    '#title' => t('Page Title'),
    '#group' => 'elements_tab',
  ];
  $form['elements']['page_title']['page_title_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default'),
  ];
  $form['elements']['page_title']['page_title_default_section']['page_title_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use Default Page Title Settings'),
    '#default_value' => theme_get_setting('page_title_default', 'eduxpro'),
    '#description'   => t('Check this option to use default values for page title. Uncheck this to set custom values below.'),
  ];
  $form['elements']['page_title']['page_title_size_section'] = [
    '#type'        => 'details',
    '#title'       => t('Font Size (rem)'),
    '#open' => TRUE,
    '#description'   => t("Value is in rem unit. 1 rem = 16px"),
  ];
  $form['elements']['page_title']['page_title_size_section']['page_title_size_desktop'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Desktop and Laptop (rem)'),
    '#default_value' => theme_get_setting('page_title_size_desktop', 'eduxpro'),
    '#description'   => t("Default value is <strong>2.6rem</strong>.<br /><p><hr /></p>"),
  ];
  $form['elements']['page_title']['page_title_size_section']['page_title_size_mobile'] = [
    '#type'   => 'number',
    '#min'  => 1,
    '#max'  => 5,
    '#step' => 0.1,
    '#title'  => t('Mobile and Tablet (rem)'),
    '#default_value' => theme_get_setting('page_title_size_mobile', 'eduxpro'),
    '#description'   => t("Default value is <strong>2.2rem</strong>."),
  ];
  $form['elements']['page_title']['page_title_transform_section'] = [
    '#type'        => 'details',
    '#title'       => t('Text Transform'),
    '#open' => TRUE,
  ];
  $form['elements']['page_title']['page_title_transform_section']['page_title_transform'] = [
    '#type'          => 'select',
    '#options' => array(
    	'none' => t('None'),
      'capitalize' => t('Capitalize'),
      'uppercase' => t('Uppercase'),
      'lowercase' => t('Lowercase'),
    ),
    '#default_value' => theme_get_setting('page_title_transform', 'eduxpro'),
    '#description'   => t("Default value is <strong>None</strong>."),
  ];
  // Elements -> Breadcrumb.
  $form['elements']['breadcrumb'] = [
    '#type'  => 'details',
    '#title' => t('Breadcrumb'),
    '#group' => 'elements_tab',
  ];
  $form['elements']['breadcrumb']['breadcrumb_icon'] = [
    '#type'        => 'details',
    '#title'       => t('Breadcrumb Separator Icon'),
    '#open' => TRUE,
  ];
  $form['elements']['breadcrumb']['breadcrumb_icon']['breadcrumb_icon_style'] = [
    '#type'          => 'radios',
    '#title'         => t('Select Breadcrumb Separator Icon<br /><br />'),
    '#options' => array(
      '&#x276F;' => t('<span style="font-size: 24px">&#x276F;</span><br /><br />'),
      '&#x27F6;' => t('<span style="font-size: 24px">&#x27F6;</span><br /><br />'),
      '&#x203A;' => t('<span style="font-size: 24px">&#x203A;</span><br /><br />'),
      '&#x279D;' => t('<span style="font-size: 24px">&#x279D;</span><br /><br />'),
      '/' => t('<span style="font-size: 24px">/</span><br /><br />'),
      '/' => t('<span style="font-size: 2rem">\</span><br /><br />'),
    ),
    '#default_value' => theme_get_setting('breadcrumb_icon_style', 'eduxpro'),
  ];
  // Elements -> Button
  $form['elements']['button'] = [
    '#type'  => 'details',
    '#title' => t('Button'),
    '#group' => 'elements_tab',
  ];
  $form['elements']['button']['button_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default'),
  ];
  $form['elements']['button']['button_default_section']['button_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use Default Main Menu Settings'),
    '#default_value' => theme_get_setting('button_default', 'eduxpro'),
    '#description'   => t('Check this option to use default main menu settings. Uncheck this to set custom values below.'),
  ];
  $form['elements']['button']['button_section'] = [
    '#type'        => 'details',
    '#title'       => t('Button Padding'),
    '#open' => TRUE,
  ];
  $form['elements']['button']['button_section']['button_padding'] = [
    '#type'   => 'textfield',
    '#title'  => t('Top Right Bottom Left'),
    '#default_value' => theme_get_setting('button_padding', 'eduxpro'),
    '#description'   => t("Padding of button. Example: <strong>5px 10px 5px 10px</strong><br />
    Default value is: 8px 10px 8px 10px<br /><p><hr /></p>"),
  ];
  $form['elements']['button']['button_section']['button_radius'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#step' => 1,
    '#title'  => t('Border Radius (px)'),
    '#default_value' => theme_get_setting('button_radius', 'eduxpro'),
    '#description'   => t("Border radius of buttons. Default value is 8px."),
  ];
  /*
   * Components
   */
  $form['components']['components_tab'] = [
    '#type'  => 'vertical_tabs',
  ];
  // Components -> Google Fonts.
  $form['components']['fonts'] = [
    '#type'          => 'details',
    '#title'         => t('Fonts'),
    '#group' => 'components_tab',
  ];
  $form['components']['fonts']['fonts_section'] = [
    '#type'        => 'fieldset',
  ];
  $form['components']['fonts']['fonts_section']['font_src'] = [
    '#type'          => 'select',
    '#title'         => t('Select Google Fonts Location'),
    '#options' => array(
    	'local' => t('Local Self Hosted'),
      'googlecdn' => t('Google CDN Server')
    ),
    '#default_value' => theme_get_setting('font_src', 'eduxpro'),
    '#description'   => t('EduX theme uses following Google fonts: Noto Sans.<br />You can serve these fonts locally or from Google server.'),
  ];
  // Components -> Social
  $form['components']['social'] = [
    '#type'  => 'details',
    '#title' => t('Social'),
    '#group' => 'components_tab',
  ];
  $form['components']['social']['all_icons'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Show Social Icons'),
  ];
  $form['components']['social']['all_icons']['all_icons_show'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show social icons in footer'),
    '#default_value' => theme_get_setting('all_icons_show', 'eduxpro'),
    '#description'   => t("Check this option to show social icons in footer. Uncheck to hide."),
  ];
  $form['components']['social']['social_profile'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Social Profile'),
  ];
  // Facebook.
    $form['components']['social']['social_profile']['facebook'] = [
    '#type'        => 'details',
    '#title'       => t("Facebook"),
  ];

  $form['components']['social']['social_profile']['facebook']['facebook_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Facebook Url'),
    '#description'   => t("Enter yours facebook profile or page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('facebook_url', 'eduxpro'),
  ];

  // Twitter.
  $form['components']['social']['social_profile']['twitter'] = [
    '#type'        => 'details',
    '#title'       => t("Twitter"),
  ];

  $form['components']['social']['social_profile']['twitter']['twitter_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Twitter Url'),
    '#description'   => t("Enter yours twitter page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('twitter_url', 'eduxpro'),
  ];

  // Instagram.
  $form['components']['social']['social_profile']['instagram'] = [
    '#type'        => 'details',
    '#title'       => t("Instagram"),
  ];

  $form['components']['social']['social_profile']['instagram']['instagram_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Instagram Url'),
    '#description'   => t("Enter yours instagram page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('instagram_url', 'eduxpro'),
  ];

  // Linkedin.
  $form['components']['social']['social_profile']['linkedin'] = [
    '#type'        => 'details',
    '#title'       => t("Linkedin"),
  ];

  $form['components']['social']['social_profile']['linkedin']['linkedin_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Linkedin Url'),
    '#description'   => t("Enter yours linkedin page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('linkedin_url', 'eduxpro'),
  ];

  // YouTube.
  $form['components']['social']['social_profile']['youtube'] = [
    '#type'        => 'details',
    '#title'       => t("YouTube"),
  ];

  $form['components']['social']['social_profile']['youtube']['youtube_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('YouTube Url'),
    '#description'   => t("Enter yours youtube.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('youtube_url', 'eduxpro'),
  ];

  // YouTube.
  $form['components']['social']['social_profile']['vimeo'] = [
    '#type'        => 'details',
    '#title'       => t("vimeo"),
  ];

  $form['components']['social']['social_profile']['vimeo']['vimeo_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('vimeo Url'),
    '#description'   => t("Enter yours vimeo.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('vimeo_url', 'eduxpro'),
  ];

  // telegram.
    $form['components']['social']['social_profile']['telegram'] = [
    '#type'        => 'details',
    '#title'       => t("Telegram"),
  ];

  $form['components']['social']['social_profile']['telegram']['telegram_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Telegram Url'),
    '#description'   => t("Enter yours Telegram profile or page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('telegram_url', 'eduxpro'),
  ];

  // WhatsApp.
    $form['components']['social']['social_profile']['whatsapp'] = [
    '#type'        => 'details',
    '#title'       => t("WhatsApp"),
  ];

  $form['components']['social']['social_profile']['whatsapp']['whatsapp_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('WhatsApp Url'),
    '#description'   => t("Enter yours whatsapp message url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('whatsapp_url', 'eduxpro'),
  ];

  // Github.
    $form['components']['social']['social_profile']['github'] = [
    '#type'        => 'details',
    '#title'       => t("GitHub"),
  ];

  $form['components']['social']['social_profile']['github']['github_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('GitHub Url'),
    '#description'   => t("Enter yours github page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('github_url', 'eduxpro'),
  ];

  // Social -> vk.com url.
  $form['components']['social']['social_profile']['vk'] = [
    '#type'        => 'details',
    '#title'       => t("vk.com"),
  ];
  $form['components']['social']['social_profile']['vk']['vk_url'] = [
      '#type'          => 'textfield',
      '#title'         => t('vk.com'),
      '#description'   => t("Enter yours vk.com page url. Leave the url field blank to hide this icon."),
      '#default_value' => theme_get_setting('vk_url', 'eduxpro'),
  ];
  // Components -> Share pages
  include_once 'inc/settings/share.php';
  
  $form['components']['font_icons'] = [
    '#type'  => 'details',
    '#title' => t('Font Icons'),
    '#group' => 'components_tab',
  ];
  $form['components']['font_icons']['fontawesome4'] = [
    '#type'          => 'fieldset',
    '#title'         => t('FontAwesome 4'),
  ];
  $form['components']['font_icons']['fontawesome4']['fontawesome_four'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable FontAwesome 4 Font Icons'),
    '#default_value' => theme_get_setting('fontawesome_four', 'eduxpro'),
    '#description'   => t('Check this option to enable fontawesome version 4 font icons.'),
  ];
  $form['components']['font_icons']['fontawesome5'] = [
    '#type'          => 'fieldset',
    '#title'         => t('FontAwesome 5'),
  ];
  $form['components']['font_icons']['fontawesome5']['fontawesome_five'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable FontAwesome 5 Font Icons'),
    '#default_value' => theme_get_setting('fontawesome_five', 'eduxpro'),
    '#description'   => t('Check this option to enable fontawesome version 5 font icons.'),
  ];
  // Components -> Page loader.
  $form['components']['preloader'] = [
    '#type'        => 'details',
    '#title'       => t('Pre Page Loader'),
    '#group' => 'components_tab',
  ];
  $form['components']['preloader']['preloader_section'] = [
    '#type'        => 'details',
    '#title'       => t('Pre Page Loader'),
    '#open' => true,
  ];
  $form['components']['preloader']['preloader_section']['preloader_option'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show a loading icon before page loads.'),
    '#default_value' => theme_get_setting('preloader_option', 'eduxpro'),
    '#description'   => t("Check this option to show a cool animated image until your website is loading. Uncheck to disable this feature."),
  ];
  // Components -> Cookie message.
  $form['components']['cookie'] = [
    '#type'        => 'details',
    '#title'       => t('Cookie Consent message'),
    '#group' => 'components_tab',
  ];

  $form['components']['cookie']['cookie_message_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Cookie Consent message'),
  ];
  $form['components']['cookie']['cookie_message_section']['cookie_message'] = [
    '#type'          => 'checkbox',
    '#title'       => t('Show Cookie Consent Popup Message'),
    '#default_value' => theme_get_setting('cookie_message', 'eduxpro'),
    '#description'   => t('Make your website EU Cookie Law Compliant. According to EU cookies law, websites need to get consent from visitors to store or retrieve cookies.'),
  ];
  $form['components']['cookie']['cookie_message_section']['cookie_message_custom'] = [
    '#type'          => 'textarea',
    '#title'         => t('Custom Cookie Consent Message'),
    '#default_value' => theme_get_setting('cookie_message_custom', 'eduxpro'),
    '#description'   => t("Enter Cookie Consent Message. Leave it blank to show default message text."),
  ];
  // Components -> Scroll to top.
  $form['components']['scrolltotop'] = [
    '#type'  => 'details',
    '#title' => t('Scroll To Top'),
    '#group' => 'components_tab',
  ];
  $form['components']['scrolltotop']['scrolltotop_enable'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Enable Scroll To Top'),
  ];

  $form['components']['scrolltotop']['scrolltotop_enable']['scrolltotop_on'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable scroll to top feature.'),
    '#default_value' => theme_get_setting('scrolltotop_on', 'eduxpro'),
    '#description'   => t("Check this option to enable scroll to top feature. Uncheck to disable this fearure and hide scroll to top icon."),
  ];
  $form['components']['scrolltotop']['scrolltotop_default_section'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Use Default Settings'),
  ];
  $form['components']['scrolltotop']['scrolltotop_default_section']['scrolltotop_default'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Use theme default sidebar block settings.'),
    '#default_value' => theme_get_setting('scrolltotop_default', 'eduxpro'),
    '#description'   => t('Check this option to use theme default value of sidebar block. Uncheck this to set custom value below.'),
  ];
  $form['components']['scrolltotop']['scrolltotop_icon'] = [
    '#type'        => 'details',
    '#title'       => t('Scroll To Top Icon'),
    '#open' => TRUE,
  ];
  $form['components']['scrolltotop']['scrolltotop_icon']['scrolltotop_icon_style'] = [
    '#type'          => 'radios',
    '#title'         => t('<p>Select Arrow Icon</p>'),
    '#options' => array(
      '&#129121;' => t('<span style="font-size: 2rem">&#129121;</span><br /><br />'),
      '&#x21e1;' => t('<span style="font-size: 2rem; font-weight: 700">&#x21e1;</span><br /><br />'),
      '&#11165;' => t('<span style="font-size: 2rem">&#11165;</span><br /><br />'),
      '&#129041;' => t('<span style="font-size: 2rem">&#129041;</span><br /><br />'),
      '&#9650;' => t('<span style="font-size: 2rem">&#9650;</span><br /><br />'),
    ),
    '#default_value' => theme_get_setting('scrolltotop_icon_style', 'eduxpro'),
  ];
  $form['components']['scrolltotop']['scrolltotop_shape_section'] = [
    '#type'        => 'details',
    '#title'       => t('Icon Shape'),
    '#open' => TRUE,
  ];
  $form['components']['scrolltotop']['scrolltotop_shape_section']['scrolltotop_icon_size'] = [
    '#type'   => 'number',
    '#title'  => t('Icon Size (px)'),
    '#default_value' => theme_get_setting('scrolltotop_icon_size', 'eduxpro'),
    '#description' => t('Default value is 20px.<p><hr /></p>'),
  ];
  $form['components']['scrolltotop']['scrolltotop_shape_section']['scrolltotop_icon_radius'] = [
    '#type'   => 'textfield',
    '#title'  => t('Background Border Radius'),
    '#default_value' => theme_get_setting('scrolltotop_icon_radius', 'eduxpro'),
    '#description' => t('Default value is 50%.<br />You can use <strong>px</strong> (example 10px) or <strong>percentage</strong> (example 50%) unit.<p><hr /></p>'),
  ];
  $form['components']['scrolltotop']['scrolltotop_position_section'] = [
    '#type'        => 'details',
    '#title'       => t('Icon Position'),
    '#open' => TRUE,
  ];
  $form['components']['scrolltotop']['scrolltotop_position_section']['scrolltotop_position'] = [
    '#type'          => 'radios',
    '#title'       => t('Left or Right Position'),
    '#options' => array(
    	'left' => t('<span style="' . $button . '">LEFT</span>'),
      'right' => t('<span style="' . $button . '">RIGHT</span>'),
    ),
    '#default_value' => theme_get_setting('scrolltotop_position', 'eduxpro'),
    '#description' => t('Default value is Right.<br /><p><hr /></p>'),
  ];
  $form['components']['scrolltotop']['scrolltotop_position_section']['scrolltotop_bottom'] = [
    '#type'   => 'number',
    '#min'  => 0,
    '#step' => 1,
    '#title'  => t('Bottom Position (px)'),
    '#default_value' => theme_get_setting('scrolltotop_bottom', 'eduxpro'),
    '#description' => t('Default value is 10px'),
  ];
  /*
   * Color
   */
  include_once 'inc/settings/color.php'; 

  /**
   * Insert Codes
   */
  include_once 'inc/settings/codes.php';

  // Settings under support tab.
  $form['support']['info'] = [
    '#type'        => 'details',
    '#open' => true,
    '#title'       => t('Edu X Pro Theme Support'),
    '#description' => t('<h4>Edu X Pro Documentation</h4>
    <p>Please check our full documentation for detailed information on how to use Edu X Pro theme.<br /><a href="https://www.drupar.com/doc/eduxpro" target="_blank">Edu X Theme Documentation</a>.</p>
    <hr />
    <h4>The X Pro Documentation</h4>
    <p>This theme uses <strong>The X Pro</strong> as the base theme. So, many things are coved under <a href="https://www.drupar.com/doc/thexpro" target="_blank">The X Pro Documentation</a></p>
    <hr />
    <h4>Open Support Ticket</h4>
    <p>For any support related to this theme, please <a href="https://www.drupar.com/node/add/ticket" target="_blank">open a support ticket</a>.</p>'),
  ];

  /**
   * Settings under License tab.
   */
  $form['license']['info'] = [
    '#type'        => 'fieldset',
    '#title'       => t('License Type'),
    '#description' => t('<p>Your theme license is: <strong>Single Domain License</strong></p>
    <p>You are allowed to use this theme on a single website. For details, please refer to <a href="https://www.drupar.com/theme-license" target="_blank">Theme License Details</a></p>
    <hr /><br /><a href="https://www.drupar.com/upgrade/edux" target="_blank">Upgrade to unlimited domain license</a>. Upgrade fee is $70 only.'),
  ];
  $form['license']['upgrade'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Upgrade'),
    '#description' => t('<p>You can upgrade to unlimited domain license. Upgrade price is $70 only.</p><p><hr /></p><p><a href="https://www.drupar.com/upgrade/edux" target="_blank">Upgrade to unlimited domain license</a>.</p>'),
  ];
/*
 * Classic slider
 */
$form['slider']['slider_classic_tab']['slider_image_section'] = [
  '#type'        => 'fieldset',
  '#title'       => t('Slider Image'),
];
$form['slider']['slider_classic_tab']['slider_image_section']['slider_image_bg'] = [
  '#type'          => 'checkbox',
  '#title'         => t('Slider Image Blob Background'),
  '#default_value' => theme_get_setting('slider_image_bg', 'eduxpro'),
  '#description'   => t("Check this option to show the animated blob backgroung behind the slider image. Uncheck to hide."),
];
$form['slider']['slider_classic_tab']['slider_image_section']['slider_image'] = [
  '#type'          => 'managed_file',
  '#upload_location' => 'public://',
  '#upload_validators' => array(
    'file_validate_extensions' => array('gif png jpg jpeg svg'),
  ),
  '#title'  => t('<p>Upload Slider Image</p>'),
  '#default_value'  => theme_get_setting('slider_image', 'eduxpro'),
  '#description'   => t('<p><hr /></p>This image will be aligned in right side of the slider.'),
];
$form['slider']['slider_classic_tab']['slider_code'] = [
  '#type'          => 'textarea',
  '#title'         => t('Slider Code'),
  '#default_value' => theme_get_setting('slider_code', 'eduxpro'),
  '#description'   => t('Please refer to this <a href="https://www.drupar.com/doc/eduxpro/homepage-slider" target="_blank">documentation page</a> for slider code tutorial.'),
];
/*
 * Single Slide
 */
$form['slider']['slider_single_tab']['slider_single_image_section'] = [
  '#type'        => 'fieldset',
  '#title'       => t('Slider Background Image'),
];
$form['slider']['slider_single_tab']['slider_single_image_section']['single_slide_image'] = [
  '#type'          => 'managed_file',
  '#upload_location' => 'public://',
  '#upload_validators' => array(
    'file_validate_extensions' => array('gif png jpg jpeg svg'),
  ),
  '#title'  => t('<p>Upload Slider Background Image</p>'),
  '#field_suffix' => theme_get_setting('single_slide_image_path', 'eduxpro'),
  '#default_value'  => theme_get_setting('single_slide_image', 'eduxpro'),
  '#description'   => t('<p><hr /></p>Recommended image size is 1920px X 1280px.'),
];
$form['slider']['slider_single_tab']['single_slide_code'] = [
  '#type'          => 'textarea',
  '#title'         => t('Slider Code'),
  '#default_value' => theme_get_setting('single_slide_code', 'eduxpro'),
  '#description'   => t('Please refer to this <a href="https://www.drupar.com/doc/eduxpro/homepage-slider" target="_blank">documentation page</a> for slider code tutorial.'),
];
// End form.
}
