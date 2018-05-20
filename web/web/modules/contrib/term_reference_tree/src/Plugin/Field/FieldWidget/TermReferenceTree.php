<?php /**
 * @file
 * Contains \Drupal\term_reference_tree\Plugin\Field\FieldWidget\TermReferenceTree.
 */

namespace Drupal\term_reference_tree\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Vocabulary;

/**
 * @FieldWidget(
 *   id = "term_reference_tree",
 *   label = @Translation("Term reference tree"),
 *   field_types = {"entity_reference"},
 *   multiple_values = TRUE
 * )
 */
class TermReferenceTree extends WidgetBase {

 /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $handler_settings = $this->getFieldSetting('handler_settings');
    $vocabularies = Vocabulary::loadMultiple($handler_settings['target_bundles']);

    $element['#type'] = 'checkbox_tree';
    $element['#default_value'] = $items->getValue();
    $element['#vocabularies'] = $vocabularies;
    $element['#max_choices'] = $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();
    $element['#leaves_only'] = FALSE;
    $element['#value_key'] = 'target_id';
    $element['#max_depth'] = 0;
    $element['#start_minimized'] = TRUE;
    $element['#element_validate'] = [[get_class($this), 'validateTermReferenceTreeElement']];
    return $element;
  }

  /**
   * Form element validation handler for term reference form widget.
   */
  public static function validateTermReferenceTreeElement(&$element, FormStateInterface $form_state) {
    $items = _term_reference_tree_flatten($element, $form_state);
    $value = [];
    if ($element['#max_choices'] != 1) {
      foreach ($items as $child) {
        if (!empty($child['#value'])) {
          array_push($value, array($element['#value_key'] => $child['#value']));

          // If the element is leaves only and select parents is on, then automatically
          // add all the parents of each selected value.
          if ($element['#select_parents'] && $element['#leaves_only']) {
            foreach ($child['#parent_values'] as $parent_tid) {
              if (!in_array(array($element['#value_key'] => $parent_tid), $value)) {
                array_push($value, array($element['#value_key'] => $parent_tid));
              }
            }
          }
        }
      }
    }
    else {
      // If it's a tree of radio buttons, they all have the same value, so we can just
      // grab the value of the first one.
      if (count($items) > 0) {
        $child = reset($items);
        if (!empty($child['#value'])) {
          array_push($value, array($element['#value_key'] => $child['#value']));
        }
      }
    }
    if ($element['#required'] && empty($value)) {
      $form_state->setError($element, t('%name field is required.', array('%name' => $element['#title'])));
    }
    $form_state->setValueForElement($element, $value);
  }

}
