<section class="services" id="services">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">خدمات تخصصی</span>
            <h2 class="section-title">چه کمکی می‌توانیم بکنیم؟</h2>
            <p class="section-desc">با بهره‌گیری از تجهیزات مدرن و روش‌های نوین درمانی، برای هر نوع درد و آسیب، راه‌حل تخصصی داریم.</p>
        </div>

        <div class="services-grid">
            <?php
            $services = [
                [ 'icon' => 'spine',    'title' => 'فیزیوتراپی ستون فقرات', 'desc' => 'درمان تخصصی دردهای گردن، کمر و دیسک با روش‌های پیشرفته غیرجراحی', 'color' => 'blue'   ],
                [ 'icon' => 'knee',     'title' => 'فیزیوتراپی مفاصل',      'desc' => 'توانبخشی زانو، مچ، شانه و سایر مفاصل با تکنیک‌های تخصصی',           'color' => 'teal'   ],
                [ 'icon' => 'sport',    'title' => 'آسیب‌های ورزشی',         'desc' => 'بازگشت سریع ورزشکاران به میدان با برنامه توانبخشی اختصاصی',          'color' => 'orange' ],
                [ 'icon' => 'neuro',    'title' => 'فیزیوتراپی اعصاب',      'desc' => 'درمان اختلالات عصبی، سکته مغزی و بیماری‌های نورولوژیک',              'color' => 'purple' ],
                [ 'icon' => 'laser',    'title' => 'لیزر درمانی',           'desc' => 'کاهش درد و التهاب با لیزر پرتوان کلاس IV بدون عارضه',               'color' => 'green'  ],
                [ 'icon' => 'shoulder', 'title' => 'توانبخشی پس از جراحی',  'desc' => 'بازیابی قدرت و دامنه حرکتی با برنامه‌های مرحله‌بندی شده',            'color' => 'red'    ],
            ];
            foreach ( $services as $i => $s ) :
            ?>
            <div class="service-card service-card--<?php echo $s['color']; ?>" data-aos="fade-up" data-aos-delay="<?php echo $i * 80; ?>">
                <div class="service-card__icon">
                    <?php echo physio_svg( $s['icon'] ); ?>
                </div>
                <h3 class="service-card__title"><?php echo esc_html( $s['title'] ); ?></h3>
                <p class="service-card__desc"><?php echo esc_html( $s['desc'] ); ?></p>
                <a href="#" class="service-card__link">
                    بیشتر بدانید
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
