<?php
class OTPService
{
    const EXPIRY_SECONDS = 600;   // 10 minutes
    const CODE_LENGTH    = 6;

    /** Generate a numeric OTP, store hashed copy in session, return plain text. */
    public static function generate(string $role): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $code = str_pad((string)random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);

        $_SESSION['otp_code']    = password_hash($code, PASSWORD_DEFAULT);
        $_SESSION['otp_expires'] = time() + self::EXPIRY_SECONDS;
        $_SESSION['otp_for']     = $role;

        return $code;
    }

    /** Verify a submitted code. Returns true on success, false otherwise. */
    public static function verify(string $submitted, string $role): bool
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (
            empty($_SESSION['otp_code'])    ||
            empty($_SESSION['otp_expires']) ||
            ($_SESSION['otp_for'] ?? '') !== $role
        ) {
            return false;
        }

        if (time() > $_SESSION['otp_expires']) {
            self::clear();
            return false;
        }

        $valid = password_verify(trim($submitted), $_SESSION['otp_code']);
        if ($valid) self::clear();   // single-use: destroy after success
        return $valid;
    }

    /** Wipe OTP data from session. */
    public static function clear(): void
    {
        unset(
            $_SESSION['otp_code'],
            $_SESSION['otp_expires'],
            $_SESSION['otp_for']
        );
    }

    /** Remaining seconds until OTP expires (for the countdown timer). */
    public static function secondsLeft(): int
    {
        if (empty($_SESSION['otp_expires'])) return 0;
        return max(0, (int)$_SESSION['otp_expires'] - time());
    }
}
