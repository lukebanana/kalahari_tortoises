<?php

/**
 * @file
 * Extend core fields with some helper functions to reduce code complexity within views and ctools plugins.
 */


/**
 * Fake an instance of a field.
 *
 * @param $field_name
 *   The unique name for this field no matter what entity/bundle it may be used on.
 * @param $view_mode
 *   We're building a new view mode for this function.  Defaults to ctools, but we expect developers to actually name this something meaningful.
 * @param $formatter
 *   The formatter key selected from the options provided by field_ui_formatter_options().
 * @param $formatter_settings
 *   An array of key value pairs.  These will be used as #default_value for the form elements generated by a call to hook_field_formatter_settings_form() for this field type.
 *   Typically we'll pass an empty array to begin with and then pass this information back to ourselves on form submit so that we can set the values for later edit sessions.
 */
function ctools_fields_fake_field_instance($field_name, $view_mode = 'ctools', $formatter, $formatter_settings) {
  $field = field_read_field($field_name);

  $field_type = field_info_field_types($field['type']);

  return array(
    // Build a fake entity type and bundle.
    'field_name' => $field_name,
    'entity_type' => 'ctools',
    'bundle' => 'ctools',

    // Use the default field settings for settings and widget.
    'settings' => field_info_instance_settings($field['type']),
    'widget' => array(
      'type' => $field_type['default_widget'],
      'settings' => array(),
    ),

    // Build a dummy display mode.
    'display' => array(
      $view_mode => array(
        'type' => $formatter,
        'settings' => $formatter_settings,
      ),
    ),

    // Set the other fields to their default values.
    // @see _field_write_instance().
    'required' => FALSE,
    'label' => $field_name,
    'description' => '',
    'deleted' => 0,
  );
}

/**
 * Helper function for calling hook_field_formatter_settings_form() without needing to load an instance of the field.
 *
 * @param $field
 *   A fully loaded field.
 * @param $formatter_type
 *   The formatter key selected from the options provided by field_ui_formatter_options().
 * @param $form
 *   The full form from the function that's calling this function.
 * @param $form_state
 *   The full form_state from the function that's calling this function.
 * @param $view_mode
 *   We're passing a view mode from this function to the fake instance we're creating.  Defaults to ctools, but we expect developers to actually name this something meaningful.
 */
function ctools_fields_get_field_formatter_settings_form($field, $formatter_type, &$form, $form_state, $view_mode = 'ctools') {
  $conf = $form_state['conf'];
  $formatter = field_info_formatter_types($formatter_type);
  if (isset($formatter['settings'])) {
    $conf['formatter_settings'] += $formatter['settings'];
  }
  $function = $formatter['module'] . '_field_formatter_settings_form';
  if (function_exists($function)) {
    $instance = ctools_fields_fake_field_instance($field['field_name'], $view_mode, $formatter_type, $conf['formatter_settings']);
    $settings_form = $function($field, $instance, $view_mode, $form, $form_state);
    if ($settings_form) {
      $form['ctools_field_list']['#value'][] = $field;
      $form += $settings_form;
    }
  }
}

/**
 * Helper function for generating all the formatter information associated with
 * any fields.
 * Especially useful for determining the fields that will be added to form that
 * executes hook_field_formatter_settings_form().
 *
 * @param $fields
 *   An array of fully loaded fields.
 */
function ctools_fields_get_field_formatter_info($fields) {
  $info = array();
  $field_info = module_invoke_all('field_formatter_info');
  foreach ($fields as $field) {
    foreach ($field_info as $format_name => $formatter_info) {
      if (in_array($field['type'], $formatter_info['field types'])) {
        $info += array($format_name => $formatter_info);
      }
    }
  }
  drupal_alter('field_formatter_info', $info);
  return $info;
}

/**
 * Returns the label of a certain field.
 *
 * Cribbed from Views.
 */
function ctools_field_label($field_name) {
  $label_counter = array();
  // Count the amount of instances per label per field.
  $instances = field_info_instances();
  foreach ($instances as $entity_type) {
    foreach ($entity_type as $bundle) {
      if (isset($bundle[$field_name])) {
        $label_counter[$bundle[$field_name]['label']] = isset($label_counter[$bundle[$field_name]['label']]) ? ++$label_counter[$bundle[$field_name]['label']] : 1;
      }
    }
  }
  if (empty($label_counter)) {
    return $field_name;
  }
  // Sort the field lables by it most used label and return the most used one.
  arsort($label_counter);
  $label_counter = array_keys($label_counter);
  return $label_counter[0];
}
