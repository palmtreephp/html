<?php

require __DIR__ . '/../vendor/autoload.php';

use Palmtree\Html\Element;

$ul = new Element('ul.some-class');

$items = ['Hello', 'World', 'Some', 'Data'];

foreach ($items as $item) {
    $li = new Element('li.item');
    $li
        ->addClass('item-' . strtolower($item))
        ->setInnerText($item);

    $ul->addChild($li);
}

$ul->addDataAttribute('item_total', count($items));

echo $ul->render();
