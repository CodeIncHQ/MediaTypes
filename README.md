# A media / MIME type lookup tool

This PHP 7.1 library provides tools to lookup [Media Types](https://en.wikipedia.org/wiki/Media_type) (formerly known as MIME types). The library uses an [internal list](assets/media-types.json) of media types generated from [Apache's httpd MIME type list](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types). Apache's list itself is generated using the [IANA official media types repository](https://www.iana.org/assignments/media-types/media-types.xml).

The media types list is loaded once in memory. You can instantiate the class or call `MediaTypes::getMediaTypes()` has many time as you want, no time will be lost reparsing the JSON list.


## Usage

```php
<?php
use CodeInc\MediaTypes\MediaTypes;

// looking up the media type of a file, a path or an URL 
// (or anything finishing by an extension)
MediaTypes::getFilenameMediaType('/path/to/a/picture.jpg'); // -> 'image/jpeg'

// looking up the media type for an extension
MediaTypes::getExtensionMediaType('jpg'); // -> 'image/jpeg'

// listing all known extensions for a media type
MediaTypes::getMediaTypeExtensions('image/jpeg'); // -> ['jpeg', 'jpg', 'jpe']

// listing all media types
var_dump(MediaTypes::getMediaTypes()); // assoc array

// searching for media types using a shell pattern 
var_dump(MediaTypes::searchMediaTypes('image/*'); // -> assoc array
```

The class implements [`IteratorAggregate`](http://php.net/manual/en/iteratoraggregate.getiterator.php) an is [iterable](http://php.net/manual/en/language.types.iterable.php):
```php
<?php
use CodeInc\MediaTypes\MediaTypes;

// listing all types
foreach (new MediaTypes() as $extension => $mediaType) {
	var_dump($extension, $mediaType);
}
```

The class implements [`ArrayAccess`](http://php.net/manual/fr/class.arrayaccess.php) and can be used as an array :

```php
<?php
use CodeInc\MediaTypes\MediaTypes;
$mediaTypes = new MediaTypes();

// you can test the existence or either an extension or a media type
var_dump(isset($mediaTypes['jpg'])); // -> true
var_dump(isset($mediaTypes['image/jpeg'])); // -> true
var_dump(isset($mediaTypes['a-fake/media-type'])); // -> false

// you can access an extension's media type
var_dump($mediaTypes['jpg']); // -> 'image/jpeg'

// and a media type's extensions
var_dump($mediaTypes['image/jpeg']); // -> ['jpeg', 'jpg', 'jpe']

// if the type does not exist, the value is null
var_dump($mediaTypes['a-fake/media-type']; // -> null
```


## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/media-types) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/media-types
```


## Updating the internal media types list

You can regenerate the internal media types list using the provided [`scripts/generate-media-types-list.php`](scripts/generate-media-types-list.php) script. The script is fetches the list from Apache's Subversion server ([here](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types)), parses it and generates the local [`assets/media-types.json`](assets/media-types.json) file. 

```bash
php scripts/generate-media-types-list.php
```


## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).

