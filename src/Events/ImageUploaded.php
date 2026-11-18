<?php

namespace Pondol\Editor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageUploaded
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public string $disk,
        public string $path,
        public string $url,
        public string $filename,
        public string $originalName,
        public int $size,
        public int|string|null $userId = null,
    ) {}
}
