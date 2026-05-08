<?php
/**
 * TOTPService — RFC 6238 Time-based One-Time Password
 * Compatible with Google Authenticator, Microsoft Authenticator, Authy.
 * Zero dependencies — pure PHP, no Composer needed.
 */
class TOTPService
{
    const DIGITS   = 6;
    const PERIOD   = 30;   // seconds per code window
    const ALGO     = 'sha1';
    const WINDOWS  = 1;    // accept 1 window before/after for clock drift

    // ── Base32 alphabet (RFC 4648) ────────────────────────────
    private static string $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a random Base32 secret key (160 bits = 32 chars).
     * Store this in the DB — never show it again after setup.
     */
    public static function generateSecret(): string
    {
        $bytes  = random_bytes(20);   // 160 bits
        return self::base32Encode($bytes);
    }

    /**
     * Verify a 6-digit code against a stored secret.
     * Accepts codes from the current window ± WINDOWS (handles clock drift).
     */
    public static function verify(string $secret, string $code): bool
    {
        $code = preg_replace('/\D/', '', $code);   // strip non-digits
        if (strlen($code) !== self::DIGITS) return false;

        $timestamp = (int) floor(time() / self::PERIOD);

        for ($offset = -self::WINDOWS; $offset <= self::WINDOWS; $offset++) {
            if (self::generateCode($secret, $timestamp + $offset) === $code) {
                return true;
            }
        }
        return false;
    }

    /**
     * Build the otpauth:// URI that QR code scanners understand.
     * $label  = shown in the Authenticator app (e.g. user's email)
     * $issuer = app name shown above the label
     */
    public static function getOtpauthUri(string $secret, string $label, string $issuer = 'EstateBook'): string
    {
        return 'otpauth://totp/'
            . rawurlencode($issuer . ':' . $label)
            . '?secret=' . $secret
            . '&issuer=' . rawurlencode($issuer)
            . '&algorithm=SHA1'
            . '&digits=' . self::DIGITS
            . '&period=' . self::PERIOD;
    }

    /**
     * Return a Google Charts QR code image URL for the otpauth URI.
     * The chart API is free, needs no API key, and works offline once scanned.
     */
    public static function getQRCodeUrl(string $otpauthUri, int $size = 220): string
    {
        return 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size
             . '&chld=M|0&cht=qr&chl=' . rawurlencode($otpauthUri);
    }

    // ── Internal helpers ──────────────────────────────────────

    private static function generateCode(string $secret, int $timestamp): string
    {
        $key     = self::base32Decode($secret);
        $time    = pack('N*', 0) . pack('N*', $timestamp);   // 8-byte big-endian
        $hash    = hash_hmac(self::ALGO, $time, $key, true);
        $offset  = ord($hash[strlen($hash) - 1]) & 0x0F;
        $code    = (
            ((ord($hash[$offset])     & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) <<  8) |
             (ord($hash[$offset + 3]) & 0xFF)
        ) % (10 ** self::DIGITS);

        return str_pad((string)$code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    private static function base32Encode(string $bytes): string
    {
        $chars  = self::$base32Chars;
        $result = '';
        $bits   = 0;
        $buffer = 0;

        foreach (str_split($bytes) as $byte) {
            $buffer = ($buffer << 8) | ord($byte);
            $bits  += 8;
            while ($bits >= 5) {
                $bits  -= 5;
                $result .= $chars[($buffer >> $bits) & 0x1F];
            }
        }
        if ($bits > 0) {
            $result .= $chars[($buffer << (5 - $bits)) & 0x1F];
        }
        return $result;
    }

    private static function base32Decode(string $encoded): string
    {
        $chars  = self::$base32Chars;
        $encoded = strtoupper(preg_replace('/\s/', '', $encoded));
        $result  = '';
        $bits    = 0;
        $buffer  = 0;

        foreach (str_split($encoded) as $char) {
            $pos = strpos($chars, $char);
            if ($pos === false) continue;
            $buffer = ($buffer << 5) | $pos;
            $bits  += 5;
            if ($bits >= 8) {
                $bits  -= 8;
                $result .= chr(($buffer >> $bits) & 0xFF);
            }
        }
        return $result;
    }
}
