<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Palmtree\Html\Element;

$menu = new Element('ul#myMenu.some-class.some-other-class');

$menuItems = [];

$menuItems[] = [
    'label' => 'Home',
    'href' => 'https://example.org',
];

$menuItems[] = [
    'label' => 'About',
    'href' => 'https://example.org/about',
];

$menuItems[] = [
    'label' => 'Contact',
    'href' => 'https://example.org/contact',
];

foreach ($menuItems as $item) {
    $li = Element::create('li.item')->addChild(
        Element::create('a[href="' . $item['href'] . '"]')->setInnerText($item['label'])
    );

    $li->classes[] = 'item-' . strtolower($item['label']);

    $menu->addChild($li);
}

$menu->attributes->setData('item_total', (string)count($menuItems));

echo $menu->render();
