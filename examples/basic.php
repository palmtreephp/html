<?php

require __DIR__ . '/../vendor/autoload.php';

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
