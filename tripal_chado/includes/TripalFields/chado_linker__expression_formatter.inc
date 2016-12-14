<?php

class chado_linker__expression_formatter extends TripalFieldFormatter {
  // The default lable for this field.
  public static $label = 'Expression';

  // The list of field types for which this formatter is appropriate.
  public static $field_types = array('chado_linker__expression');

  // The list of default settings for this formatter.
  public static $settings = array();
  /**
   *
   * @see TripalFieldFormatter::settingsForm()
   */
  public function settingsForm($view_mode, $form, &$form_state) {

  }

  /**
   *
   * @see TripalFieldFormatter::view()
   */
  public function view(&$element, $entity_type, $entity, $langcode, $items, $display) {
    // Get the settings
    $settings = $display['settings'];
    
    $content = '';
    $rows = array();
    foreach ($items as $delta => $item) {
      if (!$item['value']) {
        continue;
      }
      // Iterate through all of the children of the $item['value']. Add each
      // one as an independent row in the table.
    
      foreach ($item['value'] as $key => $value) {
    
        // If this key is the name, then we want to link to the entity if one
        // exists.
        if ($key == 'name') {
          if (array_key_exists('entity', $item['value']) and $item['value']['$entity_id']) {
            list($entity_type, $entity_id) = explode(':', $item['value']['entity']);
            $value = l($value, "bio_data/" . $entity_id, array('attributes' => array('target' => "_blank")));
          }
        }
        // If this key is the publication then we want to get the citation
        // and link to the pub if an entity exits.
        if ($key == 'publication') {
          $pub = $value['Citation'];
          if (array_key_exists('publication', $item) and array_key_exists('entity', $item['publication'][0])) {
            $entity_id = $item['publication'][0]['entity_id'];
            $title =  $item['value']['publication']['Title'];
            $link = l($title, 'bio_data/' . $entity_id);
            $pub = preg_replace("/$title/", $link, $pub);
          }
          $value = $pub;
        }
        // Add the item as a new row.
        $rows[] = array(
          array(
            'data' => ucfirst(str_replace('_', ' ', $key)),
            'header' => TRUE,
            'width' => '20%',
          ),
          $value
        );
      }
    }
    $table = array(
      'header' => array(),
      'rows' => $rows,
      'attributes' => array(
        'id' => 'tripal_linker-table-expression-object',
        'class' => 'tripal-data-table'
      ),
      'sticky' => FALSE,
      'caption' => "",
      'colgroups' => array(),
      'empty' => 'There is no curated expression data.',
    );
    $content = theme_table($table);
    if (count($items) > 0) {
      // once we have our table array structure defined, we call Drupal's theme_table()
      // function to generate the table.
      $element[0] = array(
        '#type' => 'markup',
        '#markup' => $content,
      );
    }
  }
}