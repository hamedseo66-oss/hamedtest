<section class="testimonials" id="testimonials">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">نظرات بیماران</span>
            <h2 class="section-title">بیماران ما چه می‌گویند؟</h2>
        </div>

        <div class="testimonials-grid">
            <?php
            $reviews = [
                [
                    'name'   => 'محسن قاسمی',
                    'issue'  => 'دیسک کمر',
                    'stars'  => 5,
                    'text'   => 'بعد از ۳ ماه درمان، درد کمرم که ۲ سال ازم آزار می‌داد به طور کامل برطرف شد. تیم کلینیک فوق‌العاده حرفه‌ای و دلسوز هستن.',
                    'avatar' => 'M',
                    'color'  => 'blue',
                ],
                [
                    'name'   => 'زهرا رستمی',
                    'issue'  => 'آسیب ورزشی',
                    'stars'  => 5,
                    'text'   => 'بعد از عمل جراحی زانو، با برنامه توانبخشی کلینیک ظرف ۶ هفته به فعالیت ورزشی برگشتم. واقعاً ممنون از تیم خوبشون.',
                    'avatar' => 'Z',
                    'color'  => 'teal',
                ],
                [
                    'name'   => 'امیر حسینی',
                    'issue'  => 'گردن درد',
                    'stars'  => 5,
                    'text'   => 'برنامه درمانی اختصاصی که براम طراحی کردن خیلی موثر بود. ۱۰ جلسه لیزر و دستی باعث شد دردم ۹۰٪ کم بشه.',
                    'avatar' => 'A',
                    'color'  => 'orange',
                ],
            ];
            foreach ( $reviews as $i => $r ) :
            ?>
            <div class="testimonial-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 120; ?>">
                <div class="testimonial-card__stars">
                    <?php for ( $s = 0; $s < $r['stars']; $s++ ) echo physio_svg('star'); ?>
                </div>
                <p class="testimonial-card__text">"<?php echo esc_html( $r['text'] ); ?>"</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-avatar testimonial-avatar--<?php echo $r['color']; ?>">
                        <?php echo esc_html( $r['avatar'] ); ?>
                    </div>
                    <div>
                        <strong><?php echo esc_html( $r['name'] ); ?></strong>
                        <span><?php echo esc_html( $r['issue'] ); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
