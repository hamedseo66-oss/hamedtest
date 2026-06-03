<?php
/**
 * Active Campaigns Section
 *
 * @package Royita
 */

$campaigns_query = royita_get_active_campaigns(6);
?>
<section class="section bg-white" id="active-campaigns">
    <div class="container">
        <!-- Header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <span class="section-label">فرصت‌های همکاری</span>
                <h2 class="section-title mb-0">کمپین‌های فعال</h2>
            </div>
            <a href="<?php echo esc_url(home_url('/campaigns/')); ?>" class="btn btn-outline">
                مشاهده همه کمپین‌ها
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="campaign-filter-tabs" role="tablist" aria-label="فیلتر کمپین‌ها" style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:2rem;">
            <?php
            $categories = [
                ''          => 'همه',
                'product'   => 'محصول',
                'services'  => 'خدمات',
                'lifestyle' => 'لایف‌استایل',
                'technology'=> 'تکنولوژی',
                'food'      => 'غذا',
            ];
            foreach ($categories as $slug => $label):
            ?>
            <button class="btn btn-sm <?php echo $slug === '' ? 'btn-primary' : 'btn-outline'; ?> campaign-filter-btn" 
                    data-category="<?php echo esc_attr($slug); ?>"
                    role="tab"
                    aria-selected="<?php echo $slug === '' ? 'true' : 'false'; ?>">
                <?php echo esc_html($label); ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Sort Options -->
        <div style="display:flex;align-items:center;justify-content:flex-end;gap:1rem;margin-bottom:1.5rem;">
            <label for="campaignSort" class="text-sm text-gray">مرتب‌سازی:</label>
            <select id="campaignSort" class="form-select" style="width:auto;padding:0.4rem 2rem 0.4rem 0.75rem;">
                <option value="newest">جدیدترین</option>
                <option value="budget_high">بیشترین بودجه</option>
                <option value="budget_low">کمترین بودجه</option>
                <option value="proposals">بیشترین پیشنهاد</option>
            </select>
        </div>

        <!-- Campaigns Grid -->
        <div class="campaigns-grid grid-3" id="campaignsGrid">
            <?php
            if ($campaigns_query->have_posts()):
                while ($campaigns_query->have_posts()):
                    $campaigns_query->the_post();
                    echo royita_campaign_card(get_the_ID());
                endwhile;
                wp_reset_postdata();
            else:
            ?>
            <div style="grid-column:1/-1;text-align:center;padding:4rem 2rem;">
                <div style="font-size:3rem;margin-bottom:1rem;">🎬</div>
                <h3 style="color:var(--royita-dark);margin-bottom:0.5rem;">کمپین فعالی یافت نشد</h3>
                <p style="color:var(--royita-gray);">به زودی کمپین‌های جدید اضافه خواهند شد.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Load More -->
        <?php if ($campaigns_query->max_num_pages > 1): ?>
        <div style="text-align:center;margin-top:3rem;">
            <button class="btn btn-outline btn-lg" id="loadMoreCampaigns" 
                    data-page="2" 
                    data-max-pages="<?php echo esc_attr($campaigns_query->max_num_pages); ?>">
                <span class="btn-text">بارگذاری کمپین‌های بیشتر</span>
                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
        </div>
        <?php endif; ?>

    </div>
</section>
