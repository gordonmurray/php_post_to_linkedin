<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$yaml = new Symfony\Component\Yaml\Parser;

$linkedin_credentials = $yaml->parse(file_get_contents('./credentials_linkedin.yml'));

$aylien_credentials = $yaml->parse(file_get_contents('./credentials_aylien.yml'));

$reddit = new \Murrion\Reddit();

$linkedIn = new Happyr\LinkedIn\LinkedIn($linkedin_credentials['appId'], $linkedin_credentials['appSecret']);

$aylien = new AYLIEN\TextAPI($aylien_credentials['application_id'], $aylien_credentials['application_key']);

$browsershot = new Spatie\Browsershot\Browsershot();

$aylien_helper = new \Murrion\AylienHelper();

$linkedin_helper = new \Murrion\LinkedinHelper();

$browsershot_helper = new \Murrion\BrowsershotHelper();

$public_url = 'http://xxxxxxxxxxxxxxxxxx/php_post_to_linkedin/'; // useful when called from the command line

