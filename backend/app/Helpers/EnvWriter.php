<?php

namespace App\Helpers;

class EnvWriter
{
    public static function set(string $key, string $value): void
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);

        $escapedKey = preg_quote($key, '/');

        $pattern = "/^{$escapedKey}=.*/m";

        $value = self::formatValue($value);

        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}\n";
        }

        file_put_contents($path, $content);
    }

    protected static function formatValue(string $value): string
    {
        if (str_contains($value, ' ') || str_contains($value, '#') || str_contains($value, '"') || str_contains($value, "'") || str_contains($value, '$')) {
            $value = '"' . str_replace('"', '\\"', $value) . '"';
        }

        return $value;
    }
}
