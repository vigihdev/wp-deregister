<?php

declare(strict_types=1);

namespace vigihdev\WpDeregister\Services;

use vigihdev\WpDeregister\Contracts\DeregisterContract;

final class DeregisterService implements DeregisterContract
{
    public function __construct(
        private readonly array $enqueueScriptsHandles = [],
        private readonly array $filterHandles = [],
        private readonly array $removeActionHandles = []
    ) {}

    public function deregister(): void
    {
        $this->deregisterEnqueueScripts();
        $this->deregisterFilters();
        $this->deregisterRemoveActions();
    }

    private function deregisterEnqueueScripts(): void
    {
        foreach ($this->enqueueScriptsHandles as $handle) {
            if (is_string($handle)) {
                add_action('wp_enqueue_scripts', function () use ($handle) {
                    wp_dequeue_style($handle);
                    wp_dequeue_script($handle);
                    wp_deregister_script($handle);
                }, PHP_INT_MAX);
            }
        }
    }

    private function deregisterFilters(): void
    {
        foreach ($this->filterHandles as $handles) {
            if (is_array($handles) && count($handles) >= 2) {
                call_user_func_array('add_filter', $handles);
            }
        }
    }

    private function deregisterRemoveActions(): void
    {
        foreach ($this->removeActionHandles as $handles) {
            if (is_array($handles) && count($handles) >= 2) {
                call_user_func_array('remove_action', $handles);
            }
        }
    }
}
