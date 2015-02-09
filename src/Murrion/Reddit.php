<?php

namespace Murrion;


class Reddit
{
    private $log_file = './logs/logged_links.txt';

    private $score = 2;

    /**
     * Retrieve an array of PHP posts from reddit
     *
     * @return mixed
     */
    public function retrieve_new_links()
    {

        $reddit_json = file_get_contents('http://www.reddit.com/r/php/new/.json');

        $reddit_array = json_decode($reddit_json, true);

        $threads = $reddit_array['data']['children'];

        foreach ($threads as $thread) {

            if ($thread['data']['domain'] != 'self.PHP' && $thread['data']['over_18'] == false && $thread['data']['banned_by'] == '' && $thread['data']['score'] > $this->score) {

                $links[$thread['data']['id']] = array(
                    'identifier' => $thread['data']['id'],
                    'title' => $thread['data']['title'],
                    'author' => $thread['data']['author'],
                    'score' => $thread['data']['score'],
                    'comments' => $thread['data']['num_comments'],
                    'created' => date("d/m/Y g:ia", $thread['data']['created_utc']),
                    'url' => $thread['data']['url']
                );
            }

        }

        return $links;
    }


    /**
     * Compare old and new links and return only the unseen ones
     *
     * @param $new_links
     * @param $logged_links
     * @return array
     */
    public function determine_unseen_links($new_links, $logged_links)
    {
        if (is_array($logged_links) && empty($logged_links)) {
            return $new_links;
        }

        if (is_array($new_links) && is_array($logged_links) && !empty($new_links) && !empty($logged_links)) {
            return array_diff_assoc($new_links, $logged_links);
        }
    }

    /**
     * update the list of links already seen
     *
     * @param $unseen_links
     * @param $logged_links
     */
    public function update_logged_links($unseen_links, $logged_links)
    {
        $posts_array = array();

        if (empty($logged_links)) {
            $posts_array = $unseen_links;
        }

        if (!empty($unseen_links) && !empty($logged_links)) {
            $posts_array = array_merge($unseen_links, $logged_links);
        }

        if (!empty($posts_array)) {
            $fp = fopen($this->log_file, 'w');
            fwrite($fp, serialize($posts_array));
            fclose($fp);
        }
    }


    /**
     * Return an array of any previously recorded job links
     * Used to avoid emailing the same thing twice.
     *
     * @return array
     */
    public function retrieve_logged_links()
    {
        $links_past = array();

        if (file_exists($this->log_file)) {
            $handle = fopen($this->log_file, 'r');
            if (filesize($this->log_file) > 0) {
                $links_past = unserialize(fread($handle, filesize($this->log_file)));
            }
        }

        return $links_past;
    }
}