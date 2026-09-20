<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    private HtmlSanitizer $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sanitizer = new HtmlSanitizer;
    }

    public function test_it_keeps_safe_formatting(): void
    {
        $html = '<h2>Título</h2><p>Texto con <strong>negritas</strong> y <em>cursiva</em>.</p><ul><li>Uno</li></ul>';

        $this->assertSame($html, $this->sanitizer->clean($html));
    }

    public function test_it_removes_scripts_styles_and_event_handlers(): void
    {
        $clean = $this->sanitizer->clean('<p onclick="alert(1)" style="color:red">Hola</p><script>alert(1)</script><style>p{}</style><iframe src="https://evil.example"></iframe>');

        $this->assertSame('<p>Hola</p>', $clean);
    }

    public function test_it_blocks_dangerous_url_schemes(): void
    {
        $clean = $this->sanitizer->clean('<a href="javascript:alert(1)">a</a><a href=" JaVaScRiPt:alert(1)">b</a><a href="data:text/html;base64,AAAA">c</a><img src="javascript:alert(1)" alt="x">');

        $this->assertStringNotContainsString('javascript', strtolower($clean));
        $this->assertStringNotContainsString('data:', $clean);
    }

    public function test_external_links_open_safely(): void
    {
        $clean = $this->sanitizer->clean('<a href="https://example.com">sitio</a><a href="/areas">interno</a>');

        $this->assertStringContainsString('href="https://example.com" rel="noopener noreferrer" target="_blank"', $clean);
        $this->assertStringContainsString('<a href="/areas">interno</a>', $clean);
    }

    public function test_it_unwraps_unknown_tags_keeping_their_text(): void
    {
        $this->assertSame('<p>Texto <strong>importante</strong></p>', $this->sanitizer->clean('<div><p>Texto <font color="red"><strong>importante</strong></font></p></div>'));
    }

    public function test_it_preserves_spanish_characters(): void
    {
        $this->assertSame('<p>Cláusula de rescisión — ¿qué pasa?</p>', $this->sanitizer->clean('<p>Cláusula de rescisión — ¿qué pasa?</p>'));
    }

    public function test_it_builds_a_table_of_contents_in_document_order(): void
    {
        $result = $this->sanitizer->withHeadingIds('<h2>Primera parte</h2><p>a</p><h3>Detalle</h3><h2>Segunda parte</h2><h2>Primera parte</h2>');

        $this->assertSame(['primera-parte', 'detalle', 'segunda-parte', 'primera-parte-2'], array_column($result['toc'], 'id'));
        $this->assertSame([2, 3, 2, 2], array_column($result['toc'], 'level'));
        $this->assertStringContainsString('<h2 id="primera-parte">Primera parte</h2>', $result['html']);
    }

    public function test_it_handles_empty_input(): void
    {
        $this->assertSame('', $this->sanitizer->clean('  '));
        $this->assertSame(['html' => '', 'toc' => []], $this->sanitizer->withHeadingIds(''));
    }
}
