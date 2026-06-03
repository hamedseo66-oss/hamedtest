/**
 * Royita Dashboard JavaScript
 * Loaded only on dashboard page templates
 *
 * @package Royita
 */

'use strict';

(function () {

    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

    // Re-use toast from main.js if available
    const showToast = window.royita ? window.royita.showToast : function (msg, type) {
        alert(msg);
    };

    // =====================================================
    // SIDEBAR ACTIVE STATE
    // =====================================================
    function initSidebarNav() {
        const navItems = $$('.dashboard-sidebar__nav-item');
        const current  = window.location.href;

        navItems.forEach(item => {
            if (item.href && current.includes(item.href)) {
                item.classList.add('active');
                item.setAttribute('aria-current', 'page');
            }
        });

        // Handle hash-based sub-navigation
        navItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (this.getAttribute('href') && this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    navItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');

                    const targetId = this.getAttribute('href').slice(1);
                    const target   = document.getElementById(targetId);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        $$('.dashboard-section').forEach(s => s.classList.remove('active'));
                        target.classList.add('active');
                    }
                }
            });
        });
    }

    // =====================================================
    // NOTIFICATION BADGE UPDATE
    // =====================================================
    function initNotificationBadge() {
        const badges = $$('.dashboard-sidebar__nav-badge');

        // Poll for new notifications every 60 seconds
        function fetchNotificationCounts() {
            if (!royitaDashboard) return;

            const formData = new FormData();
            formData.append('action', 'royita_get_notification_counts');
            formData.append('nonce',  royitaDashboard.nonce);

            fetch(royitaDashboard.ajaxurl, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.data.counts) {
                        const counts = data.data.counts;
                        badges.forEach(badge => {
                            const key = badge.dataset.key;
                            if (key && counts[key] !== undefined) {
                                badge.textContent = counts[key] > 0 ? counts[key] : '';
                                badge.style.display = counts[key] > 0 ? '' : 'none';
                            }
                        });
                    }
                })
                .catch(() => {}); // Silently fail
        }

        // Initial fetch + polling
        fetchNotificationCounts();
        setInterval(fetchNotificationCounts, 60000);
    }

    // =====================================================
    // PROJECT STATUS UPDATE
    // =====================================================
    function initProjectStatusUpdate() {
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-action="update-status"]');
            if (!btn) return;

            const projectId = btn.dataset.projectId;
            const newStatus = btn.dataset.status;

            if (!projectId || !newStatus) return;

            // Confirmation for destructive actions
            const destructiveStatuses = ['cancelled', 'disputed'];
            if (destructiveStatuses.includes(newStatus)) {
                const confirmed = confirm('آیا از این عملیات اطمینان دارید؟ این عمل قابل بازگشت نیست.');
                if (!confirmed) return;
            }

            btn.disabled = true;
            btn.innerHTML = '<div class="spinner spinner-sm"></div>';

            const formData = new FormData();
            formData.append('action',     'royita_update_project_status');
            formData.append('nonce',      typeof royitaDashboard !== 'undefined' ? royitaDashboard.nonce : '');
            formData.append('project_id', projectId);
            formData.append('status',     newStatus);

            fetch(typeof royitaDashboard !== 'undefined' ? royitaDashboard.ajaxurl : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.data.message, 'success');

                        // Update status badge on the page
                        const statusBadge = document.querySelector(`[data-project-status="${projectId}"]`);
                        if (statusBadge) {
                            statusBadge.className = `badge ${data.data.status_class}`;
                            statusBadge.textContent = data.data.status_label;
                        }

                        // Reload page section if completed
                        if (newStatus === 'completed') {
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    } else {
                        showToast(data.data && data.data.message ? data.data.message : 'خطایی رخ داد', 'error');
                        btn.disabled = false;
                        btn.textContent = 'تغییر وضعیت';
                    }
                })
                .catch(() => {
                    showToast('خطا در اتصال به سرور', 'error');
                    btn.disabled = false;
                });
        });
    }

    // =====================================================
    // FILE UPLOAD PREVIEW
    // =====================================================
    function initFileUpload() {
        const uploadAreas = $$('.file-upload-area');

        uploadAreas.forEach(area => {
            const input    = area.querySelector('input[type="file"]');
            const previewList = area.closest('.form-group')?.querySelector('.file-preview-list');

            if (!input) return;

            // Drag and Drop
            area.addEventListener('dragover', function (e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            area.addEventListener('dragleave', function () {
                this.classList.remove('dragover');
            });

            area.addEventListener('drop', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    handleFiles(e.dataTransfer.files);
                }
            });

            input.addEventListener('change', function () {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (!previewList) return;
                previewList.innerHTML = '';

                Array.from(files).forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'file-preview-item';
                    item.innerHTML = `
                        <span style="font-size:1.2rem">${getFileIcon(file.type)}</span>
                        <span class="file-preview-item__name">${file.name}</span>
                        <span class="file-preview-item__size">${formatFileSize(file.size)}</span>
                        <button type="button" class="file-preview-item__remove" title="حذف" aria-label="حذف فایل ${file.name}">✕</button>
                    `;
                    previewList.appendChild(item);
                });

                // Remove button
                previewList.querySelectorAll('.file-preview-item__remove').forEach(btn => {
                    btn.addEventListener('click', function () {
                        this.closest('.file-preview-item').remove();
                    });
                });
            }

            function getFileIcon(mime) {
                if (mime.startsWith('video/'))  return '🎬';
                if (mime.startsWith('image/'))  return '🖼';
                if (mime.includes('pdf'))        return '📄';
                if (mime.includes('zip'))        return '📦';
                return '📁';
            }

            function formatFileSize(bytes) {
                if (bytes < 1024)       return bytes + ' B';
                if (bytes < 1048576)   return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            }
        });
    }

    // =====================================================
    // PROPOSAL FORM SUBMISSION
    // =====================================================
    function initProposalForm() {
        const form = $('#proposalForm');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('[type="submit"]');
            const formData  = new FormData(form);

            // Validation
            const price       = parseInt(formData.get('price'));
            const days        = parseInt(formData.get('delivery_days'));
            const coverLetter = (formData.get('cover_letter') || '').trim();

            let errors = [];
            if (!price || price <= 0)   errors.push('مبلغ پیشنهادی الزامی است');
            if (!days || days <= 0)     errors.push('مدت تحویل الزامی است');
            if (!coverLetter)           errors.push('نامه پوششی الزامی است');
            if (coverLetter.length < 50) errors.push('نامه پوششی باید حداقل ۵۰ کاراکتر باشد');

            if (errors.length) {
                showToast(errors[0], 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');

            formData.append('action', 'royita_submit_proposal');
            formData.append('nonce', typeof royitaVars !== 'undefined' ? royitaVars.nonce : (typeof royitaDashboard !== 'undefined' ? royitaDashboard.nonce : ''));

            fetch(typeof royitaVars !== 'undefined' ? royitaVars.ajaxurl : (typeof royitaDashboard !== 'undefined' ? royitaDashboard.ajaxurl : '/wp-admin/admin-ajax.php'), {
                method: 'POST',
                body: formData,
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.data.message, 'success');
                        form.reset();

                        // Close modal if open
                        const modal = form.closest('.royita-modal');
                        if (modal) {
                            modal.classList.remove('is-open');
                        }

                        // Update proposal count in UI
                        const countEl = document.querySelector('.proposals-count-badge');
                        if (countEl) {
                            const current = parseInt(countEl.textContent) || 0;
                            countEl.textContent = current + 1;
                        }
                    } else {
                        showToast(data.data && data.data.message ? data.data.message : 'خطایی رخ داد', 'error');
                    }
                })
                .catch(() => showToast('خطا در اتصال به سرور', 'error'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-loading');
                });
        });
    }

    // =====================================================
    // CHARACTER COUNTER FOR TEXTAREAS
    // =====================================================
    function initCharCounters() {
        $$('textarea[maxlength], textarea[data-char-count]').forEach(textarea => {
            const maxLength = parseInt(textarea.getAttribute('maxlength') || textarea.dataset.charCount);
            if (!maxLength) return;

            const counter = document.createElement('div');
            counter.className = 'char-counter';
            counter.textContent = `۰ / ${maxLength}`;
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);

            function updateCounter() {
                const length = textarea.value.length;
                const remaining = maxLength - length;
                counter.textContent = `${length} / ${maxLength}`;

                counter.classList.remove('near-limit', 'at-limit');
                if (remaining <= 10) {
                    counter.classList.add('at-limit');
                } else if (remaining <= 50) {
                    counter.classList.add('near-limit');
                }
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        });

        // Min character counter (e.g., cover letter)
        $$('textarea[data-min-chars]').forEach(textarea => {
            const minChars = parseInt(textarea.dataset.minChars);
            const counter  = document.createElement('div');
            counter.className = 'char-counter';
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);

            function updateMinCounter() {
                const length    = textarea.value.length;
                const remaining = minChars - length;

                if (remaining > 0) {
                    counter.textContent = `حداقل ${remaining} کاراکتر دیگر بنویسید`;
                    counter.className   = 'char-counter near-limit';
                    textarea.classList.add('is-invalid');
                    textarea.classList.remove('is-valid');
                } else {
                    counter.textContent = `✓ ${length} کاراکتر`;
                    counter.className   = 'char-counter';
                    counter.style.color = 'var(--royita-success)';
                    textarea.classList.remove('is-invalid');
                    textarea.classList.add('is-valid');
                }
            }

            textarea.addEventListener('input', updateMinCounter);
        });
    }

    // =====================================================
    // CONFIRMATION DIALOGS
    // =====================================================
    function initConfirmations() {
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return;

            const message = btn.dataset.confirm || 'آیا از این عملیات اطمینان دارید؟';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });
    }

    // =====================================================
    // DASHBOARD TABS (Generic)
    // =====================================================
    function initDashboardTabs() {
        const tabNavItems = $$('.tab-nav__item');

        tabNavItems.forEach(item => {
            item.addEventListener('click', function () {
                const tabId = this.dataset.tab;
                if (!tabId) return;

                // Update nav
                const navItems = this.closest('.tab-nav')?.querySelectorAll('.tab-nav__item') || [];
                navItems.forEach(i => {
                    i.classList.remove('active');
                    i.setAttribute('aria-selected', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');

                // Show content
                const contents = document.querySelectorAll('.tab-content');
                contents.forEach(c => c.classList.remove('active'));
                const target = document.getElementById(tabId);
                if (target) target.classList.add('active');
            });
        });
    }

    // =====================================================
    // AUTO-REFRESH STATS
    // =====================================================
    function initAutoRefreshStats() {
        // Only refresh on dashboard pages
        if (!document.querySelector('.dashboard-stats-grid')) return;

        // Stats auto-refresh every 5 minutes
        setInterval(() => {
            const statsGrid = $('.dashboard-stats-grid');
            if (!statsGrid) return;

            // Visual indicator
            statsGrid.style.opacity = '0.7';
            setTimeout(() => { statsGrid.style.opacity = '1'; }, 1000);
        }, 300000);
    }

    // =====================================================
    // PROPOSAL MODAL
    // =====================================================
    function initProposalModal() {
        const openBtns = $$('[data-open-proposal-modal]');
        const modal    = $('#proposalModal');

        if (!modal) return;

        const overlay = modal.querySelector('.royita-modal__overlay');
        const closeBtn = modal.querySelector('.royita-modal__close');

        function openModal(campaignId) {
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';

            const campaignInput = modal.querySelector('input[name="campaign_id"]');
            if (campaignInput && campaignId) {
                campaignInput.value = campaignId;
            }

            const firstInput = modal.querySelector('input:not([type="hidden"]), textarea');
            if (firstInput) firstInput.focus();
        }

        function closeModal() {
            modal.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => openModal(btn.dataset.openProposalModal));
        });

        overlay && overlay.addEventListener('click', closeModal);
        closeBtn && closeBtn.addEventListener('click', closeModal);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });
    }

    // =====================================================
    // CAMPAIGN PROGRESS TRACKING
    // =====================================================
    function initProgressBars() {
        $$('.progress-bar__fill[data-progress]').forEach(bar => {
            const value = parseInt(bar.dataset.progress);
            // Animate on scroll into view
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            bar.style.width = Math.min(value, 100) + '%';
                        }, 200);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            bar.style.width = '0%';
            bar.style.transition = 'width 1s ease';
            observer.observe(bar);
        });
    }

    // =====================================================
    // INLINE STATUS EDITOR
    // =====================================================
    function initInlineStatusEditor() {
        $$('[data-inline-select]').forEach(wrapper => {
            const select    = wrapper.querySelector('select');
            const saveBtn   = wrapper.querySelector('[data-save]');
            const projectId = wrapper.dataset.projectId;

            if (!select || !saveBtn || !projectId) return;

            const originalValue = select.value;

            select.addEventListener('change', function () {
                saveBtn.style.display = this.value !== originalValue ? 'inline-flex' : 'none';
            });

            saveBtn.addEventListener('click', function () {
                const btn = this;
                btn.disabled = true;

                const formData = new FormData();
                formData.append('action',     'royita_update_project_status');
                formData.append('nonce',      typeof royitaDashboard !== 'undefined' ? royitaDashboard.nonce : '');
                formData.append('project_id', projectId);
                formData.append('status',     select.value);

                fetch(typeof royitaDashboard !== 'undefined' ? royitaDashboard.ajaxurl : '/wp-admin/admin-ajax.php', {
                    method: 'POST',
                    body: formData,
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.data.message, 'success');
                            saveBtn.style.display = 'none';
                        } else {
                            showToast(data.data?.message || 'خطایی رخ داد', 'error');
                            select.value = originalValue;
                        }
                    })
                    .catch(() => showToast('خطا در اتصال', 'error'))
                    .finally(() => { btn.disabled = false; });
            });
        });
    }

    // =====================================================
    // INIT ALL DASHBOARD FEATURES
    // =====================================================
    function init() {
        initSidebarNav();
        initNotificationBadge();
        initProjectStatusUpdate();
        initFileUpload();
        initProposalForm();
        initCharCounters();
        initConfirmations();
        initDashboardTabs();
        initAutoRefreshStats();
        initProposalModal();
        initProgressBars();
        initInlineStatusEditor();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
