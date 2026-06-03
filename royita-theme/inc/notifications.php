<?php
/**
 * Royita Email Notifications
 *
 * @package Royita
 */

defined('ABSPATH') || exit;

/**
 * Get branded HTML email wrapper
 *
 * @param string $title    Email title
 * @param string $content  Email body HTML
 * @return string Full HTML email
 */
function royita_email_template(string $title, string $content): string {
    $site_name = get_bloginfo('name');
    $site_url  = home_url('/');
    $logo_url  = ROYITA_URI . '/assets/img/logo.png';

    return '<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . esc_html($title) . '</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Tahoma, Arial, sans-serif; background-color: #F9FAFB; direction: rtl; text-align: right; color: #111827; }
        .email-wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .email-header { background: linear-gradient(135deg, #0f4c8a 0%, #1A6DC7 100%); padding: 32px 40px; text-align: center; }
        .email-header__logo { font-size: 28px; font-weight: 800; color: #ffffff; text-decoration: none; letter-spacing: -0.5px; }
        .email-header__logo span { color: #fbbf24; }
        .email-header__tagline { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 6px; }
        .email-body { padding: 40px; }
        .email-body h2 { font-size: 22px; font-weight: 700; color: #111827; margin-bottom: 16px; }
        .email-body p { font-size: 15px; color: #4B5563; line-height: 1.8; margin-bottom: 16px; }
        .email-btn { display: inline-block; background: #1A6DC7; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 700; margin: 16px 0; }
        .email-btn-cta { background: #E96218; }
        .email-info-box { background: #F3F4F6; border-right: 4px solid #1A6DC7; padding: 20px 24px; border-radius: 8px; margin: 24px 0; }
        .email-info-box p { margin-bottom: 8px; font-size: 14px; }
        .email-info-box p:last-child { margin-bottom: 0; }
        .email-info-box strong { color: #111827; }
        .email-divider { border: none; border-top: 1px solid #E5E7EB; margin: 32px 0; }
        .email-footer { background: #F9FAFB; padding: 24px 40px; text-align: center; border-top: 1px solid #E5E7EB; }
        .email-footer p { font-size: 12px; color: #9CA3AF; line-height: 1.6; }
        .email-footer a { color: #1A6DC7; text-decoration: none; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-success { background: #D1FAE5; color: #065f46; }
        .badge-warning { background: #FEF3C7; color: #92400e; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="email-header__logo"><span>رو</span>یتا</div>
            <div class="email-header__tagline">پلتفرم اتصال برندها و کریتورهای ویدیو</div>
        </div>
        <div class="email-body">
            ' . $content . '
        </div>
        <div class="email-footer">
            <p>این ایمیل به صورت خودکار ارسال شده است. لطفاً پاسخ ندهید.</p>
            <p><a href="' . esc_url($site_url) . '">' . esc_html($site_name) . '</a> &bull; تهران، ایران</p>
            <p style="margin-top:8px;"><a href="' . esc_url($site_url . 'unsubscribe/') . '">لغو اشتراک ایمیل</a></p>
        </div>
    </div>
</body>
</html>';
}

/**
 * Get common email headers for RTL HTML email
 */
function royita_email_headers(): array {
    return [
        'Content-Type: text/html; charset=UTF-8',
        'From: رویتا <noreply@royita.ir>',
    ];
}

// =====================================================
// NOTIFY NEW PROPOSAL
// =====================================================

/**
 * Notify brand when a new proposal is submitted
 *
 * @param int $proposal_id Proposal post ID
 */
function royita_notify_new_proposal(int $proposal_id): void {
    $campaign_id = (int) get_post_meta($proposal_id, 'proposal_campaign', true);
    if (!$campaign_id) return;

    $brand_author_id = (int) get_post_field('post_author', $campaign_id);
    $brand_email     = get_the_author_meta('user_email', $brand_author_id);
    $brand_name      = get_the_author_meta('display_name', $brand_author_id);

    if (!$brand_email) return;

    $campaign_title = get_the_title($campaign_id);
    $campaign_url   = get_permalink($campaign_id);
    $price          = (int) get_post_meta($proposal_id, 'proposal_price', true);
    $days           = (int) get_post_meta($proposal_id, 'proposal_delivery_days', true);

    $creator_id   = (int) get_post_meta($proposal_id, 'proposal_creator', true);
    $creator_name = get_the_title($creator_id);

    $dashboard_url = home_url('/dashboard-brand/');

    $content = '
        <h2>پیشنهاد جدید دریافت کردید 🎬</h2>
        <p>سلام <strong>' . esc_html($brand_name) . '</strong>،</p>
        <p>یک کریتور برای کمپین شما پیشنهاد ارسال کرده است.</p>
        <div class="email-info-box">
            <p><strong>کمپین:</strong> ' . esc_html($campaign_title) . '</p>
            <p><strong>کریتور:</strong> ' . esc_html($creator_name) . '</p>
            <p><strong>مبلغ پیشنهادی:</strong> ' . esc_html(royita_format_price($price)) . '</p>
            <p><strong>مدت تحویل:</strong> ' . esc_html($days) . ' روز</p>
        </div>
        <p>برای مشاهده و بررسی پیشنهاد به داشبورد خود مراجعه کنید:</p>
        <a href="' . esc_url($dashboard_url) . '" class="email-btn">مشاهده در داشبورد</a>
        <hr class="email-divider">
        <p style="font-size:13px;color:#6B7280;">اگر این ایمیل مربوط به شما نیست، آن را نادیده بگیرید.</p>
    ';

    $subject = sprintf('پیشنهاد جدید برای کمپین "%s"', $campaign_title);

    wp_mail(
        $brand_email,
        $subject,
        royita_email_template($subject, $content),
        royita_email_headers()
    );
}

// =====================================================
// NOTIFY PROPOSAL ACCEPTED
// =====================================================

/**
 * Notify creator when their proposal is accepted
 *
 * @param int $proposal_id Proposal post ID
 */
function royita_notify_proposal_accepted(int $proposal_id): void {
    $creator_author_id = (int) get_post_field('post_author', $proposal_id);
    $creator_email     = get_the_author_meta('user_email', $creator_author_id);
    $creator_name      = get_the_author_meta('display_name', $creator_author_id);

    if (!$creator_email) return;

    $campaign_id    = (int) get_post_meta($proposal_id, 'proposal_campaign', true);
    $campaign_title = get_the_title($campaign_id);
    $price          = (int) get_post_meta($proposal_id, 'proposal_price', true);
    $days           = (int) get_post_meta($proposal_id, 'proposal_delivery_days', true);

    $brand_author_id = (int) get_post_field('post_author', $campaign_id);
    $brand_name      = get_the_author_meta('display_name', $brand_author_id);

    $dashboard_url = home_url('/dashboard-creator/');

    $content = '
        <h2>پیشنهاد شما پذیرفته شد! 🎉</h2>
        <p>سلام <strong>' . esc_html($creator_name) . '</strong>،</p>
        <p>تبریک! پیشنهاد شما توسط برند <strong>' . esc_html($brand_name) . '</strong> پذیرفته شد.</p>
        <div class="email-info-box">
            <p><strong>کمپین:</strong> ' . esc_html($campaign_title) . '</p>
            <p><strong>مبلغ تأیید شده:</strong> ' . esc_html(royita_format_price($price)) . '</p>
            <p><strong>مدت تحویل:</strong> ' . esc_html($days) . ' روز</p>
            <p><strong>وضعیت:</strong> <span class="badge badge-success">پذیرفته شده</span></p>
        </div>
        <p>پروژه به داشبورد شما اضافه شده است. برای شروع کار وارد شوید:</p>
        <a href="' . esc_url($dashboard_url) . '" class="email-btn email-btn-cta">شروع پروژه</a>
        <hr class="email-divider">
        <p style="font-size:13px;color:#6B7280;">موفقیت در این پروژه آرزوی ماست!</p>
    ';

    $subject = sprintf('پیشنهاد شما برای "%s" پذیرفته شد', $campaign_title);

    wp_mail(
        $creator_email,
        $subject,
        royita_email_template($subject, $content),
        royita_email_headers()
    );
}

// =====================================================
// NOTIFY PROJECT COMPLETED
// =====================================================

/**
 * Notify both parties when project is completed
 *
 * @param int $project_id Project post ID
 */
function royita_notify_project_completed(int $project_id): void {
    $campaign_id  = (int) get_post_meta($project_id, 'project_campaign', true);
    $creator_id   = (int) get_post_meta($project_id, 'project_creator', true);
    $project_price = (int) get_post_meta($project_id, 'project_price', true);

    $campaign_title = get_the_title($campaign_id);

    // Get brand email
    $brand_author_id = (int) get_post_field('post_author', $campaign_id);
    $brand_email     = get_the_author_meta('user_email', $brand_author_id);
    $brand_name      = get_the_author_meta('display_name', $brand_author_id);

    // Get creator email
    $creator_user_id  = (int) get_post_field('post_author', $creator_id);
    $creator_email    = get_the_author_meta('user_email', $creator_user_id);
    $creator_name     = get_the_title($creator_id);

    $project_url = home_url('/dashboard-brand/');

    // Email to brand
    if ($brand_email) {
        $brand_content = '
            <h2>پروژه با موفقیت تکمیل شد ✅</h2>
            <p>سلام <strong>' . esc_html($brand_name) . '</strong>،</p>
            <p>پروژه شما با موفقیت به پایان رسید.</p>
            <div class="email-info-box">
                <p><strong>کمپین:</strong> ' . esc_html($campaign_title) . '</p>
                <p><strong>کریتور:</strong> ' . esc_html($creator_name) . '</p>
                <p><strong>مبلغ:</strong> ' . esc_html(royita_format_price($project_price)) . '</p>
                <p><strong>وضعیت:</strong> <span class="badge badge-success">تکمیل شده</span></p>
            </div>
            <p>لطفاً نظر خود را درباره همکاری با کریتور ثبت کنید:</p>
            <a href="' . esc_url($project_url) . '" class="email-btn">ثبت نظر</a>
        ';

        $subject = sprintf('پروژه "%s" تکمیل شد', $campaign_title);
        wp_mail($brand_email, $subject, royita_email_template($subject, $brand_content), royita_email_headers());
    }

    // Email to creator
    if ($creator_email) {
        $creator_content = '
            <h2>پروژه شما تکمیل شد 🎬</h2>
            <p>سلام <strong>' . esc_html($creator_name) . '</strong>،</p>
            <p>پروژه شما تأیید و تکمیل شد. مبلغ پروژه به‌زودی به حسابتان واریز می‌شود.</p>
            <div class="email-info-box">
                <p><strong>کمپین:</strong> ' . esc_html($campaign_title) . '</p>
                <p><strong>مبلغ پروژه:</strong> ' . esc_html(royita_format_price($project_price)) . '</p>
                <p><strong>وضعیت پرداخت:</strong> <span class="badge badge-warning">در حال پردازش</span></p>
            </div>
            <p>می‌توانید تاریخچه پرداخت‌هایتان را در داشبورد مشاهده کنید:</p>
            <a href="' . esc_url(home_url('/dashboard-creator/')) . '" class="email-btn">داشبورد کریتور</a>
        ';

        $subject = sprintf('پروژه "%s" تکمیل شد - پرداخت در حال پردازش', $campaign_title);
        wp_mail($creator_email, $subject, royita_email_template($subject, $creator_content), royita_email_headers());
    }
}

// =====================================================
// NOTIFY NEW MESSAGE
// =====================================================

/**
 * Notify user when they receive a new message
 *
 * @param int    $from_user_id Sender user ID
 * @param int    $to_user_id   Recipient user ID
 * @param string $message      Message text
 */
function royita_notify_new_message(int $from_user_id, int $to_user_id, string $message): void {
    $to_email   = get_the_author_meta('user_email', $to_user_id);
    $to_name    = get_the_author_meta('display_name', $to_user_id);
    $from_name  = get_the_author_meta('display_name', $from_user_id);

    if (!$to_email) return;

    $preview = royita_truncate_text($message, 150);

    $content = '
        <h2>پیام جدید دریافت کردید 💬</h2>
        <p>سلام <strong>' . esc_html($to_name) . '</strong>،</p>
        <p><strong>' . esc_html($from_name) . '</strong> برای شما پیامی ارسال کرده است:</p>
        <div class="email-info-box">
            <p>' . nl2br(esc_html($preview)) . '</p>
        </div>
        <p>برای مشاهده و پاسخ به پیام وارد داشبورد شوید:</p>
        <a href="' . esc_url(home_url('/')) . '" class="email-btn">مشاهده پیام</a>
        <hr class="email-divider">
        <p style="font-size:13px;color:#6B7280;">اگر نمی‌خواهید اعلان پیام دریافت کنید، تنظیمات حساب خود را بررسی کنید.</p>
    ';

    $subject = sprintf('پیام جدید از %s', $from_name);

    wp_mail(
        $to_email,
        $subject,
        royita_email_template($subject, $content),
        royita_email_headers()
    );
}

// =====================================================
// NOTIFY DELIVERABLE SUBMITTED (کریتور فایل آپلود کرد)
// =====================================================
function royita_notify_deliverable_submitted(int $project_id): void {
    $campaign_id   = (int) get_post_meta($project_id, 'project_campaign', true);
    $brand_user_id = (int) get_post_meta($project_id, 'project_brand_id', true);
    $campaign_title = get_the_title($campaign_id);
    $brand_email   = get_the_author_meta('user_email', $brand_user_id);
    $brand_name    = get_the_author_meta('display_name', $brand_user_id);

    if (!$brand_email) return;

    $content = '
        <h2>فایل تحویلی آپلود شد 📁</h2>
        <p>سلام <strong>' . esc_html($brand_name) . '</strong>،</p>
        <p>کریتور پروژه "<strong>' . esc_html($campaign_title) . '</strong>" فایل نهایی را آپلود کرده است.</p>
        <p>لطفاً فایل را بررسی و تایید یا درخواست ویرایش نمایید.</p>
        <a href="' . esc_url(home_url('/brand/project/?id=' . $project_id)) . '" class="email-btn">مشاهده و بررسی فایل</a>
    ';

    $subject = sprintf('فایل تحویلی پروژه "%s" آماده بررسی است', $campaign_title);
    wp_mail($brand_email, $subject, royita_email_template($subject, $content), royita_email_headers());
}

// =====================================================
// NOTIFY REVISION REQUESTED (برند درخواست ویرایش داد)
// =====================================================
function royita_notify_revision_requested(int $project_id, string $note): void {
    $campaign_id     = (int) get_post_meta($project_id, 'project_campaign', true);
    $creator_user_id = (int) get_post_meta($project_id, 'project_creator_id', true);
    $campaign_title  = get_the_title($campaign_id);
    $creator_email   = get_the_author_meta('user_email', $creator_user_id);
    $creator_name    = get_the_author_meta('display_name', $creator_user_id);

    if (!$creator_email) return;

    $content = '
        <h2>درخواست ویرایش دریافت شد ✏️</h2>
        <p>سلام <strong>' . esc_html($creator_name) . '</strong>،</p>
        <p>برند پروژه "<strong>' . esc_html($campaign_title) . '</strong>" درخواست ویرایش ارسال کرده است.</p>
        <div class="email-info-box">
            <p><strong>توضیحات برند:</strong><br>' . nl2br(esc_html($note)) . '</p>
        </div>
        <a href="' . esc_url(home_url('/creator/project/?id=' . $project_id)) . '" class="email-btn">مشاهده پروژه</a>
    ';

    $subject = sprintf('درخواست ویرایش برای پروژه "%s"', $campaign_title);
    wp_mail($creator_email, $subject, royita_email_template($subject, $content), royita_email_headers());
}

// =====================================================
// NOTIFY DISPUTE OPENED (اختلاف ثبت شد)
// =====================================================
function royita_notify_dispute_opened(int $project_id, string $reason): void {
    $campaign_id = (int) get_post_meta($project_id, 'project_campaign', true);
    $campaign_title = get_the_title($campaign_id);
    $admin_email = get_option('admin_email');

    $content = '
        <h2>اختلاف جدید ثبت شد ⚠️</h2>
        <p>یک اختلاف برای پروژه "<strong>' . esc_html($campaign_title) . '</strong>" (شناسه: ' . $project_id . ') ثبت شده است.</p>
        <div class="email-info-box">
            <p><strong>دلیل:</strong><br>' . nl2br(esc_html($reason)) . '</p>
        </div>
        <a href="' . esc_url(admin_url('post.php?post=' . $project_id . '&action=edit')) . '" class="email-btn">بررسی در پنل ادمین</a>
    ';

    $subject = sprintf('اختلاف پروژه #%d — "%s"', $project_id, $campaign_title);
    wp_mail($admin_email, $subject, royita_email_template($subject, $content), royita_email_headers());
}
