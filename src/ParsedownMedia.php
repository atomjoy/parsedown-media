<?php

namespace Atomjoy\Parsedown;

use Parsedown;

/**
 * ParsedownMedia
 *
 * Create media and gallery from a Markdown file.
 *
 * $p = new \Atomjoy\Parsedown\ParsedownMedia();
 *
 * Audio, video
 * echo $p->text("{Title here}(embed)(https://www.youtube.com/embed/y5g3UMaODxo?si=evrMVC4n9DbZ3OQD)");
 * echo $p->text("{Title here}(video)(https://afe019d0-9895-400c-9385-5c25c4775c8e.mdnplay.dev/shared-assets/videos/flower.webm)");
 * echo $p->text("{Title here}(audio)(https://361fc8d0-210a-4e2b-9cff-6f13be3457ad.mdnplay.dev/shared-assets/audio/t-rex-roar.mp3)");
 *
 * Gallery
 * echo $p->text("%%%box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n%%%");
 * echo $p->text("&&&box\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\nhttps://img.icons8.com/bubbles/100/google-logo.jpg\n&&&");
 */
class ParsedownMedia extends Parsedown
{
    protected $imageGalleryCounter = 0;
    protected $imageGalleryExtensions = ['webp', 'gif', 'png', 'jpg', 'jpeg'];

    protected $imageGallerySplideCounter = 0;
    protected $imageGallerySplideExtensions = ['webp', 'gif', 'png', 'jpg', 'jpeg'];

    function __construct()
    {
        $this->BlockTypes['%'][] = 'ImageGallery';
        $this->BlockTypes['&'][] = 'ImageGallerySplide';
        $this->BlockTypes["{"][] = 'Media';
        // $this->InlineTypes["{"][] = 'Media';
    }

    /**
     * Create media tags
     *
     * {Video Description}(video)(https://example.com/video.mp4)
     * {Audio Description}(audio)(https://example.com/audio.mp3)
     * {Embed Description}(embed)(https://example.com/embed/url)
     */
    protected function blockMedia($Line, $Block = null)
    {
        if (preg_match('/^(\s*)\{(.*?)\}\((.+?)\)\((\S+?)\)$/', $Line['body'], $matches)) {

            $description = htmlspecialchars($matches[2], ENT_QUOTES);
            $type = strtolower($matches[3]);
            $link = htmlspecialchars($matches[4], ENT_QUOTES);

            $html = '<div class="media-wrapper">';
            if ($type === 'video') {
                $html .= '<video controls width="100%" style="aspect-ratio: 16/9;">';
            } else if ($type === 'audio') {
                $html .= '<audio controls width="100%" style="aspect-ratio: 16/9;">';
            } else {
                $html .= '<iframe width="100%" src="' . $link . '" style="aspect-ratio: 16/9;" frameborder="0" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
            }
            if ($type === 'video') {
                $html .= '<source src="' . $link . '">';
                $html .= '</video>';
            } else if ($type === 'audio') {
                $html .= '<source src="' . $link . '">';
                $html .= '</audio>';
            }
            if (!empty($description)) {
                $html .= '<p class="media-description">' . $description . '</p>';
            }
            $html .= '</div>';

            $Block = array(
                'markup' => $html,
                'complete' => true,
            );

            return $Block;
        }
    }

    // Image Gallery

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

    // Splide image gallery

    protected function blockImageGallerySplide($Line, $Block)
    {
        $this->imageGallerySplideCounter = 0;

        $marker = $Line['text'][0];

        $openerLength = strspn($Line['text'], $marker);

        if ($openerLength < 3) {
            return;
        }

        $infostring = trim(substr($Line['text'], $openerLength), "\t ");

        if (strpos($infostring, '&') !== false) {
            return;
        }

        $Element = array(
            'name' => 'ul',
            'attributes' => ['class' => 'splide__list'],
            'text' => ''
        );

        if ($infostring !== '') {
            $class_name = substr($infostring, 0, strcspn($infostring, " \t\n\f\r"));

            $class_name = preg_replace("/[^a-zA-Z0-9]/", "", $class_name);

            $Element['attributes'] = array('class' => "splide__list splide__$class_name");

            $Element['elements'] = array();
        } else {
            $Element['attributes'] = array('class' => "splide__list");
        }

        $Block = array(
            'char' => $marker,
            'openerLength' => $openerLength,
            'element' => array(
                'name' => 'div',
                'attributes' => ['class' => 'splide'],
                'element' => array(
                    'name' => 'div',
                    'attributes' => ['class' => 'splide__track'],
                    'element' => $Element,
                    'text' => '',
                ),
            ),

        );

        return $Block;
    }

    /**
     * Appending the word `continue` to the function name will cause this function to be
     * called to process any following lines, until $block['complete'] is set to be 'true'.
     */
    protected function blockImageGallerySplideContinue($Line, $Block)
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
            $Block['element']['element']['text'] = substr($Block['element']['element']['element']['text'], 1);

            $Block['complete'] = true;

            return $Block;
        }

        $url = $Line['body'];

        $ext = pathinfo($url, PATHINFO_EXTENSION);

        if (in_array($ext, $this->imageGallerySplideExtensions)) {

            $this->imageGallerySplideCounter++;

            $Element = array(
                'name' => 'li',
                'attributes' => ['class' => 'splide__slide'],
                'element' => array(
                    'name' => 'img',
                    'attributes' => array(
                        'src' => $url,
                        'data-image' => $this->imageGallerySplideCounter,
                        'class' => 'splide__image splide__image__' . $this->imageGallerySplideCounter,
                        'alt' => 'Image',
                    ),
                ),
            );

            $Block['element']['element']['element']['elements'][] = $Element;
        }

        $Block['element']['element']['element']['text'] .= "\n" . $url;

        return $Block;
    }

    /**
     * Appending the word `complete` to the function name will cause this function to be
     * called when the block is marked as *complete* (see the previous method).
     */
    protected function blockImageGallerySplideComplete($block)
    {
        return $block;
    }

    /**
     * blockListComplete
     *
     * @param array $Block
     * @return mixed
     */
    protected function blockListComplete(array $Block)
    {
        $list = $this->parseToDoList($Block);

        return $list;
    }

    /**
     * parseToDoList
     *
     * Markdown todo list parser with fontawesome icons.
     *
     * @param array $Block
     * @return mixed
     */
    protected function parseToDoList(array $Block)
    {
        $list = parent::blockListComplete($Block);

        if (!isset($list)) {
            return null;
        }

        foreach ($list['element'] as $key => $listItem) {
            if (is_array($listItem)) {
                foreach ($listItem as $inList => $items) {
                    $item = $items['handler']['argument'];
                    if (isset($item) && is_array($item)) {
                        $checkmark = strtolower(substr($item[0], 0, 3));
                        $text = trim(substr($item[0], 3));
                        if ($checkmark === '[x]' || $checkmark === '[ ]') {
                            $iconClass = $checkmark === '[x]' ? 'fas fa-check-square todo-icon-checked' : 'far fa-square';
                            $list['element']['attributes']['class'] = 'todo-list';
                            $list['element']['elements'][$inList] = [
                                'name' => 'li',
                                'attributes' => ['class' => 'list-none todo-item'],
                                'elements' => [
                                    [
                                        'name' => 'i',
                                        'attributes' => ['class' => 'todo-icon ' . $iconClass],
                                        'text' => '',
                                    ],
                                    [
                                        'name' => 'span',
                                        'attributes' => ['class' => 'todo-text'],
                                        'text' => $text,
                                    ]
                                ]
                            ];
                        }
                    }
                }
            }
        }

        return $list;
    }
}
