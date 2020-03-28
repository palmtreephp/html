# :palm_tree: Palmtree Html

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
    $li = new Element('li.item');
    $li->addClass('item-' . \strtolower($item['label']));

    $a = new Element('a');
    $a->addAttribute('href', $item['href'])->setInnerText($item['label']);
    
    $li->addChild($a);
    $menu->addChild($li);
}

$menu->addDataAttribute('item_total', \count($menuItems));

echo $menu->render();

?>
```

Renders the following HTML:

```html
<ul data-item_total="3" class="some-class">
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
