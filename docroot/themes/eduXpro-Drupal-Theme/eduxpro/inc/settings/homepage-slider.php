<?php
$form['slider']['slider_tab'] = [
  '#type'  => 'vertical_tabs',
];
// Slider -> Main tab
$form['slider']['slider_main_tab'] = [
  '#type'        => 'details',
  '#title'       => t('Slider Options'),
  '#group' => 'slider_tab',
];
// Slider -> Classic tab
$form['slider']['slider_classic_tab'] = [
  '#type'        => 'details',
  '#title'       => t('Classic Slider'),
  '#group' => 'slider_tab',
];
// Slider -> Single slide tab
$form['slider']['slider_single_tab'] = [
  '#type'        => 'details',
  '#title'       => t('Single Slide'),
  '#group' => 'slider_tab',
];
// Slider -> Layered slider tab
$form['slider']['slider_layered_tab'] = [
  '#type'        => 'details',
  '#title'       => t('Layered Slider'),
  '#group' => 'slider_tab',
];
/*
 * Main tab
 */
// Slider -> Enable / disable slider
$form['slider']['slider_main_tab']['slider_enable_option'] = [
  '#type'        => 'fieldset',
  '#title'       => t('Enable Slider'),
];
$form['slider']['slider_main_tab']['slider_enable_option']['slider_show'] = [
  '#type'          => 'checkbox',
  '#title'         => t('Show Slider on Homepage'),
  '#default_value' => theme_get_setting('slider_show', 'eduxpro'),
  '#description'   => t("Check this option to show slider on homepage. Uncheck to hide."),
];
// Slider -> Select slider type
$form['slider']['slider_main_tab']['slider_type_section'] = [
  '#type'        => 'fieldset',
  '#title'       => t('Slider Style'),
];
$form['slider']['slider_main_tab']['slider_type_section']['slider_type'] = [
  '#type'        => 'radios',
  '#title'       => t('Select Slider Style'),
  '#options' => array(
    'slider-classic' => t('Classic'),
    'slider-single' => t('Single Slide'),
    'slider-layered' => t('Layered'),
  ),
  '#default_value' => theme_get_setting('slider_type', 'eduxpro'),
  '#description'   => t('You can customize each slider type in their respective tab.'),
];


