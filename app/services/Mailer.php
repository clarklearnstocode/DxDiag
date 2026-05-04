<?php
/**
 * EstateBook Mailer
 * Sends OTP emails via Gmail SMTP (TLS on port 587).
 *
 * SETUP — fill in the two lines marked below, then test with:
 *   http://localhost/EstateBook_fixed/public/test_email.php
 */
class Mailer
{
    // ── FILL THESE IN ─────────────────────────────────────────
    const SMTP_HOST = 'smtp.gmail.com';
    const SMTP_PORT = 587;
    const SMTP_FROM = 'ksabordo22@gmail.com';   // ← your Gmail (e.g. ksabordo22@gmail.com)
    const SMTP_PASS = 'vzwo wnxx bldr ttrn';    // ← 16-char App Password (no spaces needed)
    const FROM_NAME = 'EstateBook';
    // ──────────────────────────────────────────────────────────

    /**
     * Send an OTP email.
     * @return true|string   true on success, error message string on failure
     */
    public static function sendOTP(string $toEmail, string $toName, string $otp, string $role = 'User')
    {
        return self::send(
            $toEmail,
            $toName,
            'EstateBook – Your Verification Code',
            self::buildOTPEmail($toName, $otp, $role)
        );
    }

    // ── Core SMTP sender ──────────────────────────────────────

    private static function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool|string
    {
        $from  = self::SMTP_FROM;
        $pass  = self::SMTP_PASS;
        $name  = self::FROM_NAME;

        // Guard: reject placeholder values
        if ($from === 'your_gmail@gmail.com' || $pass === 'xxxx xxxx xxxx xxxx') {
            return 'SMTP not configured — open app/services/Mailer.php and fill in SMTP_FROM and SMTP_PASS.';
        }

        // Remove spaces from App Password (Google sometimes displays it with spaces)
        $pass = str_replace(' ', '', $pass);

        // Build RFC-compliant message headers + body
        $msgHeaders = "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "From: {$name} <{$from}>\r\n"
            . "To: {$toName} <{$toEmail}>\r\n"
            . "Subject: {$subject}\r\n"
            . "X-Mailer: EstateBook/1.0";

        $message = $msgHeaders . "\r\n\r\n" . $htmlBody;

        // Open plain TCP connection (STARTTLS upgrade happens inside)
        $errno = 0; $errstr = '';
        $sock  = @fsockopen(self::SMTP_HOST, self::SMTP_PORT, $errno, $errstr, 15);

        if (!$sock) {
            return "Cannot connect to SMTP server: [{$errno}] {$errstr}. "
                 . "Check that XAMPP is running and port 587 is not blocked by your firewall.";
        }

        // Helper closures
        $read = function () use ($sock): string {
            $data = '';
            while (!feof($sock)) {
                $line = fgets($sock, 1024);
                if ($line === false) break;
                $data .= $line;
                // Multi-line responses continue until a line with a space after the code
                if (strlen($line) >= 4 && $line[3] === ' ') break;
            }
            return $data;
        };

        $cmd = function (string $c) use ($sock): void {
            fwrite($sock, $c . "\r\n");
        };

        $code = function (string $resp): int {
            return (int) substr(trim($resp), 0, 3);
        };

        try {
            $r = $read(); // 220 smtp.gmail.com ESMTP
            if ($code($r) !== 220) {
                fclose($sock);
                return "Bad SMTP greeting: $r";
            }

            $cmd('EHLO localhost');
            $read(); // server capabilities

            $cmd('STARTTLS');
            $r = $read();
            if ($code($r) !== 220) {
                fclose($sock);
                return "STARTTLS rejected: $r";
            }

            // Upgrade the socket to TLS in-place
            $ok = stream_socket_enable_crypto(
                $sock, true,
                STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLS_CLIENT
            );
            if (!$ok) {
                fclose($sock);
                return "TLS handshake failed. Make sure OpenSSL is enabled in your XAMPP php.ini "
                     . "(extension=openssl must be uncommented).";
            }

            $cmd('EHLO localhost');
            $read();

            // Login
            $cmd('AUTH LOGIN');
            $read(); // 334 VXNlcm5hbWU6

            $cmd(base64_encode($from));
            $read(); // 334 UGFzc3dvcmQ6

            $cmd(base64_encode($pass));
            $r = $read();
            if ($code($r) !== 235) {
                fclose($sock);
                return "Authentication failed (code {$code($r)}). "
                     . "Common causes: (1) Wrong App Password — re-generate it in Google Account. "
                     . "(2) App Password has spaces — remove them. "
                     . "(3) 2-Step Verification is OFF — must be ON to use App Passwords.";
            }

            $cmd("MAIL FROM:<{$from}>");
            $r = $read();
            if ($code($r) !== 250) {
                fclose($sock);
                return "MAIL FROM rejected: $r";
            }

            $cmd("RCPT TO:<{$toEmail}>");
            $r = $read();
            if ($code($r) !== 250 && $code($r) !== 251) {
                fclose($sock);
                return "RCPT TO rejected: $r — check recipient email address";
            }

            $cmd('DATA');
            $read(); // 354 Start mail input

            // Send message, terminate with <CRLF>.<CRLF>
            $cmd($message . "\r\n.");
            $r = $read();
            if ($code($r) !== 250) {
                fclose($sock);
                return "Message rejected by server: $r";
            }

            $cmd('QUIT');
            fclose($sock);
            return true;

        } catch (Throwable $e) {
            @fclose($sock);
            return 'Unexpected error: ' . $e->getMessage();
        }
    }

    // ── Email template ────────────────────────────────────────

    private static function buildOTPEmail(string $name, string $otp, string $role): string
    {
        $firstName = htmlspecialchars(explode(' ', trim($name))[0]);
        $role      = htmlspecialchars($role);
        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0a0a0a;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr><td align="center" style="padding:40px 20px;">
      <table width="480" cellpadding="0" cellspacing="0"
             style="background:#141414;border-radius:16px;border:1px solid #222;overflow:hidden;">
        <tr>
          <td style="background:#0f0f0f;padding:24px 32px;border-bottom:1px solid #1e1e1e;">
            <span style="font-size:1.3rem;font-weight:800;color:#fff;">
              Estate<span style="color:#c9a07a;">Book</span>
            </span>
            <span style="margin-left:10px;background:rgba(201,160,122,0.12);color:#c9a07a;
                         padding:3px 9px;border-radius:20px;font-size:0.6rem;
                         text-transform:uppercase;letter-spacing:1px;vertical-align:middle;">
              {$role} Verification
            </span>
          </td>
        </tr>
        <tr>
          <td style="padding:32px;">
            <p style="color:#888;font-size:0.9rem;margin:0 0 16px;">Hi <strong style="color:#fff;">{$firstName}</strong>,</p>
            <p style="color:#555;font-size:0.85rem;line-height:1.7;margin:0 0 24px;">
              Use the code below to complete your login.
              It expires in <strong style="color:#c9a07a;">10 minutes</strong> and can only be used once.
            </p>
            <div style="background:#0d0d0d;border:1px dashed #2a2a2a;border-radius:12px;
                        padding:28px;text-align:center;margin-bottom:24px;">
              <div style="letter-spacing:14px;font-size:2.2rem;font-weight:800;
                          color:#c9a07a;font-family:'Courier New',monospace;padding-left:14px;">
                {$otp}
              </div>
              <p style="color:#333;font-size:0.72rem;margin:10px 0 0;">One-time verification code</p>
            </div>
            <p style="color:#3a3a3a;font-size:0.75rem;line-height:1.6;margin:0;">
              If you did not attempt to log in, you can safely ignore this email.
            </p>
          </td>
        </tr>
        <tr>
          <td style="padding:16px 32px;border-top:1px solid #1a1a1a;text-align:center;">
            <p style="color:#222;font-size:0.7rem;margin:0;">© EstateBook · Bacolod City, Philippines</p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
    }
}
