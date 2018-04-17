# A media / MIME type lookup tool

This PHP 7.1 library provides tools to lookup Media Types (formerly known as MIME types). The library uses an internal [JSON list](assets/media-types.json) of media types generated from [Apache's httpd MIME type list](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types).

## Usage

_To be written_

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/psmedia-types) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/media-types
```

## Updating the internal media types list

You can regenerate the internal media types list using the provided [`generate-media-types-list.php`](scripts/generate-media-types-list.php) script. The script is fetches the list from Apache's Subversion server (`https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types`), parses it and generates the local `assets/media-types.json` file. 

```bash
php scripts/generate-media-types-list.php
```


## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).