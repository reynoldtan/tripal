<?php

class sio__references_widget extends ChadoFieldWidget {

  // The default lable for this field.
  public static $default_label = 'References';

  // The list of field types for which this formatter is appropriate.
  public static $field_types = ['sio__references'];

  /**
   *
   * @see TripalFieldWidget::form()
   */
  public function form(&$widget, &$form, &$form_state, $langcode, $items, $delta, $element) {
    parent::form($widget, $form, $form_state, $langcode, $items, $delta, $element);


  }

  /**
   *
   * @see TripalFieldWidget::submit()
   */
  public function submit($form, &$form_state, $entity_type, $entity, $langcode, $delta) {

  }
}
