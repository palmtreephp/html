<?php

declare(strict_types=1);

namespace Palmtree\Html;

final class Escaper
{
    private const string TAG_NAME_PATTERN = '/^[a-zA-Z][a-zA-Z0-9-]*$/';
    /** @see https://html.spec.whatwg.org/multipage/syntax.html#attributes-2 */
    private const string ATTRIBUTE_NAME_PATTERN = '/^[^\s"\'>\/=\x00-\x1F\x7F]+$/u';

    /**
     * Escapes a string for use as text content or a quoted attribute value.
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, \ENT_QUOTES | \ENT_SUBSTITUTE | \ENT_HTML5, 'UTF-8');
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function assertValidTagName(string $tag): void
    {
        if (!preg_match(self::TAG_NAME_PATTERN, $tag)) {
            throw new \InvalidArgumentException(\sprintf('Invalid tag name "%s"', self::escape($tag)));
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function assertValidAttributeName(string $name): void
    {
        if (!preg_match(self::ATTRIBUTE_NAME_PATTERN, $name)) {
            throw new \InvalidArgumentException(\sprintf('Invalid attribute name "%s"', self::escape($name)));
        }
    }
}
