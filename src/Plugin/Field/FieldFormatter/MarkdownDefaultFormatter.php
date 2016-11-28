<?php

namespace Drupal\mdblog\Plugin\Field\FieldFormatter;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'MarkdownDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "MarkdownDefaultFormatter",
 *   label = @Translation("Markdown"),
 *   field_types = {
 *     "markdown"
 *   }
 * )
 */
class MarkdownDefaultFormatter extends FormatterBase {
    /**
     * Define how the field type is shown. We only display the result markup
     * component of the field entry for presentation.
     */
    public function viewElements(FieldItemListInterface $item,$langcode) {
        $elements = array();

        foreach ($items as $delta => $item) {
            $elements[$delta] = array(
                '#type' => 'markup',
                '#markup' => $item->result,
            );
        }

        return $elements;
    }
}
