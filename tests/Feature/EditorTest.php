<?php

namespace Pondol\Editor\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Pondol\Editor\Events\ImageUploaded;
use Pondol\Editor\Facades\Editor;
use Pondol\Editor\Tests\TestCase;

class EditorTest extends TestCase
{
    public function test_default_view_renders_suneditor(): void
    {
        $html = view('editor::default', [
            'name' => 'content',
            'id' => 'content-id',
            'value' => '<p>Hello</p>',
            'attr' => ['class' => 'form-control'],
        ])->render();

        $this->assertStringContainsString('name="content"', $html);
        $this->assertStringContainsString('id="content-id"', $html);
        $this->assertStringContainsString('class="form-control"', $html);
        $this->assertStringContainsString('<p>Hello</p>', $html);
    }

    public function test_component_renders_with_inline_mode_and_preset(): void
    {
        $blade = '<x-editor-components template="easymde" name="md" id="md_1" :options="[\'section_mode\' => \'inline\', \'preset\' => \'minimal\', \'theme\' => \'dark\']" />';
        $rendered = $this->blade($blade);

        $rendered->assertSee('pondol-editor-theme-dark', false);
        $rendered->assertSee('easymde.min.js', false);
        $rendered->assertSee('"preset":"minimal"', false);
    }

    public function test_extract_base64_image_saves_png_and_blocks_svg(): void
    {
        $dir = sys_get_temp_dir().'/pondol-editor-test-'.uniqid();
        $pngBase64 = 'data:image/png;base64,'.base64_encode(base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        ));
        $svgBase64 = 'data:image/svg+xml;base64,'.base64_encode('<svg onload="alert(1)"></svg>');

        $input = '<p><img src="'.$pngBase64.'"><img src="'.$svgBase64.'"></p>';
        $output = Editor::extractBase64Image($input, $dir, '/uploads/');

        $this->assertStringContainsString('/uploads/', $output);
        $this->assertStringNotContainsString($pngBase64, $output);
        // SVG는 차단되어 치환·저장되지 않아야 함
        $this->assertStringContainsString($svgBase64, $output);

        @array_map('unlink', glob($dir.'/*') ?: []);
        @rmdir($dir);
    }

    public function test_markdown_and_editorjs_helpers_sanitize_and_render(): void
    {
        $mdHtml = Editor::toHtml("# Hello\n\n<script>alert(1)</script>**World**");
        $this->assertStringContainsString('<h1>Hello</h1>', $mdHtml);
        $this->assertStringContainsString('<strong>World</strong>', $mdHtml);
        $this->assertStringNotContainsString('<script>', $mdHtml);

        $json = json_encode([
            'blocks' => [
                ['type' => 'header', 'data' => ['level' => 2, 'text' => 'Title']],
                ['type' => 'paragraph', 'data' => ['text' => 'Body <script>bad()</script>']],
            ],
        ]);
        $blockHtml = Editor::renderEditorJs($json);
        $this->assertStringContainsString('<h2>Title</h2>', $blockHtml);
        $this->assertStringNotContainsString('<script>', $blockHtml);
    }

    public function test_unified_upload_stores_image_and_dispatches_event(): void
    {
        Storage::fake('public');
        Event::fake([ImageUploaded::class]);

        $file = UploadedFile::fake()->image('photo.png', 100, 100);

        $response = $this->postJson(route('editor.upload', ['driver' => 'suneditor']), [
            'file' => $file,
        ]);

        $response->assertOk()->assertJsonStructure(['result' => [['url', 'name', 'size']]]);
        Event::assertDispatched(ImageUploaded::class);
    }

    public function test_unified_upload_rejects_non_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('evil.php', 10, 'application/x-php');

        $this->postJson(route('editor.upload', ['driver' => 'default']), [
            'file' => $file,
        ])->assertStatus(422);
    }

    public function test_clean_tmp_command_deletes_expired_files(): void
    {
        Storage::fake('public');
        $disk = Storage::disk('public');

        $disk->put('tmp/editor/old.png', 'old');
        $disk->put('tmp/editor/fresh.png', 'fresh');

        touch($disk->path('tmp/editor/old.png'), now()->subDays(3)->getTimestamp());

        $this->artisan('pondol:clean-editor-tmp --days=1')->assertExitCode(0);

        $this->assertFalse($disk->exists('tmp/editor/old.png'));
        $this->assertTrue($disk->exists('tmp/editor/fresh.png'));
    }
}
