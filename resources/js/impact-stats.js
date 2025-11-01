// resources/js/impact-stats.js
import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('stats-cards');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // تحديث أرقام الترتيب
    const updateBadges = () => {
        container.querySelectorAll('.sort-badge').forEach((b, i) => {
            b.textContent = `#${i + 1}`;
        });
    };

    // === تفعيل الأزرار على الكروت (يُستدعى بعد كل إضافة) ===
    function initCardButtons() {
        // فتح Modal التعديل
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.removeEventListener('click', handleEditClick);
            btn.addEventListener('click', handleEditClick);
        });

        // فتح Modal الحذف
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.removeEventListener('click', handleDeleteClick);
            btn.addEventListener('click', handleDeleteClick);
        });
    }

    // فتح Modal التعديل وتعبئة الحقول
    function handleEditClick(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const title = btn.dataset.title;
        const amount = btn.dataset.amount;
        const isActive = btn.dataset.active === '1';

        document.getElementById('editId').value = id;
        document.getElementById('editTitle').value = title;
        document.getElementById('editAmount').value = amount;
        document.getElementById('editActive').checked = isActive;
    }

    // فتح Modal الحذف
    function handleDeleteClick(e) {
        const btn = e.currentTarget;
        const id = btn.dataset.id;
        const title = btn.dataset.title;
        document.getElementById('deleteTitle').textContent = title;
        document.getElementById('deleteForm').action = `/admin/impact-stats/${id}`;
    }

    // === تفعيل الأزرار على الكروت الموجودة عند تحميل الصفحة ===
    initCardButtons();

    // === تفعيل/إيقاف ===
    document.addEventListener('click', async (e) => {
        if (e.target.closest('.toggle-btn')) {
            const btn = e.target.closest('.toggle-btn');
            const id = btn.dataset.id;
            const isActive = btn.dataset.active === '1';
            btn.classList.add('loading');
            btn.disabled = true;

            try {
                const res = await fetch(`/admin/impact-stats/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-HTTP-Method-Override': 'PATCH'
                    }
                });
                if (res.ok) {
                    btn.dataset.active = isActive ? '0' : '1';
                    btn.innerHTML = `<i class="fas fa-power-off"></i> ${isActive ? 'تفعيل' : 'إيقاف'}`;
                    btn.className = `toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 ${isActive ? 'btn-outline-secondary' : 'btn-teal'}`;

                    const badge = document.querySelector(`.status-badge[data-id="${id}"]`);
                    badge.className = `status-badge badge rounded-pill px-3 py-2 ${isActive ? 'bg-secondary' : 'bg-teal text-white'}`;
                    badge.innerHTML = `<i class="fas fa-circle small me-1"></i>${isActive ? 'معطل' : 'مفعل'}`;

                    showToast(isActive ? 'تم الإيقاف' : 'تم التفعيل');
                }
            } catch (e) {
                showToast('خطأ في الاتصال', 'error');
            } finally {
                btn.classList.remove('loading');
                btn.disabled = false;
            }
        }
    });

    // === إضافة جديدة ===
    document.getElementById('createForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = e.target.querySelector('.save-btn');
        const spinner = btn.querySelector('.spinner');
        btn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(e.target);
        try {
            const res = await fetch('/admin/impact-stats', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (res.ok) {
                const data = await res.json();
                addCard(data.item || data);
                bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
                e.target.reset();
                showToast('تمت الإضافة بنجاح');
            } else {
                const err = await res.json();
                showToast(err.message || 'حدث خطأ', 'error');
            }
        } catch (e) {
            showToast('فشل في الإضافة', 'error');
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // === تعديل ===
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('editId').value;
        const btn = e.target.querySelector('.save-btn');
        const spinner = btn.querySelector('.spinner');
        btn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(e.target);
        formData.append('_method', 'PATCH');

        try {
            const res = await fetch(`/admin/impact-stats/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (res.ok) {
                const data = await res.json();
                const item = data.item || data;
                updateCard(id, item);

                const toggleBtn = document.querySelector(`[data-id="${id}"] .toggle-btn`);
                const isActive = item.is_active;
                toggleBtn.dataset.active = isActive ? '1' : '0';
                toggleBtn.innerHTML = `<i class="fas fa-power-off"></i> ${isActive ? 'إيقاف' : 'تفعيل'}`;
                toggleBtn.className = `toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 ${isActive ? 'btn-teal' : 'btn-outline-secondary'}`;

                const badge = document.querySelector(`.status-badge[data-id="${id}"]`);
                badge.className = `status-badge badge rounded-pill px-3 py-2 ${isActive ? 'bg-teal text-white' : 'bg-secondary'}`;
                badge.innerHTML = `<i class="fas fa-circle small me-1"></i>${isActive ? 'مفعل' : 'معطل'}`;

                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                showToast('تم التحديث بنجاح');
            } else {
                const err = await res.json();
                showToast(err.message || 'حدث خطأ', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('فشل في الاتصال', 'error');
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    // === حذف ===
    document.querySelectorAll('.delete-confirm').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const form = btn.closest('form');
            const id = form.action.split('/').pop();
            const spinner = btn.querySelector('.spinner');
            btn.disabled = true;
            spinner.classList.remove('d-none');

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                });

                let data = {};
                if (res.headers.get('content-type')?.includes('application/json')) {
                    data = await res.json();
                }

                if (res.ok) {
                    document.querySelector(`[data-id="${id}"]`)?.remove();
                    updateBadges();
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    showToast(data.message || 'تم الحذف بنجاح');
                } else {
                    showToast(data.message || 'حدث خطأ', 'error');
                }
            } catch (e) {
                console.error(e);
                showToast('فشل في الاتصال', 'error');
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    });

    // === إضافة كرت جديد ===
    window.addCard = function(item) {
        const col = document.createElement('div');
        col.className = 'col';
        col.dataset.id = item.id;
        col.dataset.order = item.sort_order;
        col.innerHTML = `
            <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift position-relative drag-handle">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-1 fw-semibold sort-badge">#${item.sort_order}</span>
                        <span class="status-badge badge rounded-pill px-3 py-2 ${item.is_active ? 'bg-teal text-white' : 'bg-secondary'}" data-id="${item.id}">
                            <i class="fas fa-circle small me-1"></i>${item.is_active ? 'مفعل' : 'معطل'}
                        </span>
                    </div>
                    <h5 class="card-title mb-2 fw-bold text-dark">${item.title_ar}</h5>
                    <p class="display-6 fw-bold text-danger mb-3">$${Number(item.amount_usd).toLocaleString('en-US', { minimumFractionDigits: 1 })}</p>
                    <div class="d-flex gap-2">
                        <button class="toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 ${item.is_active ? 'btn-teal' : 'btn-outline-secondary'}"
                                data-id="${item.id}" data-active="${item.is_active ? '1' : '0'}">
                            <i class="fas fa-power-off"></i> ${item.is_active ? 'إيقاف' : 'تفعيل'}
                        </button>
                        <button class="btn btn-sm btn-warning flex-fill edit-btn d-flex align-items-center justify-content-center gap-1"
                                data-id="${item.id}"
                                data-title="${item.title_ar}"
                                data-amount="${item.amount_usd}"
                                data-active="${item.is_active ? '1' : '0'}"
                                data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit"></i> تعديل
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center"
                                data-id="${item.id}"
                                data-title="${item.title_ar}"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="drag-handle-icon position-absolute top-0 end-0 p-3 text-muted opacity-50">
                    <i class="fas fa-grip-lines fa-lg"></i>
                </div>
            </div>
        `;
        container.appendChild(col);
        updateBadges();

        // تفعيل الأزرار على الكرت الجديد
        initCardButtons();
    };

    // === تحديث كرت ===
    window.updateCard = function(id, data) {
        const card = document.querySelector(`[data-id="${id}"]`);
        card.querySelector('.card-title').textContent = data.title_ar;
        card.querySelector('.display-6').textContent = `$${Number(data.amount_usd).toLocaleString('en-US', { minimumFractionDigits: 1 })}`;
        const badge = card.querySelector('.status-badge');
        const btn = card.querySelector('.toggle-btn');
        const active = data.is_active;
        badge.className = `status-badge badge rounded-pill px-3 py-2 ${active ? 'bg-teal text-white' : 'bg-secondary'}`;
        badge.innerHTML = `<i class="fas fa-circle small me-1"></i>${active ? 'مفعل' : 'معطل'}`;
        btn.dataset.active = active ? '1' : '0';
        btn.innerHTML = `<i class="fas fa-power-off"></i> ${active ? 'إيقاف' : 'تفعيل'}`;
        btn.className = `toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 ${active ? 'btn-teal' : 'btn-outline-secondary'}`;
    };

    // === السحب والإفلات ===
    new Sortable(container, {
        animation: 200,
        ghostClass: 'bg-light',
        handle: '.drag-handle, .drag-handle-icon',
        onEnd: () => {
            const order = {};
            [...container.children].forEach((el, i) => order[el.dataset.id] = i + 1);
            fetch('/admin/impact-stats/reorder', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ order })
            }).then(r => r.ok && updateBadges());
        }
    });

    // === Toast ===
    window.showToast = function(msg, type = 'success') {
        document.querySelectorAll('.custom-toast').forEach(t => t.remove());
        const toast = document.createElement('div');
        toast.className = `custom-toast alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed top-0 end-0 m-4 shadow-lg rounded-pill fade show d-flex align-items-center`;
        toast.style.cssText = `min-width:320px;z-index:1070;animation:slideInRight .4s ease,fadeOut .5s 3.5s forwards;backdrop-filter:blur(8px);`;
        toast.innerHTML = `<i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-2"></i><span>${msg}</span><button type="button" class="btn-close btn-close-sm ms-auto" onclick="this.parentElement.remove()"></button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    };

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { to { opacity: 0; transform: translateX(50px); } }
    `;
    document.head.appendChild(style);
});
