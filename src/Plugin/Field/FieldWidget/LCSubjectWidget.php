<?php

namespace Drupal\lc_subject_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'lcsubject_widget' widget.
 *
 * @FieldWidget(
 *   id = "lcsubject_widget",
 *   label = @Translation("LC Subject widget type"),
 *   field_types = {
 *     "lcsubject_field"
 *   }
 * )
 */
class LCSubjectWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 60,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['size'] = [
      '#type' => 'number',
      '#title' => t('Size of textfield'),
      '#default_value' => $this->getSetting('size'),
      '#required' => TRUE,
      '#min' => 1,
    ];
    $elements['placeholder'] = [
      '#type' => 'textfield',
      '#title' => t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['value'] = $element + [
        '#type' => 'textfield',
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => $this->getFieldSetting('max_length'),
        '#autocomplete_route_name' => 'lc_subject_field.autocomplete',
        '#autocomplete_route_parameters' => ['candidate' => 'lc_subject_field'],
        '#ajax' => [
          'event' => 'autocomplete-select'
        ],
    ];
    $form['#attached']['library'][] = 'lc_subject_field/lc-autocomplete';
    $element['url'] = [
      '#type' => 'textfield',
        //'#default_value' => isset($items[$delta]->url) ? $items[$delta]->url : NULL,
        '#size' => $this->getSettings('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => $this->getFieldSetting('max_length'),
        '#ajax' => [
          'event' => 'autocomplete-select'
        ]
      ];
    $element['url']['#attributes']['class'][] = 'subject-url-input';
    return $element;
  }

}
