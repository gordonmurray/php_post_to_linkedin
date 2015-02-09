<?php
namespace Murrion;


class BrowsershotHelper
{

    /**
     * Generate a screen shot with Browsershot
     *
     * @param $browsershot
     * @param $reddit_identifier
     * @param $url
     * @return string
     */
    public function generate_image($browsershot, $reddit_identifier, $url, $public_url)
    {
        $browsershot
            ->setURL($url)
            ->setWidth('1024')
            ->setHeight('768')
            ->save('logs/' . $reddit_identifier . '.png');

        $image_size = filesize('logs/' . $reddit_identifier . '.png');

        // images around 4888 bytes seem to be blank

        $article_image = ($image_size > 5000) ? $public_url . '/logs/' . $reddit_identifier . '.png' : $public_url . '/logs/php.png';

        return $article_image;
    }
}