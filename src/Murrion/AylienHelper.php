<?php

namespace Murrion;


class AylienHelper
{

    /**
     * compile hash tags in to a string. Use small hash tags, avoid duplicates and use a small number of them
     *
     * @param $hash
     * @return string
     */
    public function parse_hashtags($hash)
    {
        $hashtags_array = array();
        $hashtags_string = '';

        if (isset($hash['language']) && $hash['language'] == 'en' && isset($hash['hashtags']) && !empty($hash['hashtags'])) {
            foreach ($hash['hashtags'] as $hashtag) {
                $hashtag = strtolower($hashtag);
                if (!in_array($hashtag, $hashtags_array) && strlen($hashtag) <= 10) {
                    $hashtags_array[] = $hashtag;
                }
            }
        }

        if (count($hashtags_array) > 3) {
            $hashtags_array = array_slice($hashtags_array, 0, 3);
        }

        if (empty($hashtags_array)) {
            $hashtags_string = '#php';
        }

        if (!empty($hashtags_array)) {
            $hashtags_string = implode(" ", $hashtags_array);
        }

        return $hashtags_string;
    }
}