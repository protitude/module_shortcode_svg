<?php

/**
 * @file
 * Contains \Drupal\shortcode_svg\Controller\ShortcodeSvgController.
 */

namespace Drupal\shortcode_svg\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Drupal\Component\Utility\Xss;

/**
 * Controller routines for svg icons from sprite.
 */
class ShortcodeSvgController extends ControllerBase {

  /**
   * Routes for svg icons.
   *
   * @return array
   *   A render array representing the zap page content.
   */
  public function content() {
    $config = $this->config('shortcode_svg.settings');
    $fid = $config->get('image');
    $colors = $config->get('colors');
    $width = $config->get('width');
    $color = [];
    if (isset($colors)) {
      $color_lines  = explode(PHP_EOL, $colors);
      foreach ($color_lines as $color_line) {
        if (!empty(rtrim($color_line))) {
          $parts = explode(':', $color_line);
          $name = trim($parts[0]);
          $hex = trim($parts[1]);
          $color[$hex] = $name;
        }
      }
    }
    $file = \Drupal\file\Entity\File::load($fid);
    $path = file_url_transform_relative(file_create_url($file->getFileUri()));
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $xml = new \XMLReader;
    $xml->open($host . $path);
    while ($xml->read()) {
      if ($xml->nodeType === \XMLReader::ELEMENT && $xml->name == 'g') {
        $ids[] = $xml->getAttribute('id');
      }
    }
    $content = '<h4>Icons</h4><ul class="svg_list">';
    foreach ($ids as $icon_name) {
      $content .= sprintf(
        '<li id="%s"><svg viewBox="0 0 34 34" class="icon">
        <use xlink:href="%s#%s"></use>
        </svg></li>',
    $icon_name,
      $path,
      $icon_name
      );
    }
    $content .= '</ul><div class="fixed-bottom"><button type="button" class="copy-icon" data-clipboard="I like turtles">Copy shortcode to clipboard</button></div>';

    $form['alt'] = [
      '#type' => 'textfield',
      '#title' => t('Image title'),
      '#maxlength' => 100,
      '#suffix' => $this->t('Optional alt text for the icon. Only use if necessary for someone to understand the context of the icon.'),
      '#attributes' => [
        'class' => [
          'svg-alt',
        ]
      ],
    ];

    $form['width'] = [
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#value' => $width,
      '#maxlength' => 10,
      '#suffix' => $this->t('Only place number into this box to set the width of the icon'),
      '#attributes' => [
        'class' => [
          'svg-width',
        ]
      ],
    ];

    $form['colors'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => $color,
      '#attributes' => [
        'class' => [
          'svg-color',
        ]
      ],
    ];

    $form['icon_list'] = [
      '#markup' => $content,
      '#allowed_tags' => ['div', 'p', 'h4', 'strong', 'svg', 'use', 'ul', 'li', 'button'],
    ];
    $form['#attached']['library'][] = 'shortcode_svg/icon_form';
    $form['#attached']['library'][] = 'shortcode_svg/clipboard';

    return $form;
  }
}
