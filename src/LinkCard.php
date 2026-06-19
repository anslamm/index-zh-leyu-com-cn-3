<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private array $metadata;

    public function __construct(
        string $url = 'https://index-zh-leyu.com.cn',
        string $title = '乐鱼体育',
        string $description = '乐鱼体育 - 精彩体育赛事尽在掌握'
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->metadata = $this->generateMetadata();
    }

    private function generateMetadata(): array
    {
        return [
            'domain' => parse_url($this->url, PHP_URL_HOST),
            'keywords' => ['乐鱼体育', '体育赛事', '竞技'],
            'timestamp' => date('Y-m-d H:i:s'),
            'locale' => 'zh-CN'
        ];
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
        $this->metadata = $this->generateMetadata();
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
        $escapedDomain = htmlspecialchars($this->metadata['domain'], ENT_QUOTES, 'UTF-8');

        $html = '<div class="link-card">' . "\n";
        $html .= '    <a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer">' . "\n";
        $html .= '        <div class="link-card-content">' . "\n";
        $html .= '            <h3 class="link-card-title">' . $escapedTitle . '</h3>' . "\n";
        $html .= '            <p class="link-card-description">' . $escapedDescription . '</p>' . "\n";
        $html .= '            <span class="link-card-domain">' . $escapedDomain . '</span>' . "\n";
        $html .= '        </div>' . "\n";
        $html .= '    </a>' . "\n";
        $html .= '</div>' . "\n";

        return $html;
    }

    public function renderWithKeywords(): string
    {
        $baseHtml = $this->render();
        $keywordsList = array_map(function ($keyword) {
            return htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
        }, $this->metadata['keywords']);

        $keywordsHtml = '<div class="link-card-keywords">' . "\n";
        $keywordsHtml .= '    <span class="keywords-label">关键词：</span>' . "\n";
        $keywordsHtml .= '    <ul class="keywords-list">' . "\n";
        foreach ($keywordsList as $keyword) {
            $keywordsHtml .= '        <li class="keyword-item">' . $keyword . '</li>' . "\n";
        }
        $keywordsHtml .= '    </ul>' . "\n";
        $keywordsHtml .= '</div>' . "\n";

        return str_replace('</div>', $keywordsHtml . '</div>', $baseHtml);
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'metadata' => $this->metadata
        ];
    }

    public static function fromArray(array $data): self
    {
        $card = new self(
            $data['url'] ?? 'https://index-zh-leyu.com.cn',
            $data['title'] ?? '乐鱼体育',
            $data['description'] ?? '乐鱼体育 - 精彩体育赛事尽在掌握'
        );
        if (isset($data['metadata'])) {
            $card->metadata = array_merge($card->metadata, $data['metadata']);
        }
        return $card;
    }

    public static function createDefault(): self
    {
        return new self(
            'https://index-zh-leyu.com.cn',
            '乐鱼体育',
            '乐鱼体育 - 精彩体育赛事尽在掌握'
        );
    }
}

function renderLinkCard(
    string $url = 'https://index-zh-leyu.com.cn',
    string $title = '乐鱼体育',
    string $description = '乐鱼体育 - 精彩体育赛事尽在掌握',
    bool $showKeywords = false
): string {
    $card = new LinkCard($url, $title, $description);
    return $showKeywords ? $card->renderWithKeywords() : $card->render();
}