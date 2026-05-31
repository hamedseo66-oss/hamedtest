<section class="appointment" id="appointment">
    <div class="appointment__bg">
        <div class="appointment__shapes">
            <div class="appointment__shape appointment__shape--1"></div>
            <div class="appointment__shape appointment__shape--2"></div>
        </div>
    </div>
    <div class="container">
        <div class="appointment__inner">
            <div class="appointment__content" data-aos="fade-right">
                <span class="section-tag section-tag--light">نوبت‌دهی آنلاین</span>
                <h2 class="section-title section-title--white">همین امروز قدم اول را بردارید</h2>
                <p class="appointment__desc">نوبت اول شما رایگان است. تیم ما شرایط شما را ارزیابی کرده و بهترین برنامه درمانی را پیشنهاد می‌دهد.</p>
                <ul class="appointment__benefits">
                    <li><?php echo physio_svg('check'); ?> ارزیابی اولیه رایگان</li>
                    <li><?php echo physio_svg('check'); ?> تعیین وقت در همان روز</li>
                    <li><?php echo physio_svg('check'); ?> مشاوره با متخصص</li>
                    <li><?php echo physio_svg('check'); ?> پشتیبانی ۲۴ ساعته</li>
                </ul>
                <div class="appointment__contact">
                    <a href="tel:021-88888888" class="appointment__phone">
                        <?php echo physio_svg('phone'); ?>
                        <div>
                            <small>تماس مستقیم</small>
                            <strong>۰۲۱-۸۸۸۸۸۸۸۸</strong>
                        </div>
                    </a>
                </div>
            </div>

            <div class="appointment__form-wrapper" data-aos="fade-left">
                <div class="appointment__form-card">
                    <h3>رزرو نوبت</h3>
                    <p>فرم زیر را تکمیل کنید، در اسرع وقت با شما تماس می‌گیریم.</p>
                    <?php if ( function_exists('wpcf7_enqueue_scripts') ) : ?>
                        <?php echo do_shortcode('[contact-form-7 id="appointment-form"]'); ?>
                    <?php else : ?>
                    <form class="appt-form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
                        <?php wp_nonce_field( 'physio_appointment', 'physio_nonce' ); ?>
                        <input type="hidden" name="action" value="physio_appointment">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="appt-name">نام و نام خانوادگی *</label>
                                <input type="text" id="appt-name" name="name" required placeholder="نام شما">
                            </div>
                            <div class="form-group">
                                <label for="appt-phone">شماره موبایل *</label>
                                <input type="tel" id="appt-phone" name="phone" required placeholder="۰۹۱۲-۳۴۵-۶۷۸۹">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="appt-service">نوع خدمت</label>
                            <select id="appt-service" name="service">
                                <option value="">انتخاب کنید...</option>
                                <option>فیزیوتراپی ستون فقرات</option>
                                <option>فیزیوتراپی مفاصل</option>
                                <option>آسیب‌های ورزشی</option>
                                <option>فیزیوتراپی اعصاب</option>
                                <option>لیزر درمانی</option>
                                <option>توانبخشی پس از جراحی</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="appt-message">توضیحات</label>
                            <textarea id="appt-message" name="message" rows="3" placeholder="مختصری درباره مشکل خود بنویسید..."></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary btn--full">
                            ثبت درخواست نوبت
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
