<section class="team" id="team">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">تیم درمانی</span>
            <h2 class="section-title">متخصصین ما</h2>
            <p class="section-desc">تیم ما متشکل از فیزیوتراپیست‌های مجرب با سال‌ها تجربه بالینی است.</p>
        </div>

        <div class="team-grid">
            <?php
            $doctors = [
                [ 'name' => 'دکتر محمد رضایی',   'title' => 'متخصص فیزیوتراپی ستون فقرات', 'exp' => '۱۵', 'color' => 'blue'   ],
                [ 'name' => 'دکتر فاطمه احمدی',   'title' => 'متخصص توانبخشی اعصاب',        'exp' => '۱۲', 'color' => 'teal'   ],
                [ 'name' => 'دکتر علی کریمی',     'title' => 'متخصص آسیب‌های ورزشی',        'exp' => '۱۰', 'color' => 'orange' ],
                [ 'name' => 'دکتر سارا محمدی',    'title' => 'متخصص فیزیوتراپی کودکان',    'exp' => '۸',  'color' => 'purple' ],
            ];
            foreach ( $doctors as $i => $doc ) :
            ?>
            <div class="team-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="team-card__photo team-card__photo--<?php echo $doc['color']; ?>">
                    <svg viewBox="0 0 240 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="120" cy="95" r="48" fill="rgba(255,255,255,0.25)"/>
                        <circle cx="120" cy="80" r="30" fill="rgba(255,255,255,0.85)"/>
                        <path d="M88 118 Q120 100 152 118 L165 185 Q120 202 75 185 Z" fill="rgba(255,255,255,0.8)"/>
                    </svg>
                    <div class="team-card__exp">
                        <strong><?php echo $doc['exp']; ?></strong>
                        <span>سال</span>
                    </div>
                </div>
                <div class="team-card__info">
                    <h3><?php echo esc_html( $doc['name'] ); ?></h3>
                    <p><?php echo esc_html( $doc['title'] ); ?></p>
                    <div class="team-card__socials">
                        <a href="#" aria-label="پروفایل">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </a>
                        <a href="#" aria-label="تماس">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.22h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6.07 6.07l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
