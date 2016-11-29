<?php

/**
 * @file
 * Contains \Drupal\mdblog\Plugin\Field\FieldFormatter\MarkdownDefaultFormatter.
 */

namespace Drupal\mdblog\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'MarkdownDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "markdown_default",
 *   label = @Translation("Default"),
 *   field_types = {
 *     "markdown"
 *   }
 * )
 */
class MarkdownDefaultFormatter extends FormatterBase {
    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items,$langcode) {
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
