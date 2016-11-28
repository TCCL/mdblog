<?php

namespace Drupal\mdblog\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'markdown' field type.
 *
 * @FieldType(
 *   id = "markdown",
 *   label = @Translation("Markdown"),
 *   description = @Translation("Stores an address."),
 *   category = @Translation("Custom"),
 *   default_widget = "MarkdownDefaultWidget",
 *   default_formatter = "MarkdownDefaultFormatter"
 * )
 */
class Markdown extends FieldItemBase {
    public static function propertyDefinitions(StorageDefinition $storage) {
        $props = array();
        $props['source'] = DataDefinition::create('string')->setLabel(t('Source Markdown'));
        $props['result'] = DataDefinition::create('string')->setLabel(t('Compiled Markdown'));
        return $props;
    }

    public static function schema(StorageDefinition $storage) {
        $columns = array();

        $columns['source'] = array(
            'type' => 'text',
            'length' => 131072,
        );
        $columns['result'] = array(
            'type' => 'text',
            'length' => 131072,
        );

        return array(
            'columns' => $columns,
            'indexes' => array(),
        );
    }

    public function isEmpty() {
        return empty($this->get('source'));
    }
}