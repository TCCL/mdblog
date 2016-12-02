<?php

/**
 * @file
 * Contains \Drupal\mdbog\Plugin\Field\FieldFormatter\MarkdownTrimmedFormatter.
 */

namespace Drupal\mdblog\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'MarkdownTrimmedFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "markdown_trimmed",
 *   label = @Translation("Trimmed"),
 *   field_types = {
 *     "markdown"
 *   }
 * )
 */
class MarkdownTrimmedFormatter extends FormatterBase {
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
     * {@inheritdoc}
     */
    public static function defaultSettings() {
        return array(
            'trim_length' => '600',
        ) + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state) {
        // NOTE: we're lying a little when we say X characters since we actually
        // go off of an offset into the HTML. So sorry about this...
        $element['trim_length'] = array(
            '#title' => t('Trimmed limit'),
            '#type' => 'number',
            '#field_suffix' => t('characters'),
            '#default_value' => $this->getSetting('trim_length'),
            '#description' => t('Field %label will be truncated to this length.', array('%label' => $this->fieldDefinition->getLabel())),
            '#min' => 1,
            '#required' => TRUE,
        );
        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function settingsSummary() {
        $summary = array();
        $summary[] = t('Trimmed limit: @trim_length characters', array('@trim_length' => $this->getSetting('trim_length')));
        return $summary;
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
        $trimmed = $this->trimMarkup($item->result);
        if (empty($this->elementType)) {
            return array(
                '#type' => 'markup',
                '#markup' => $trimmed,
            );
        }

        // Assume the site has a theme module of '$this->elementType' that can
        // format the blog post (typically by just outputting the html and
        // including a CSS library to style the post).
        return array(
            '#type' => $this->elementType,
            '#post' => $trimmed,
        );
    }

    /**
     * This function attempts to trim markup to the nearest configured length.
     * It approximates this by finding the nearest closing HTML tag.
     *
     * @param  string $markup
     *
     * @return [type]         [description]
     */
    private function trimMarkup($markup) {
        $length = $this->getSetting('trim_length');
        if (empty($length)) {
            $length = 0;
        }

        // Find the close tag nearest to the desired length offset.
        $offset = 0;
        while ($offset < $length) {
            if (!preg_match('/<\/[^>]+>/',$markup,$matches,PREG_OFFSET_CAPTURE,$offset)) {
                break;
            }
            list($match,$offset) = $matches[0];
            $offset += strlen($match);
        }

        error_log($length);
        $trimmed = substr($markup,0,$offset);
        if ($offset < strlen(trim($markup))) {
            $trimmed .= "<h1>...</h1>";
        }
        return $trimmed;
    }
}
