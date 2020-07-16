<?php

namespace Drupal\shortcode_svg\Plugin\Shortcode;

use Drupal\Core\Language\Language;
use Drupal\shortcode\Plugin\ShortcodeBase;
use Drupal\shortcode_svg\Plugin\ShortcodeIcon;
use Drupal\Component\Utility\Xss;

/**
 * Provides a shortcode for SVG image sprite.
 *
 * @Shortcode(
 *   id = "svg",
 *   title = @Translation("Svg Shortcode"),
 *   description = @Translation("Svg shortcode")
 * )
 */
class SvgShortcode extends ShortcodeBase {
  /**
   * {@inheritdoc}
   */
  public function process(array $attributes, $text, $langcode = Language::LANGCODE_NOT_SPECIFIED) {
    $attributes = $this->getAttributes(
      [
        'name' => '',
        'alt' => '',
        'width' => '',
        'color' =>  '',
      ],
      $attributes
    );

    $name = !empty($attributes['name'])?Xss::filter($attributes['name']):null;

    if ($name !== null ) {
      $alt = !empty($attributes['alt'])?Xss::filter($attributes['alt']):null;
      $title = "";
      $label = "";
      if ($alt !== null ) {
        $title = "<title id='svg_title'>$alt</title>";
        $label = "aria-labelledby='svg_title'";
      }
      $width = !empty($attributes['width'])?Xss::filter($attributes['width']):null;
      $color = !empty($attributes['color'])?Xss::filter($attributes['color']):null;

      $icon = new ShortcodeIcon();
      $path = $icon->getSvg();

      $content = sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34" %s class="svg-icon %s" width="%s">
        %s<use fill="#%s" xlink:href="%s#%s"></use>
        </svg>',
      $label,
        $name,
        $width,
        $title,
        $color,
        $path,
        $name
      );

      return $content;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = false) {
    $output = [];
    $output[] = '<p><strong>' . $this->t('[svg name="icon_name" alt="optional description" width="number only" color="hex code no #"][/svg]') . '</strong> ';

    return implode(' ', $output);
  }

}
