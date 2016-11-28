<?php

namespace Drupal\mdblog\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'MarkdownDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "MarkdownDefaultWidget",
 *   label = @Translation("Markdown entry"),
 *   field_types = {
 *     "markdown"
 *   }
 * )
 */
class MarkdownDefaultWidget extends WidgetBase {
    public function formElement(
        FieldItemListInterface $items,
        $delta,
        array $element,
        array &$form,
        FormStateInterface $formState)
    {
        // We only provide a field for the Markdown source. This is because the
        // backend will compile the source into the result. The "Markdown" field
        // will store the result, along with the original Markdown source for
        // obvious reasons.

        $element['source'] = array(
            '#type' => 'textarea',
            '#title' => t("Markdown Text Input"),
            '#default_value' => isset($items[$delta]->source) ?
                $items[$delta]->source : null,
            '#empty_value' => '',
            '#placeholder' => t('Markdown'),
        );

        return $element;
    }
}
