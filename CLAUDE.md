# رویتا — حافظه پروژه
> پلتفرم Marketplace اتصال برند ایرانی به تولیدکننده محتوای ویدیویی

---

## Stack رسمی

| بخش | ابزار |
|---|---|
| Theme | Hello Elementor (parent) + royita-theme (child) |
| Page Builder | Elementor Pro |
| فیلدهای سفارشی | ACF Pro (از UI مدیریت می‌شه) |
| مدیریت کاربران | Ultimate Member |
| فرم‌ها | Elementor Forms |
| پرداخت | WooCommerce + زرین‌پال |
| پیام‌رسانی و اعلان | کد سفارشی (inc/ajax-handlers.php + inc/notifications.php) |

---

## ساختار CPT پروژه Royita

### موجودیت‌های اصلی سیستم

```
User (کاربر)
├── Brand (برند/کارفرما)
└── Creator (تولیدکننده محتوای ویدیویی)

Campaign (کمپین)
└── Proposal (پیشنهاد)
    └── Project (پروژه/قرارداد)
        └── Review (نظر و امتیاز)
```

---

## CPT 1 — Campaign (کمپین)

```
post_type: royita_campaign
```

| فیلد | نوع | توضیح |
|---|---|---|
| title | text | عنوان کمپین |
| content | textarea | توضیحات کامل بریف |
| campaign_status | select | open / in_review / in_progress / done / cancelled |
| campaign_category | taxonomy | campaign_category |
| campaign_platform | taxonomy | campaign_platform |
| campaign_budget_min | number | حداقل بودجه (تومان) |
| campaign_budget_max | number | حداکثر بودجه (تومان) |
| campaign_deadline | date | مهلت تحویل |
| campaign_brand_id | number | user_id برند |
| campaign_type | select | reel / youtube / tvc / product_video / documentary |
| campaign_deliverables | repeater | تحویلی‌ها (فرمت، مدت، تعداد) |
| campaign_requirements | repeater | الزامات فنی و محتوایی |
| campaign_reference_links | repeater | لینک‌های مرجع |
| campaign_brand_assets | file | فایل‌های برند (لوگو، گایدلاین) |
| campaign_target_audience | text | مخاطب هدف |
| campaign_kpi | textarea | معیارهای موفقیت |
| campaign_attachments | file | فایل‌های پیوست |
| campaign_views | number | تعداد بازدید |
| proposals_count | number | تعداد پیشنهادها (auto) |
| is_featured | true_false | کمپین ویژه |

### چرخه وضعیت Campaign

```
open → in_review → in_progress → done
  └──────────────────────────────→ cancelled
```

---

## CPT 2 — Proposal (پیشنهاد)

```
post_type: royita_proposal
```

| فیلد | نوع | توضیح |
|---|---|---|
| title | text | auto: «پیشنهاد [creator] برای [campaign]» |
| content | textarea | توضیحات پیشنهاد |
| proposal_campaign | relationship | ارتباط به Campaign |
| proposal_creator_id | number | user_id کریتور |
| proposal_price | number | قیمت پیشنهادی (تومان) |
| proposal_delivery_days | number | مدت تحویل (روز) |
| proposal_revisions | number | تعداد ویرایش مجاز |
| proposal_status | select | pending / accepted / rejected / withdrawn |
| proposal_cover_letter | textarea | نامه معرفی |
| proposal_portfolio_items | repeater | نمونه کارهای مرتبط |

### چرخه وضعیت Proposal

```
pending → accepted → (تبدیل به Project)
       └→ rejected
       └→ withdrawn (توسط creator)
```

---

## CPT 3 — Project (پروژه/قرارداد)

```
post_type: royita_project
```

| فیلد | نوع | توضیح |
|---|---|---|
| project_campaign | relationship | ارتباط به Campaign |
| project_proposal | relationship | ارتباط به Proposal تایید شده |
| project_brand_id | number | user_id برند |
| project_creator_id | number | user_id کریتور |
| project_amount | number | مبلغ نهایی (تومان) |
| project_status | select | active / in_review / revision_requested / completed / disputed / cancelled |
| project_start_date | date | تاریخ شروع |
| project_end_date | date | تاریخ پایان |
| project_escrow_status | select | held / released / refunded |
| project_payment_ref | text | شماره تراکنش |
| project_revisions_used | number | ویرایش‌های استفاده‌شده |
| project_revisions_max | number | حداکثر ویرایش |
| project_deliverables | repeater | فایل‌های تحویلی |
| project_milestones | repeater | مراحل تحویل |

### فیلدهای Repeater — project_milestones

| فیلد | نوع | توضیح |
|---|---|---|
| milestone_title | text | عنوان مرحله |
| milestone_amount | number | مبلغ این مرحله (تومان) |
| milestone_due_date | date | مهلت تحویل مرحله |
| milestone_status | select | pending / delivered / approved / disputed |

### چرخه وضعیت Project

```
active → in_review → completed
      └→ revision_requested → active
      └→ disputed
      └→ cancelled
```

---

## CPT 4 — Review (نظر و امتیاز)

```
post_type: royita_review
```

| فیلد | نوع | توضیح |
|---|---|---|
| review_project | relationship | ارتباط به Project |
| review_from_user | number | user_id نویسنده نظر |
| review_to_user | number | user_id دریافت‌کننده |
| review_rating | number | امتیاز کلی ۱ تا ۵ |
| review_communication | number | امتیاز ارتباط ۱ تا ۵ |
| review_quality | number | امتیاز کیفیت ۱ تا ۵ |
| review_delivery | number | امتیاز تحویل به موقع ۱ تا ۵ |
| review_type | select | brand_to_creator / creator_to_brand |
| review_text | textarea | متن نظر |
| review_is_public | true_false | نمایش عمومی |

---

## CPT 5 — Testimonial (توصیه‌نامه صفحه اصلی)

```
post_type: royita_testimonial
```

| فیلد | نوع | توضیح |
|---|---|---|
| testimonial_name | text | نام کاربر |
| testimonial_role | text | سمت |
| testimonial_company | text | نام برند/شرکت |
| testimonial_avatar | image | عکس |
| testimonial_text | textarea | متن توصیه |
| testimonial_rating | number | امتیاز ۱ تا ۵ |
| testimonial_type | select | brand / creator |
| testimonial_order | number | ترتیب نمایش |

---

## User Meta — Brand (برند)

```
user_role: brand_owner
```

| فیلد | نوع | توضیح |
|---|---|---|
| brand_company_name | text | نام شرکت/برند |
| brand_industry | select | حوزه فعالیت |
| brand_website | url | آدرس سایت |
| brand_logo | image | لوگو |
| brand_description | textarea | معرفی برند |
| brand_phone | text | تلفن |
| brand_verified | true_false | تایید هویت |
| brand_total_spent | number | مجموع پرداختی‌ها (تومان) |
| brand_total_campaigns | number | تعداد کمپین‌ها |
| brand_rating | number | میانگین امتیاز از کریتورها |

---

## User Meta — Creator (تولیدکننده محتوا)

```
user_role: creator_user
```

| فیلد | نوع | توضیح |
|---|---|---|
| creator_title | text | عنوان تخصص (مثلاً: ویدیوگرافر) |
| creator_type | select | videographer / influencer / motion_designer / animator / editor |
| creator_bio | textarea | معرفی |
| creator_skills | checkbox | مهارت‌ها |
| creator_experience_years | number | سال‌های تجربه |
| creator_portfolio | repeater | نمونه کارها |
| creator_rate_reel | number | نرخ ریلز (تومان) |
| creator_rate_youtube | number | نرخ یوتیوب (تومان) |
| creator_instagram_followers | number | فالوور اینستاگرام |
| creator_youtube_subscribers | number | سابسکرایبر یوتیوب |
| creator_availability | select | available / busy / vacation |
| creator_response_time | text | زمان پاسخ‌دهی |
| creator_location | text | شهر |
| creator_instagram | url | لینک اینستاگرام |
| creator_youtube | url | لینک یوتیوب |
| creator_verified | true_false | تایید هویت |
| creator_badge | select | none / rising / top / pro / elite |
| creator_rating_avg | number | میانگین امتیاز |
| creator_total_projects | number | تعداد پروژه‌های انجام‌شده |
| creator_total_earned | number | مجموع درآمد (تومان) |

---

## Taxonomy ها

### campaign_category (دسته‌بندی کمپین)
- محصول
- خدمات
- لایف‌استایل
- تکنولوژی
- غذا
- سلامت
- فشن
- آموزش

### campaign_platform (پلتفرم هدف)
- اینستاگرام
- یوتیوب
- تیک‌تاک
- آپارات

### creator_specialty (تخصص کریتور)
- ویدیوگرافی
- موشن‌گرافیک
- انیمیشن
- اینفلوئنسر
- تدوین
- تصویربرداری

### creator_industry (صنعت کریتور)
- آرایشی
- الکترونیک
- فشن
- غذا
- ورزش
- فین‌تک
- سلامت

---

## روابط بین موجودیت‌ها

```
Brand (User / brand_owner)
    │
    └──[ایجاد می‌کنه]──► Campaign (CPT)
                              │
                    ◄──[ثبت می‌کنه]── Creator (User / creator_user)
                              │              │
                              └──► Proposal ─┘
                                      │
                              [تایید برند]
                                      │
                                      ▼
                               Project (CPT)
                                      │
                              [اتمام پروژه]
                                      │
                                      ▼
                                Review (CPT) ← دو طرفه
```

---

## نکات مهم پیاده‌سازی

1. همه روابط از طریق ACF Relationship Field یا user_id مستقیم
2. user_id را همیشه در CPT ها ذخیره کن (برای WP_Query سریع)
3. Status ها با ACF Select Field مدیریت می‌شن
4. Milestone در Project برای پرداخت مرحله‌ای ضروری است
5. Review دو طرفه است: برند به creator و creator به برند
6. Escrow: پرداخت قبل از شروع، آزادسازی بعد از تایید برند
7. پیام‌رسانی و اعلان با کد سفارشی هندل می‌شه (نه افزونه)

---

## فایل‌های قالب

```
royita-theme/
├── style.css
├── functions.php
├── header.php / footer.php
├── front-page.php          ← Elementor-managed
├── page.php / single.php / archive.php / 404.php
├── inc/
│   ├── post-types.php      ← ۷ CPT (کد)
│   ├── taxonomies.php      ← دسته‌بندی‌ها (کد)
│   ├── roles.php           ← brand_owner / creator_user (کد)
│   ├── ajax-handlers.php   ← پیشنهاد، فیلتر، پیام (کد)
│   ├── notifications.php   ← ایمیل خودکار (کد)
│   ├── helpers.php         ← توابع کمکی (کد)
│   └── schema.php          ← Schema.org (کد)
├── assets/css/
│   ├── main.css
│   ├── rtl.css
│   └── login.css
└── assets/js/
    ├── main.js
    └── dashboard.js
```

---

*آخرین به‌روزرسانی: خرداد ۱۴۰۵*
