<?php
/**
 * FAQ Accordion Section
 *
 * @package Royita
 */

$faqs = [
    [
        'q' => 'رویتا چیست و چطور کار می‌کند؟',
        'a' => 'رویتا یک پلتفرم آنلاین است که برندها را با تولیدکنندگان محتوای ویدیویی (کریتورها) متصل می‌کند. برندها کمپین خود را ثبت می‌کنند، کریتورها پیشنهاد ارسال می‌کنند، و برند بهترین گزینه را انتخاب می‌کند. پرداخت از طریق سیستم امانت‌داری ایمن ما انجام می‌شود.',
    ],
    [
        'q' => 'آیا رویتا برای برندهای کوچک هم مناسب است؟',
        'a' => 'بله! رویتا برای تمام اندازه‌های کسب‌وکار طراحی شده است. از استارتاپ‌های نوپا تا شرکت‌های بزرگ، همه می‌توانند با هر بودجه‌ای کمپین ثبت کنند. بسیاری از کریتورهای ما با بودجه‌های متنوع کار می‌کنند.',
    ],
    [
        'q' => 'پرداخت در رویتا چطور انجام می‌شود؟',
        'a' => 'رویتا از سیستم امانت‌داری (Escrow) استفاده می‌کند. پس از قبول پیشنهاد، مبلغ از برند دریافت و در حساب امانی نگهداری می‌شود. پس از تأیید تحویلی‌ها توسط برند، مبلغ به کریتور پرداخت می‌شود. این سیستم امنیت هر دو طرف را تضمین می‌کند.',
    ],
    [
        'q' => 'چطور می‌توانم به عنوان کریتور ثبت‌نام کنم؟',
        'a' => 'ثبت‌نام در رویتا کاملاً رایگان است. به صفحه ثبت‌نام بروید، نوع حساب "کریتور" را انتخاب کنید، اطلاعات پایه را وارد کنید و پروفایل خود را کامل کنید. بعد از بررسی و تأیید پروفایل، می‌توانید روی کمپین‌ها پیشنهاد ارسال کنید.',
    ],
    [
        'q' => 'آیا می‌توانم قبل از پذیرش پیشنهاد با کریتور صحبت کنم؟',
        'a' => 'بله! رویتا یک سیستم پیام‌رسانی داخلی دارد. می‌توانید قبل از تصمیم‌گیری با کریتور در مورد جزئیات پروژه صحبت کنید، سؤال بپرسید و نمونه کارها را بررسی کنید.',
    ],
    [
        'q' => 'اگر از نتیجه کار راضی نباشم چه اتفاقی می‌افتد؟',
        'a' => 'هر پروژه دارای تعداد مشخصی ویرایش رایگان است که در پیشنهاد مشخص شده. اگر پس از ویرایش‌ها هنوز ناراضی باشید، تیم پشتیبانی رویتا وارد عمل می‌شود. در صورت عدم توافق، پرونده بررسی و در صورت لزوم مبلغ بازگردانده می‌شود.',
    ],
    [
        'q' => 'کمیسیون رویتا چقدر است؟',
        'a' => 'رویتا ۱۵٪ از مبلغ پروژه را به عنوان کمیسیون دریافت می‌کند. این مبلغ شامل هزینه پلتفرم، امنیت پرداخت، پشتیبانی و بیمه معامله می‌شود. برای برندهایی که حجم بالای همکاری دارند، تعرفه‌های ویژه در نظر گرفته می‌شود.',
    ],
    [
        'q' => 'آیا محتوای تولید شده متعلق به برند است؟',
        'a' => 'بله، پس از پرداخت کامل، تمام حقوق محتوای تولیدی به برند منتقل می‌شود. این موضوع در قرارداد پایه رویتا صراحتاً ذکر شده است. البته بعضی کریتورها ممکن است برای استفاده در پورتفولیوی خود اجازه بخواهند که در پیشنهاد مشخص می‌شود.',
    ],
];
?>

<section class="section faq-section" id="faq">
    <div class="container">
        <div class="section-header">
            <span class="section-label">سؤالات متداول</span>
            <h2 class="section-title">پاسخ به سؤالات شما</h2>
            <p class="section-subtitle">اگر پاسخ سؤالتان را اینجا نیافتید، با ما تماس بگیرید</p>
        </div>

        <!-- FAQ List with Schema Markup -->
        <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">

            <?php foreach ($faqs as $i => $faq): ?>
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button class="faq-question" 
                        type="button"
                        aria-expanded="false"
                        aria-controls="faq-answer-<?php echo $i; ?>"
                        id="faq-question-<?php echo $i; ?>">
                    <span itemprop="name"><?php echo esc_html($faq['q']); ?></span>
                    <span class="faq-icon" aria-hidden="true">+</span>
                </button>
                <div class="faq-answer" 
                     id="faq-answer-<?php echo $i; ?>"
                     role="region"
                     aria-labelledby="faq-question-<?php echo $i; ?>"
                     itemscope
                     itemprop="acceptedAnswer"
                     itemtype="https://schema.org/Answer">
                    <div class="faq-answer__inner" itemprop="text">
                        <?php echo esc_html($faq['a']); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

        <!-- Bottom CTA -->
        <div style="text-align:center;margin-top:3rem;padding:2rem;background:var(--royita-bg-alt);border-radius:var(--radius-xl);">
            <p style="font-size:1.125rem;color:var(--royita-dark);font-weight:600;margin-bottom:0.75rem;">
                سؤال دیگری دارید؟
            </p>
            <p style="color:var(--royita-gray);margin-bottom:1.5rem;">تیم پشتیبانی ما آماده پاسخ‌گویی است</p>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                تماس با پشتیبانی
            </a>
        </div>
    </div>
</section>
