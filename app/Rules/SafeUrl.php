<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Prevents SSRF (Server-Side Request Forgery) attacks by blocking URLs that
 * point to internal/private infrastructure. Attackers use tools like Burp Suite
 * to replace external URLs with internal ones to probe cloud metadata endpoints,
 * internal services, and local loopback addresses.
 */
class SafeUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Let 'nullable' or 'required' handle empty values
        }

        $parsed = @parse_url($value);

        if (!$parsed || empty($parsed['host'])) {
            $fail('The :attribute must be a valid URL.');
            return;
        }

        $scheme = strtolower($parsed['scheme'] ?? '');

        // Only allow HTTP/HTTPS — block file://, ftp://, gopher://, etc.
        if (!in_array($scheme, ['http', 'https'], true)) {
            $fail('The :attribute must use http or https.');
            return;
        }

        $host = strtolower($parsed['host']);

        // Block localhost variants
        $blockedHostnames = [
            'localhost',
            'localhost.localdomain',
            'ip6-localhost',
            'ip6-loopback',
        ];

        if (in_array($host, $blockedHostnames, true)) {
            $fail('The :attribute cannot point to an internal address.');
            return;
        }

        // Resolve hostname to IP(s) and check each
        $ips = gethostbynamel($host);
        if ($ips === false) {
            // If we can't resolve it, block it (fail closed)
            $fail('The :attribute hostname could not be resolved.');
            return;
        }

        foreach ($ips as $ip) {
            if ($this->isPrivateOrReservedIp($ip)) {
                $fail('The :attribute cannot point to an internal or private network address.');
                return;
            }
        }
    }

    private function isPrivateOrReservedIp(string $ip): bool
    {
        // Use PHP's built-in filter to check against private/reserved ranges:
        // 10.x.x.x, 172.16-31.x.x, 192.168.x.x, 127.x.x.x, 169.254.x.x (AWS metadata),
        // ::1 (IPv6 loopback), fc00::/7 (IPv6 private), link-local, etc.
        $isPublic = filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );

        return $isPublic === false;
    }
}
