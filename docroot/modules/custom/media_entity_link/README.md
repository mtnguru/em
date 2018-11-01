[![Build Status](https://travis-ci.org/drupal-media/media_entity_link.svg?branch=8.x-1.x)](https://travis-ci.org/drupal-media/media_entity_link)

## About Media entity

Media entity provides a 'base' entity for a media element. This is a very basic
entity which can reference to all kinds of media-objects (local files, YouTube
videos, links, CDN-files, ...). This entity only provides a relation between
Drupal (because it is an entity) and the resource. You can reference to this
entity within any other Drupal entity.

## About Media entity Link

This module provides Link integration for Media entity (i.e. media type
provider plugin).

### Without Link API
If you need just to embedded links you can use this module without using
Link's API. That will give you access to the fields available from the
url/embed code: id and user.

You will need to:

- Create a Media bundle with the type provider "Link".
- On that bundle create a field for the Link url/source (this should be a plain
  text or link field).
- Return to the bundle configuration and set "Field with source information" to
  use that field.

**IMPORTANT:** beware that there is limit on the number of request that can be
made for free. [Read more](https://dev.link.com/rest/public)


### With Link API
If you need to get other fields, you will need to use Link's API. To get this
working follow the steps below:

- Download and enable 
  [composer_manager](https://www.drupal.org/project/composer_manager). Also make
  sure you have [drush](https://github.com/drush-ops/drush) installed.
- Run the following commands from within your Drupal root directory to download
  the [library](https://github.com/J7mbo/link-api-php) that will handle the
  communication:

```
  // Rebuild the composer.json file with updated dependencies.
  $ drush composer-json-rebuild

  // Install the required packages.
  $ drush composer-manager install
```
- Create a link app on the link
  [developer site](https://dev.link.com/apps/)
- Enable read access for your link app
- Grab your access tokens from the link developer site
- In your Link bundle configuration set "Whether to use Link api to fetch
  links or not" to "Yes"" and paste in the "Consumer key", "Consumer secret",
  "Oauth access token" and the "Oauth access token secret"

### Storing field values
If you want to store the fields that are retrieved from Link you should create
appropriate fields on the created media bundle (image, content and
relink_count) and map this to the fields provided by Link.php.

**NOTE:** At the moment there is no GUI for that, so the only method of doing
that for now is via CMI.

This would be an example of that (the field_map section):

```
langcode: en
status: true
dependencies:
  module:
    - media_entity_link
id: link
label: Link
description: 'Link to be used with content.'
type: link
type_configuration:
  link:
    source_field: field_link_source
    use_link_api: '1'
    consumer_key: YOUR_CONSUMER_KEY
    consumer_secret: YOUR_CONSUMER_SECRET
    oauth_access_token: YOUR_OAUTH_ACCESS_TOKEN
    oauth_access_token_secret: YOUR_OAUTH_ACCESS_TOKEN_SECRET
field_map:
  source: field_link_source
  id: field_link_id
  content: field_link_content
```

Project page: http://drupal.org/project/media_entity_link

Maintainers:
 - Janez Urevc (@slashrsm) drupal.org/user/744628
 - Primož Hmeljak (@primsi) drupal.org/user/282629

IRC channel: #drupal-media
