<?php

require __DIR__ . '/../vendor/autoload.php';

use Palmtree\Html\Element;

$menu = new Element('ul#myMenu.some-class.some-other-class');

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
    $a  = Element::create('a')->addAttribute('href', $item['href'])->setInnerText($item['label']);
    $li = Element::create('li.item')->addClass('item-' . strtolower($item['label']))->addChild($a);

    $menu->addChild($li);
}

$menu->addDataAttribute('item_total', count($menuItems));

echo $menu->render();
