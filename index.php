<?php
require __DIR__ . '/bootstrap.php';

/*
 * Search Reddit and submit any good PHP related links to Linkedin
 */
$app->get('/', function () use ($app, $reddit, $linkedIn, $browsershot, $aylien, $linkedin_helper, $aylien_helper, $browsershot_helper, $linkedin_credentials, $public_url) {

    $responses = array();

    $new_links = $reddit->retrieve_new_links();

    $logged_links = $reddit->retrieve_logged_links();

    $unseen_links = $reddit->determine_unseen_links($new_links, $logged_links);

    $url_parameters = array(
        'format' => 'xml',
        'oauth2_access_token' => $linkedin_credentials['oauth2_access_token']
    );

    // process each new link to share
    foreach ($unseen_links as $post_data) {

        $reddit_identifier = $post_data['identifier'];

        $title_array = explode(" ", strtolower($post_data['title']));

        $url = $post_data['url'];

        $article_image = $browsershot_helper->generate_image($browsershot, $reddit_identifier, $url, $public_url);

        // Use Aylien to get some extra information from the URL
        $content = (array)$aylien->Extract(array('url' => $url));
        $hash = (array)$aylien->Hashtags(array('url' => $url));

        $article_title = $content['title'];
        $article_content = substr($content['article'], 0, 300) . '..';
        $hash_tags_string = $aylien_helper->parse_hashtags($hash);

        if ($article_content != '') {

            // prepare xml
            $linkedin_share_xml = $linkedin_helper->linkedin_share_xml($hash_tags_string, $article_title, $article_content, $url, $article_image);

            $linkedin_group_xml = $linkedin_helper->linkedin_group_xml($hash_tags_string, $article_title, $article_content, $url, $article_image);

            // submit to Linkedin groups if certain keywords are present
            $responses = $linkedin_helper->post_to_linkedin_groups($linkedIn, $title_array, $url_parameters, $linkedin_group_xml);

            // only submit to the main profile page if nothing was shared in to a group
            if (empty($responses)) {
                $responses[] = $linkedin_helper->post_to_linkedin_profile($linkedIn, $url_parameters, $linkedin_share_xml);
            }
        }
    }

    echo count($unseen_links) . " new links to post\n";

    print_r($responses);

    $reddit->update_logged_links($unseen_links, $logged_links);

    return '';

});

/*
 * Show previously sent links
 */
$app->get('/logs', function () use ($app, $reddit) {

    $logged_links = $reddit->retrieve_logged_links();

    print_r($logged_links);

    return '';

});

$app->run();