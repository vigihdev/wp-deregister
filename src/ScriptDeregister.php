<?php

declare(strict_types=1);

namespace WpDeregister;

use WpDeregister\Contracts\DeregisterContract;

final class ScriptDeregister implements DeregisterContract
{

    public function __construct(
        private readonly array $handles
    ) {}

    public function deregister(): void
    {
        foreach ($this->handles as $handle) {
            if (is_string($handle)) {
                add_action('wp_enqueue_scripts', function () use ($handle) {
                    wp_dequeue_script($handle);
                }, PHP_INT_MAX);
            }
        }
    }
}
