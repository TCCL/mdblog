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
     * The theme element type to use to format the blog post item. This option
     * is pulled from the module config and may be empty, in which case the
     * render arrays produced simply contain the raw markup.
     *
     * @var string
     */
    private $elementType = "";

    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items,$langcode) {
        // Pull element type config.
        $config = \Drupal::config('mdblog.settings');
        $this->elementType = $config->get('mdblog.themetype');

        $elements = array();
        foreach ($items as $delta => $item) {
            $elements[$delta] = $this->getRenderArray($item);
        }

        return $elements;
    }

    /**
     * Creates a render array for the specified 'markdown_post' field item.
     *
     * @param  FieldItem $item
     *  A Drupal field item to turn into a render array.
     *
     * @return array
     *  A Drupal render array
     */
    private function getRenderArray($item) {
        if (empty($this->elementType)) {
            return array(
                '#type' => 'markup',
                '#markup' => $item->result,
            );
        }

        // Assume the site has a theme module of '$this->elementType' that can
        // format the blog post (typically by just outputting the html and
        // including a CSS library to style the post).
        return array(
            '#type' => $this->elementType,
            '#post' => $item->result,
        );
    }
}
