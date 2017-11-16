<?php

namespace Drupal\az_wysiwyg;

use Drupal\Component\Utility\Html;
use DOMXPath;
use Drupal\Component\Utility\Unicode;

/**
 * Base implementation of tooltip filter type plugin.
 */
class WysiwygBase {


  /**
   * Cleanup and truncate tip text.
   */
  static private function sanitizeTip($tip) {

    // Get rid of HTML.
    $tip = strip_tags($tip);

    // Maximise tooltip text length.
    $tip = Unicode::truncate($tip, 300, TRUE, TRUE);

    return $tip;
  }

  static public function loadTopics() {
    $terms = [];

    // Query for topics taxonomy term data.
    $query = \Drupal::database()->select('taxonomy_term_field_data', 'tfd');
    $query->addfield('tfd', 'tid', 'id');
    $query->addfield('tfd', 'name');
    $query->addfield('tfd', 'name', 'name_norm');
    $query->addField('tfd', 'description__value', 'description');
    $query->condition('tfd.vid', 'topics', 'IN');

    $query->leftjoin('taxonomy_term__field_include_in_glossary', 'tfg', 'tfg.entity_id = tfd.tid');
    $query->addField('tfg', 'field_include_in_glossary_value', 'in_glossary');
    $query->condition('tfg.field_include_in_glossary_value', '1');

    $query->leftjoin('taxonomy_term__field_tooltip', 'tft', 'tft.entity_id = tfd.tid');
    $query->addField('tft', 'field_tooltip_value', 'tooltip');

    $results = $query->execute()->fetchAllAssoc('name_norm');
    // Build terms array.
    foreach ($results as $result) {
      // Make name_norm lowercase, it seems not possible in PDO query?
      $result->name_norm = strtolower($result->name_norm);
      $result->url = '/taxonomy/term/' . $result->id;
      $terms[$result->name_norm] = $result;
    }
    return $terms;
  }

  /**
   * Add topics to node
   *
   * @param $node
   * @param $matched
   */
  static public function addTopicsToNode(&$node, $topics) {
    return;
    $tids = $node->field_topics->getValue();
    foreach ($topics as $name => $topic) {
      if (isset($topic->in_text)) {
        $found = false;
        foreach ($tids as $i => $val) {
          if ($val['target_id'] == $topic->id) {
            $found = TRUE;
          }
        }
        if (!$found) {
          $node->field_topics->appendItem($topic->id);
        }
      }
    }
  }

  static public function extractTopics($text, &$topics) {
    $topicReg = '/&lt;topic(.*?)&gt;(.*?)&lt;\/topic&gt;/';
//  $topicReg = '/&lt;([a-z]+) *(.*?)&gt;(.*?)&lt;\/([a-z]+?)&gt;/';
    // Remove &nbsp; characters that do not have a space before or after them.
    $text = preg_replace('/([^ ])\&nbsp;([^ ])/', '$1 $2', $text);

    $hitcount = preg_match_all($topicReg, $text, $matches, PREG_OFFSET_CAPTURE);

    foreach ($matches[0] as $key => $match) {
      $name = strtolower($matches[2][$key][0]);
      if (!empty($matches[1][$key][0])) {
        if (preg_match('/name=\"*([a-z ]+)\"*/', $matches[1][$key][0], $attributes)) {
          $name = strtolower($attributes[1]);
        }
      }
      if (!empty($topics[$name])) {
        $topics[$name]->in_text = true;
      }
    }
  }
}
