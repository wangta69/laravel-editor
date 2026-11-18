<?php

namespace Pondol\Editor\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Editor
{
    /**
     * 요청(Request) 단위 에디터 인스턴스 트래킹 (session 대체)
     *
     * @var array<int, array{id: string, template: string}>
     */
    protected array $instances = [];

    public function resetInstances(): void
    {
        $this->instances = [];
    }

    public function registerInstance(string $id, string $template = ''): array
    {
        foreach ($this->instances as $instance) {
            if ($instance['id'] === $id) {
                return $this->instances;
            }
        }

        $this->instances[] = [
            'id' => $id,
            'template' => $template,
        ];

        return $this->instances;
    }

    /**
     * @return array<int, array{id: string, template: string}>
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * [기존 호환 100%] 본문 내 Base64 이미지를 파일로 추출 후 URL 치환
     */
    public static function extractBase64Image($str, $destination_dir, $destination_public_url)
    {
        if (empty($str) || ! is_string($str)) {
            return $str;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $pattern = '/<img[^>]*src=["\']?([^>"\']+)["\']?[^>]*>/i';
        preg_match_all($pattern, $str, $matches);

        if (empty($matches[1])) {
            return $str;
        }

        $filesystem = new Filesystem;

        foreach ($matches[1] as $matched) {
            if (strpos($matched, 'data:image/') !== 0) {
                continue;
            }

            if (! preg_match('/^data:image\/([a-zA-Z0-9+.-]+);base64,(.+)$/s', $matched, $parts)) {
                continue;
            }

            $extension = strtolower($parts[1]);
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            // svg+xml 등 스크립트 실행 가능 포맷 차단
            if (! in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            $binary = base64_decode($parts[2], true);
            if ($binary === false) {
                continue;
            }

            $file_name = Str::uuid()->toString().'.'.$extension;
            $filesystem->ensureDirectoryExists($destination_dir);
            File::put(rtrim($destination_dir, '/').'/'.$file_name, $binary);

            $publicUrl = rtrim($destination_public_url, '/').'/'.$file_name;
            $str = str_replace($matched, $publicUrl, $str);
        }

        return $str;
    }

    /**
     * [신규] Markdown -> 안전한 HTML 변환 헬퍼
     */
    public static function toHtml(?string $markdown): string
    {
        if ($markdown === null || $markdown === '') {
            return '';
        }

        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * [신규] Editor.js JSON -> 안전한 HTML 변환 헬퍼
     */
    public static function renderEditorJs(?string $json): string
    {
        if ($json === null || trim($json) === '') {
            return '';
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded) || empty($decoded['blocks']) || ! is_array($decoded['blocks'])) {
            return '';
        }

        $html = [];

        foreach ($decoded['blocks'] as $block) {
            $type = $block['type'] ?? '';
            $data = $block['data'] ?? [];

            switch ($type) {
                case 'header':
                    $level = max(1, min(6, (int) ($data['level'] ?? 2)));
                    $text = self::sanitizeInlineHtml((string) ($data['text'] ?? ''));
                    $html[] = "<h{$level}>{$text}</h{$level}>";
                    break;

                case 'paragraph':
                    $text = self::sanitizeInlineHtml((string) ($data['text'] ?? ''));
                    $html[] = "<p>{$text}</p>";
                    break;

                case 'list':
                    $style = ($data['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
                    $items = self::renderListItems($data['items'] ?? []);
                    $html[] = "<{$style}>{$items}</{$style}>";
                    break;

                case 'quote':
                    $text = self::sanitizeInlineHtml((string) ($data['text'] ?? ''));
                    $caption = e((string) ($data['caption'] ?? ''));
                    $cite = $caption !== '' ? "<footer>{$caption}</footer>" : '';
                    $html[] = "<blockquote><p>{$text}</p>{$cite}</blockquote>";
                    break;

                case 'code':
                    $code = e((string) ($data['code'] ?? ''));
                    $html[] = "<pre><code>{$code}</code></pre>";
                    break;

                case 'delimiter':
                    $html[] = '<hr />';
                    break;

                case 'image':
                    $url = e((string) ($data['file']['url'] ?? $data['url'] ?? ''));
                    $caption = e((string) ($data['caption'] ?? ''));
                    if ($url !== '') {
                        $figcaption = $caption !== '' ? "<figcaption>{$caption}</figcaption>" : '';
                        $html[] = "<figure><img src=\"{$url}\" alt=\"{$caption}\" />{$figcaption}</figure>";
                    }
                    break;

                case 'table':
                    $rows = $data['content'] ?? [];
                    if (is_array($rows)) {
                        $withHeadings = ! empty($data['withHeadings']);
                        $trs = [];
                        foreach ($rows as $idx => $row) {
                            if (! is_array($row)) {
                                continue;
                            }
                            $tag = ($withHeadings && $idx === 0) ? 'th' : 'td';
                            $cells = array_map(fn ($cell) => "<{$tag}>".self::sanitizeInlineHtml((string) $cell)."</{$tag}>", $row);
                            $trs[] = '<tr>'.implode('', $cells).'</tr>';
                        }
                        $html[] = '<table>'.implode('', $trs).'</table>';
                    }
                    break;
            }
        }

        return implode("\n", $html);
    }

    private static function renderListItems(array $items): string
    {
        $out = '';
        foreach ($items as $item) {
            if (is_string($item)) {
                $out .= '<li>'.self::sanitizeInlineHtml($item).'</li>';
            } elseif (is_array($item)) {
                $content = self::sanitizeInlineHtml((string) ($item['content'] ?? ''));
                $nested = ! empty($item['items']) && is_array($item['items'])
                    ? '<ul>'.self::renderListItems($item['items']).'</ul>'
                    : '';
                $out .= "<li>{$content}{$nested}</li>";
            }
        }

        return $out;
    }

    private static function sanitizeInlineHtml(string $html): string
    {
        return strip_tags($html, '<b><strong><i><em><u><s><a><code><mark><br>');
    }
}
