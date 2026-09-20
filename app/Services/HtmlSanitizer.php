<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Str;

/**
 * Sanea HTML editable (artículos, páginas legales, áreas) con una lista blanca estricta.
 * Elimina scripts, estilos, iframes, atributos de eventos y URLs con esquemas peligrosos.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'hr', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'strong', 'b', 'em', 'i', 'u', 's', 'a',
        'blockquote', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'figure', 'figcaption', 'img',
        'span', 'code', 'pre', 'sup', 'sub', 'mark',
    ];

    private const DROP_WITH_CONTENT = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'svg', 'math', 'template', 'noscript',
        'link', 'meta', 'base', 'button', 'input', 'select', 'textarea', 'audio', 'video', 'canvas',
    ];

    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'th' => ['colspan', 'rowspan', 'scope'],
        'td' => ['colspan', 'rowspan'],
        'ol' => ['start'],
    ];

    public function clean(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $body = $this->parse($html);
        $this->sanitizeChildren($body);

        return $this->innerHtml($body);
    }

    /**
     * Devuelve el HTML saneado con ids en h2/h3 y el índice de contenido.
     *
     * @return array{html: string, toc: array<int, array{id: string, title: string, level: int}>}
     */
    public function withHeadingIds(string $html): array
    {
        $clean = $this->clean($html);
        if ($clean === '') {
            return ['html' => '', 'toc' => []];
        }

        $body = $this->parse($clean);
        $toc = [];
        $used = [];

        foreach ($this->collect($body, ['h2', 'h3']) as $heading) {
            $title = trim($heading->textContent);
            if ($title === '') {
                continue;
            }
            $id = Str::slug($title) ?: 'seccion';
            $base = $id;
            for ($i = 2; isset($used[$id]); $i++) {
                $id = $base.'-'.$i;
            }
            $used[$id] = true;
            $heading->setAttribute('id', $id);
            $toc[] = ['id' => $id, 'title' => $title, 'level' => (int) substr($heading->nodeName, 1)];
        }

        return ['html' => $this->innerHtml($body), 'toc' => $toc];
    }

    private function parse(string $html): DOMElement
    {
        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        /** @var DOMElement $body */
        $body = $document->getElementsByTagName('body')->item(0);

        return $body;
    }

    private function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (! $node instanceof DOMElement) {
                if ($node->nodeType === XML_COMMENT_NODE || $node->nodeType === XML_PI_NODE) {
                    $parent->removeChild($node);
                }

                continue;
            }

            $tag = strtolower($node->nodeName);

            if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
                $parent->removeChild($node);

                continue;
            }

            $this->sanitizeChildren($node);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);

                continue;
            }

            $this->sanitizeAttributes($node, $tag);
        }
    }

    private function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);
            if (! in_array($name, $allowed, true)) {
                $element->removeAttribute($attribute->nodeName);

                continue;
            }
            if (in_array($name, ['href', 'src'], true) && ! $this->isSafeUrl($attribute->nodeValue ?? '', $name === 'src')) {
                $element->removeAttribute($attribute->nodeName);
            }
        }

        if ($tag === 'a' && $element->hasAttribute('href')) {
            if (preg_match('#^https?://#i', $element->getAttribute('href')) === 1) {
                $element->setAttribute('rel', 'noopener noreferrer');
                $element->setAttribute('target', '_blank');
            } else {
                $element->removeAttribute('target');
            }
        }
        if ($tag === 'img') {
            $element->setAttribute('loading', 'lazy');
        }
    }

    private function isSafeUrl(string $url, bool $isImage): bool
    {
        $url = trim(preg_replace('/[\x00-\x20]+/', '', $url) ?? '');
        if ($url === '' || str_starts_with($url, '#') || str_starts_with($url, '/')) {
            return ! str_starts_with($url, '//');
        }
        $allowed = $isImage ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'];

        return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), $allowed, true);
    }

    /**
     * Elementos en orden de documento.
     *
     * @param  array<int, string>  $tags
     * @return array<int, DOMElement>
     */
    private function collect(DOMElement $root, array $tags): array
    {
        $xpath = new DOMXPath($root->ownerDocument);
        $query = implode('|', array_map(fn (string $tag): string => './/'.$tag, $tags));

        return iterator_to_array($xpath->query($query, $root), false);
    }

    private function innerHtml(DOMElement $element): string
    {
        $html = '';
        foreach ($element->childNodes as $child) {
            $html .= $element->ownerDocument->saveHTML($child);
        }

        return trim($html);
    }
}
