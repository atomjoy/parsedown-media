<?php

namespace Atomjoy\Parsedown;

use Parsedown;

/**
 * ParsedownGallery
 *
 * Create media from a Markdown file.
 *
 * $p = new \Atomjoy\Parsedown\ParsedownEmbed();
 * echo $p->text("{Title here}(embed)(https://www.youtube.com/embed/y5g3UMaODxo?si=evrMVC4n9DbZ3OQD)");
 * echo $p->text("{Title here}(video)(https://afe019d0-9895-400c-9385-5c25c4775c8e.mdnplay.dev/shared-assets/videos/flower.webm)");
 * echo $p->text("{Title here}(audio)(https://361fc8d0-210a-4e2b-9cff-6f13be3457ad.mdnplay.dev/shared-assets/audio/t-rex-roar.mp3)");
 */
class ParsedownEmbed extends Parsedown
{
    function __construct()
    {
        $this->BlockTypes["{"] = array('Media');
        // $this->InlineTypes["{"] = array('Media');
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
            $html .= '<source src="' . $link . '">';
            if ($type === 'video') {
                $html .= '</video>';
            } else if ($type === 'audio') {
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
}
