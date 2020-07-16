<?php

namespace Drupal\shortcode_svg\Plugin;

/**
 * Provides svg icon location and code for usage outside shortcode.
 *
 * @ShortcodeIcon(
 *   id = "ShortcodeIcon",
 *   title = @Translation("Shortcode Icon"),
 *   description = @Translation("Path/usage of shortcode icon")
 * )
 */
class ShortcodeIcon {
  /**
   * Image Location.
   */
  private $svgLocation;

  /**
   * Load icon location.
   */
  public function __construct() {
    $config = \Drupal::config('shortcode_svg.settings');
    $fid = $config->get('image');
    $file = \Drupal\file\Entity\File::load($fid);
    $path = file_url_transform_relative(file_create_url($file->getFileUri()));
    $this->svgLocation = $path;
  }

  /**
   * Function to output icon.
   */
  public function setIcon($icon, $width, $color) {
    $path = $this->getSvg();
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 34 34' class='svg-icon $icon' width='$width'>
      <use fill='$color' xlink:href='$path#$icon'></use>
      </svg>";
  }

  /**
   * Return Location.
   */
  public function getSvg() {
    return $this->svgLocation;
  }
}
