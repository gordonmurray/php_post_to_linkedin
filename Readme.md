PHP Post to Linkedin
====================

A very small PHP application to automatically post content to Linkedin, including titles, descriptions, hash tags and images.

Content will be submitted to one or more Linkedin Groups or to the users main profile.

The URL's of interesting content to share are found on Reddit. The content title, description and Hash tags are discovered using a couple of Aylien's API endpoints. A screen shot of the URL is generated using Browsershot.

## Installation

1. git clone git@github.com:murrion/php_post_to_linkedin.git

2. php composer install

3. Update the credentials_aylien.yml and credentials_linkedin.yml file with your own Aylien credentials and Linkedin Application credentials.

4. Update $public_url in bootstrap.php with the online public URL that you will use (this is so that Linkedin can read the Image URLs)

5. Set write permissions to the following folder : /logs/

Finally, Schedule the application to run as often as you like.

## Dependencies

1. You will need to sign up for Aylien API credentials here: https://www.mashape.com/aylien/text-analysis

2. You will need to create a Linkedin Application here: https://www.linkedin.com/secure/developer

#### View logs:

http://localhost/php_post_to_linkedin/index.php/logs

