# :palm_tree: Palmtree Html

Build and render HTML elements using an OOP style interface and jQuery style selectors.

## Requirements
* PHP >= 5.6

## Installation

Use composer to add the package to your dependencies:
```bash
composer require palmtree/html
```

## Usage Example
```php
<?php
use Palmtree\Html\Element;

$ul = new Element('ul.some-class');

$items = ['Hello', 'World', 'Some', 'Data'];

foreach($items as $item) {
    $li = new Element('li.item');
    $li->addClass('item-' . strtolower($item))->setInnerText($item);
    
    $ul->addChild($li);
}

$ul->addDataAttribute('item_total', count($items));

echo $ul->render();

?>
```

Renders the following HTML:

```html
<ul data-item_total="4" class="some-class">
	<li class="item item-hello">Hello</li>
	<li class="item item-world">World</li>
	<li class="item item-some">Some</li>
	<li class="item item-data">Data</li>
</ul>
```

## License

Released under the [MIT license](LICENSE)
