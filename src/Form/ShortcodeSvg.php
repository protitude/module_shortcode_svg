<?php

namespace Drupal\shortcode_svg\Form;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
use enshrined\svgSanitize\Sanitizer;

/**
 * Upload SVG sprite.
 *
 * @ingroup shortcode_svg
 */
class ShortcodeSvg extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'render_shortcode_svg_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [
      'shortcode_svg.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('shortcode_svg.settings');
    $stored_image = null !== $config->get('image')?$config->get('image'):'';
    $colors = null !== $config->get('colors')?$config->get('colors'):'';
    $width = null !== $config->get('width')?$config->get('width'):'';
    $polyfill = $config->get('polyfill')[0] == 1?$config->get('polyfill'):'';

    $url = Url::fromRoute('shortcode_svg.list');
    $icon_link = Link::fromTextAndUrl(t('on this page'), $url)->toString();
    $description = sprintf(
      '%s. %s %s.',
      t('Upload a layered svg file below, with each icon having it\'s own selectable id'),
      t('You can view your icons'),
      $icon_link
    );

    $form['svg_description'] = [
      '#markup' => $description,
    ];

    $form['svg_image'] = [
      '#title' => t('Svg Image'),
      '#type' => 'managed_file',
      '#description' => t('Upload SVG image here'),
      '#upload_location' => 'public://svg/',
      '#upload_validators' => [
        'file_validate_extensions' => array('svg'),
        'file_validate_size' => array(3000000)
      ],
      '#default_value' => [$stored_image],
    ];

    $form['svg_colors'] = [
      '#type' => 'textarea',
      '#title' => t("Icon Colors"),
      '#description' => t('Create a dropdown with your site colors. List out the colors you want available, one per line. Write as color colon hex code name as follows <br />  white: ffffff'),
      '#default_value' => $colors,
    ];

    $form['svg_width'] = [
      '#type' => 'textfield',
      '#title' => t('Width'),
      '#description' => t('Default icon width'),
      '#default_value' => $width,
    ];

    $form['svg_polyfill'] = [
      '#type' => 'checkbox',
      '#title' => t('Use ie11 polyfill'),
      '#description' => t('Check this if support for ie11 is necessary.'),
      '#default_value' => $polyfill,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Implements form validation.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $sanitizer = new Sanitizer();
    $image = Xss::filter($form_state->getValue('svg_image')[0]);
    $colors = Xss::filter($form_state->getValue('svg_colors'));
    $width = Xss::filter($form_state->getValue('svg_width'));
    $polyfill = Xss::filter($form_state->getValue('svg_polyfill'));
    $file = File::load($image);
    $uri = file_create_url($file->getFileUri());
    $image_path = file_url_transform_relative($uri);
    $svg = file_get_contents($uri);
    // Sanitize SVG text
    $cleanSVG = $sanitizer->sanitize($svg);
    $f=fopen($_SERVER['DOCUMENT_ROOT'] . $image_path, 'w');
    fwrite($f, $cleanSVG);
    fclose($f);
    // Overwrite uploaded file with sanitized file
    $file->setPermanent();
    $file->save();
    // Retrieve the configuration.
    $this->configFactory->getEditable('shortcode_svg.settings')
                        ->set('image', $file->id())
                        ->set('colors', $colors)
                        ->set('width', $width)
                        ->set('polyfill', $polyfill)
                        ->save();

    parent::submitForm($form, $form_state);
  }

}

