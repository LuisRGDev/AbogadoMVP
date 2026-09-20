<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Las primeras versiones guardaban el cuerpo del artículo como JSON
 * ([{"heading": "...", "text": "..."}] o [["título", "texto"]]).
 * Esta migración lo convierte a HTML para el editor de contenido enriquecido.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('articles')->orderBy('id')->each(function (object $article): void {
            $body = trim((string) $article->body);
            if ($body === '' || ! str_starts_with($body, '[')) {
                return;
            }

            $sections = json_decode($body, true);
            if (! is_array($sections)) {
                return;
            }

            $html = '';
            foreach ($sections as $section) {
                $heading = is_array($section) ? ($section['heading'] ?? $section[0] ?? '') : '';
                $text = is_array($section) ? ($section['text'] ?? $section[1] ?? '') : (string) $section;
                if ($heading !== '') {
                    $html .= '<h2>'.e($heading).'</h2>';
                }
                if ($text !== '') {
                    $html .= '<p>'.e($text).'</p>';
                }
            }

            DB::table('articles')->where('id', $article->id)->update(['body' => $html]);
        });
    }

    public function down(): void
    {
        // Conversión de una sola vía: el HTML resultante es compatible con la estructura anterior.
    }
};
