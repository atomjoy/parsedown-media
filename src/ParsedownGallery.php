<?php

namespace Atomjoy\Parsedown;

use Parsedown;

/**
 * ParsedownGallery
 *
 * Create an image gallery from a Markdown file.
 *
 * $p = new \Atomjoy\Parsedown\ParsedownGallery();
 *
 * echo $p->text("%%%box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n%%%");
 */
class ParsedownGallery extends Parsedown
{
    protected $imageGalleryCounter = 0;
    protected $imageGalleryExtensions = array('webp', 'gif', 'png', 'jpg', 'jpeg');

    /**
     * The array index is the first character of the pattern to match.
     * It is not possible to define patterns longer than one character directly here, as only the first
     * character of the line will be checked. (eg. Setting $this->BlockTypes['{tag'] won't work.)
     *
     * The second array is a list of all functions that should be called when the character is found.
     *
     * Leave out the 'block' or 'inline' part of the function name, as it will be prepended automatically.
     */
    function __construct()
    {
        $this->BlockTypes['%'][] = 'ImageGallery';
    }

    protected function blockImageGallery($Line, $Block)
    {
        $this->imageGalleryCounter = 0;

        $marker = $Line['text'][0];

        $openerLength = strspn($Line['text'], $marker);

        if ($openerLength < 3) {
            return;
        }

        $infostring = trim(substr($Line['text'], $openerLength), "\t ");

        if (strpos($infostring, '%') !== false) {
            return;
        }

        $Element = array(
            'name' => 'ul',
            'text' => '',
        );

        if ($infostring !== '') {
            $class_name = substr($infostring, 0, strcspn($infostring, " \t\n\f\r"));

            $class_name = preg_replace("/[^a-zA-Z0-9]/", "", $class_name);

            $Element['attributes'] = array('class' => "gallery-$class_name");

            $Element['elements'] = array();
        } else {
            $Element['attributes'] = array('class' => "gallery-content");
        }

        $Block = array(
            'char' => $marker,
            'openerLength' => $openerLength,
            'element' => array(
                'name' => 'div',
                'attributes' => ['class' => 'gallery-wrapper'],
                'element' => $Element,
            ),
        );

        return $Block;
    }

    /**
     * Appending the word `continue` to the function name will cause this function to be
     * called to process any following lines, until $block['complete'] is set to be 'true'.
     */
    protected function blockImageGalleryContinue($Line, $Block)
    {
        if (isset($Block['complete'])) {
            return;
        }

        if (isset($Block['interrupted'])) {
            $Block['element']['element']['text'] .= str_repeat("\n", $Block['interrupted']);

            unset($Block['interrupted']);
        }

        if (($len = strspn($Line['text'], $Block['char'])) >= $Block['openerLength']
            and chop(substr($Line['text'], $len), ' ') === ''
        ) {
            $Block['element']['element']['text'] = substr($Block['element']['element']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $url = $Line['body'];

        $ext = pathinfo($url, PATHINFO_EXTENSION);

        if (in_array($ext, $this->imageGalleryExtensions)) {

            $this->imageGalleryCounter++;

            $Element = array(
                'name' => 'li',
                'element' => array(
                    'name' => 'img',
                    'attributes' => array(
                        'src' => $url,
                        'data-image' => $this->imageGalleryCounter,
                        'class' => 'gallery-image gallery-image-' . $this->imageGalleryCounter,
                        'alt' => 'Image',
                    ),
                ),
            );

            $Block['element']['element']['elements'][] = $Element;
        }

        $Block['element']['element']['text'] .= "\n" . $url;

        return $Block;
    }

    /**
     * Appending the word `complete` to the function name will cause this function to be
     * called when the block is marked as *complete* (see the previous method).
     */
    protected function blockImageGalleryComplete($block)
    {
        return $block;
    }
}
