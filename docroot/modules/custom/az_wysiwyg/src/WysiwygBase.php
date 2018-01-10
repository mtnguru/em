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

  static public function loadGlossary() {
    $terms = &drupal_static(__FUNCTION__);
    if (isset($terms)) {
      return $terms;
    }
    $terms = [];
    // DB Query for all glossary terms.
    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->addfield('nfd', 'nid');
    $query->addfield('nfd', 'title');
    $query->condition('nfd.type', 'glossary');

    // Join in the tooltip text field.
    $query->join('node__field_tooltip', 'nft', 'nft.entity_id = nfd.nid');
    $query->addfield('nft', 'field_tooltip_value', 'tooltip');

    $results = $query->execute()->fetchAllAssoc('title');
    // Build terms array.
    foreach ($results as $result) {
      $result->url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $result->nid);
      $terms[strtolower($result->title)] = $result;
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
    if ($node->hasField('field_topics')) {
      $nids = $node->field_topics->getValue();
      foreach ($topics as $name => $topic) {
        if (isset($topic->in_text)) {
          $found = false;
          foreach ($nids as $i => $val) {
            if ($val['target_id'] == $topic->nid) {
              $found = TRUE;
            }
          }
          if (!$found) {
            $node->field_topics->appendItem($topic->nid);
          }
        }
      }
    }
  }

  static public function extractTerms($text, &$terms) {
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
      if (!empty($terms[$name])) {
        $terms[$name]->in_text = true;
      }
    }
  }
}
