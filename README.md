# :palm_tree: Palmtree Html

[![License](http://img.shields.io/packagist/l/palmtree/form.svg)](LICENSE)
[![Build](https://img.shields.io/github/workflow/status/palmtreephp/html/Build.svg)](https://github.com/palmtreephp/html/actions/workflows/build.yml)

Build and render HTML elements using an OOP style interface and jQuery style selectors.

## Requirements
* PHP >= 7.1

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


$menu->attributes->setData('item_total', (string)count($menuItems));

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
