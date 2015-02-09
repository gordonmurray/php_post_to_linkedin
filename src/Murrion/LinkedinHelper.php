<?php
namespace Murrion;


class LinkedinHelper
{
    /**
     * Populate the XML needed to Share to a Profile on Linkedin
     *
     * @param $hashtags_string
     * @param $article_title
     * @param $article_content
     * @param $url
     * @return string
     */
    public function linkedin_share_xml($hash_tags_string, $article_title, $article_content, $url, $article_image)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<share>';
        $xml .= '<comment>' . $hash_tags_string . '</comment>';
        $xml .= '<content>';
        $xml .= '<title>' . $article_title . '</title>';
        $xml .= '<description>' . $article_content . '</description>';
        $xml .= '<submitted-url>' . $url . '</submitted-url>';
        $xml .= '<submitted-image-url>' . $article_image . '</submitted-image-url>';
        $xml .= '</content>';
        $xml .= '<visibility>';
        $xml .= '<code>anyone</code>';
        $xml .= '</visibility>';
        $xml .= '</share>';

        return $xml;
    }

    /**
     * Populate the XML needed to send to a Linkedin Group
     *
     * @param $hash_tags_string
     * @param $article_title
     * @param $article_content
     * @param $url
     * @return string
     */
    public function linkedin_group_xml($hash_tags_string, $article_title, $article_content, $url, $article_image)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<post>';
        $xml .= '<title>' . $article_title . '</title>';
        $xml .= '<summary>' . $hash_tags_string . '</summary>';
        $xml .= '<content>';
        $xml .= '<submitted-url>' . $url . '</submitted-url>';
        $xml .= '<submitted-image-url>' . $article_image . '</submitted-image-url>';
        $xml .= '<title>' . $article_title . '</title>';
        $xml .= '<description>' . $article_content . '</description>';
        $xml .= '</content>';
        $xml .= '</post>';

        return $xml;
    }

    /**
     * Post articles to one or more Linkedin Groups depending on keywords found in the title
     *
     * @param $linkedIn
     * @param $title_array
     * @param $url_parameters
     * @param $linkedin_group_xml
     * @return array
     */
    public function post_to_linkedin_groups($linkedIn, $title_array, $url_parameters, $linkedin_group_xml)
    {
        $responses = array();

        // post to the PHP group
        if (in_array('php', $title_array)) {
            try {
                $responses[] = $linkedIn->api('v1/groups/24405/posts', $url_parameters, 'POST', $linkedin_group_xml);
                $posted_to_group = true;
            } catch (Exception $e) {
                $responses[] = $e->getMessage();
            }
        }

        // post to the CodeIgniter group
        if (in_array('codeigniter', $title_array)) {
            try {
                $responses[] = $linkedIn->api('v1/groups/1438197/posts', $url_parameters, 'POST', $linkedin_group_xml);
                $posted_to_group = true;
            } catch (Exception $e) {
                $responses[] = $e->getMessage();
            }
        }

        // post to the Laravel group
        if (in_array('laravel', $title_array)) {
            try {
                $responses[] = $linkedIn->api('v1/groups/6553973/posts', $url_parameters, 'POST', $linkedin_group_xml);
                $posted_to_group = true;
            } catch (Exception $e) {
                $responses[] = $e->getMessage();
            }
        }

        return $responses;
    }

    /**
     * Share to the users main profile on Linkedin
     *
     * @param $linkedIn
     * @param $url_parameters
     * @param $linkedin_share_xml
     * @return array
     */
    public function post_to_linkedin_profile($linkedIn, $url_parameters, $linkedin_share_xml)
    {
        $responses = array();

        try {
            $responses[] = $linkedIn->api('v1/people/~/shares', $url_parameters, 'POST', $linkedin_share_xml);
        } catch (Exception $e) {
            $responses[] = $e->getMessage();
        }

        return $responses;
    }
}