<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تأكيد البريد الإلكتروني - i-Shifa</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Tahoma, Arial, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
          
          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); padding: 40px 40px 30px; text-align: center;">
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                  <td style="background-color: rgba(255,255,255,0.2); border-radius: 12px; padding: 12px; vertical-align: middle;">
                    <img src="https://img.icons8.com/ios-filled/30/ffffff/stethoscope.png" alt="i-Shifa" width="30" height="30" style="display: block;" />
                  </td>
                  <td style="padding-right: 12px; vertical-align: middle;">
                    <span style="font-size: 28px; font-weight: bold; color: #ffffff; letter-spacing: -0.5px;">i-Shifa</span>
                  </td>
                </tr>
              </table>
              <p style="color: rgba(255,255,255,0.85); font-size: 14px; margin: 16px 0 0;">منصتك الصحية الموثوقة</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding: 40px;">
              <h1 style="color: #1f2937; font-size: 24px; font-weight: bold; margin: 0 0 16px; text-align: center;">
                تأكيد البريد الإلكتروني
              </h1>
              
              <p style="color: #4b5563; font-size: 16px; line-height: 1.8; margin: 0 0 12px; text-align: center;">
                مرحباً،
              </p>
              
              <p style="color: #4b5563; font-size: 16px; line-height: 1.8; margin: 0 0 24px; text-align: center;">
                شكراً لتسجيلك في منصة i-Shifa. لتفعيل حسابك، يرجى تأكيد بريدك الإلكتروني بالضغط على الزر أدناه.
              </p>

              <!-- Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto 24px;">
                <tr>
                  <td align="center" style="border-radius: 12px; background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
                    <a href="{{ $verificationUrl }}
" target="_blank" style="display: inline-block; padding: 16px 48px; color: #ffffff; font-size: 16px; font-weight: bold; text-decoration: none; border-radius: 12px;">
                      تأكيد البريد الإلكتروني
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Divider -->
              <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 32px 0;" />

              <!-- Alternative link -->
              <p style="color: #6b7280; font-size: 13px; line-height: 1.8; text-align: center; margin: 0 0 8px;">
                إذا لم يعمل الزر، انسخ الرابط التالي والصقه في متصفحك:
              </p>
              <p style="color: #0d9488; font-size: 12px; word-break: break-all; text-align: center; margin: 0 0 24px; direction: ltr;">
                <a href="{{ $verificationUrl }}
" target="_blank" style="color: #0d9488; text-decoration: underline;">{{ $verificationUrl }}
</a>
              </p>

              <!-- Warning -->
              <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ecfdf5; border-radius: 10px; border: 1px solid #a7f3d0;">
                <tr>
                  <td style="padding: 16px 20px;">
                    <p style="color: #065f46; font-size: 13px; line-height: 1.7; margin: 0; text-align: center;">
                      ✉️ هذا الرابط صالح لمدة <strong>24 ساعة</strong>. إذا لم تقم بإنشاء حساب على i-Shifa، يمكنك تجاهل هذا البريد.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #f9fafb; padding: 24px 40px; border-top: 1px solid #e5e7eb;">
              <p style="color: #9ca3af; font-size: 12px; line-height: 1.7; margin: 0; text-align: center;">
                هذه الرسالة مرسلة تلقائياً من منصة i-Shifa. لا ترد على هذا البريد.
              </p>
              <p style="color: #9ca3af; font-size: 12px; margin: 8px 0 0; text-align: center;">
                © 2026 i-Shifa. جميع الحقوق محفوظة.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
