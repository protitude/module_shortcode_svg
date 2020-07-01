# Shortcode SVG

## Installation

To install this module, `composer require` it, or  place it in your modules
folder and enable it on the modules page. Visit
https://www.drupal.org/node/1897420 if you need further information.

## Requirements
This module requires the [Shortcode](https://www.drupal.org/project/shortcode)
module along with [Drupal](https://www.drupal.org/project/drupal). Outside of
Drupal, you'll need the library [enshrined/svg-sanitize]
(https://github.com/Keyamoon/svgxuse) in order to properly sanitize your SVG
image sprite. Also, the [svgxuse](https://github.com/Keyamoon/svgxuse) library
will be added, so if you check the box on the configuration page to polyfill
for internet explorer 11. These are included in the composer file as long as
you use composer to install this module.

## Configuration

This module can be configured in the Shortcode SVG sprite section
(/admin/config/content/shortcode_svg). In there you can upload the svg image
sprite, insert the default colors of the drop down to support for the icons,
set a default width to be offered, and finally you can check the 'Use ie11
polyfill in order to add a javascript polyfill to support ie11.

The shortcode can be broken down into the following:

`[svg name=android alt="Android" width=40 color=0073E6][/svg]`

The name is the name of the layer you are pointing to in the svg. Alt is a
title you can insert for screen readers. Width is how wide the icon will be,
the height will be adjusted accordingly. Finally, color is the hexcode that
the icon will get filled with.

In order to make this much easier, you can use the following HTML to see an
icon panel, I'd suggest placing it in the description of the field.

`<a href="/admin/config/content/shortcode_svg/svg_list" class="edit-button use-ajax" data-dialog-type="dialog" data-dialog-renderer="off_canvas" data-dialog-options="{&quot;width&quot;:400}">Icons shortcode panel</a>`

This will slide out a side panel with all of the icons shown. You can select
the colors, fill in the title, set the width, and select the proper icon. Once
all of the settings are set, hit the copy to clipboard button at the bottom and
paste in the shortcode into the proper field. Assuming you have shortcodes
enabled for your text format and everything is set correctly the icon should
work properly.

If you have 'Limit allowed HTML tags and correct faulty HTML' enabled in your
text format, you'll need to add the following to the allowed HTML for  the svg
to work properly.

`<svg viewbox data-name xlmns class width><use fill xlink href>`

## Credit

The Shortcode SVG module was originally developed by Miles France of [Protitude
](https://protitude.com).
