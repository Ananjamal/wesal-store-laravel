<?php

namespace App\Traits;

trait CleansUrls
{
    /**
     * Clean and format a URL to support both absolute same-domain links and relative paths.
     */
    protected function cleanUrl(?string $url): ?string
    {
        if (empty($url)) {
            return $url;
        }

        // Standardize url schemas and hosts
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $parsed = parse_url($url);
            $host = $parsed['host'] ?? '';
            
            // Check if it's the local website domain
            $isLocal = str_contains($host, 'wesal-store.test') 
                || str_contains($host, 'localhost') 
                || str_contains($host, '127.0.0.1')
                || (request()->getHost() && $host === request()->getHost());

            if ($isLocal) {
                $path = $parsed['path'] ?? '/';
                $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
                $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
                return $path . $query . $fragment;
            }
        }

        // If it does not start with / and is not an external protocol or hash, add leading /
        if (!str_starts_with($url, '/') 
            && !str_starts_with($url, 'http') 
            && !str_starts_with($url, '#') 
            && !str_starts_with($url, 'mailto:') 
            && !str_starts_with($url, 'tel:')
        ) {
            return '/' . $url;
        }

        return $url;
    }
}
