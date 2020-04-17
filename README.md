# :palm_tree: Palmtree Html

[![License](http://img.shields.io/packagist/l/palmtree/html.svg)](LICENSE)
[![Build Status](https://scrutinizer-ci.com/g/palmtreephp/html/badges/build.png?b=master)](https://scrutinizer-ci.com/g/palmtreephp/html/build-status/master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/palmtreephp/html.svg)](https://scrutinizer-ci.com/g/palmtreephp/html/)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/palmtreephp/html.svg)](https://scrutinizer-ci.com/g/palmtreephp/html/)

Build and render HTML elements using an OOP style interface and jQuery style selectors.

## Requirements
* PHP >= 7.1

For PHP 5.6 support use [v1.0](https://github.com/palmtreephp/html/tree/1.0)

## Installation

Use composer to add the package to your dependencies:
```bash
composer require palmtree/html
```

## Usage Example
```php
<?php
use Palmtree\Html\Element;

$menu = new Element('ul.some-class');

$menuItems = [];

$menuItems[] = [
    'label' => 'Home',
    'href'  => 'https://example.org',
];

$menuItems[] = [
    'label' => 'About',
    'href'  => 'https://example.org/about',
];

$menuItems[] = [
    'label' => 'Contact',
    'href'  => 'https://example.org/contact',
];

foreach ($menuItems as $item) {
    $a = Element::create('a[href="' . $item['href'] . '"]')->setInnerText($item['label']);

    $li = Element::create('li.item')->addChild($a);
    $li->classes[] = 'item-' . strtolower($item['label']);

    $menu->addChild($li);
}


$menu->attributes->setData('item_total', count($menuItems));

echo $menu->render();

?>
```

Renders the following HTML:

```html
<ul class="some-class" data-item_total="3">
    <li class="item item-home">
        <a href="https://example.org">Home</a>
    </li>
    <li class="item item-about">
        <a href="https://example.org/about">About</a>
    </li>
    <li class="item item-contact">
        <a href="https://example.org/contact">Contact</a>
    </li>
</ul>
```

## License

Released under the [MIT license](LICENSE)
