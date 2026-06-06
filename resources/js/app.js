import './bootstrap';
import '../css/app.css';
import './react.jsx';
import './vue.js';

// File Upload Preview
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-file-preview]').forEach(container => {
        const input = container.querySelector('input[type="file"]');
        const preview = container.querySelector('.file-preview');
        const removeBtn = container.querySelector('.file-remove');
        const nameDisplay = container.querySelector('.file-name');

        if (!input) return;

        const originalPreviewHTML = preview ? preview.innerHTML : '';

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) {
                if (preview) {
                    preview.innerHTML = '';
                    preview.insertAdjacentHTML('afterbegin', originalPreviewHTML);
                }
                if (nameDisplay) nameDisplay.textContent = '';
                return;
            }

            if (nameDisplay) {
                const size = file.size > 1024 * 1024
                    ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                    : (file.size / 1024).toFixed(1) + ' KB';
                nameDisplay.textContent = `${file.name} (${size})`;
            }

            if (preview && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (container.dataset.previewStyle === 'circle') {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-24 h-24 rounded-full object-cover ring-4 ring-indigo-100 shadow-md';
                        preview.replaceChildren(img);
                    } else if (container.dataset.previewStyle === 'avatar') {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-28 h-28 rounded-2xl object-cover ring-4 ring-indigo-100 shadow-lg shadow-indigo-200';
                        preview.replaceChildren(img);
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'max-h-48 rounded-xl shadow-md border border-gray-200';
                        preview.replaceChildren(img);
                    }
                };
                reader.readAsDataURL(file);
            } else if (preview && !file.type.startsWith('image/')) {
                const icons = {
                    'application/pdf': 'fa-file-pdf',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fa-file-excel',
                    'application/vnd.ms-excel': 'fa-file-excel',
                    'application/msword': 'fa-file-word',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word',
                };
                const icon = icons[file.type] || 'fa-file';
                const colors = {
                    'fa-file-pdf': 'text-red-500 bg-red-50',
                    'fa-file-excel': 'text-emerald-500 bg-emerald-50',
                    'fa-file-word': 'text-blue-500 bg-blue-50',
                };
                const color = colors[icon] || 'text-gray-500 bg-gray-50';
                const div = document.createElement('div');
                div.className = `flex items-center gap-3 p-4 rounded-xl ${color} border border-gray-200`;
                const i = document.createElement('i');
                i.className = `fa-solid ${icon} text-2xl`;
                const span = document.createElement('span');
                span.className = 'text-sm font-medium';
                span.textContent = file.name;
                div.append(i, span);
                preview.replaceChildren(div);
            }
        });

        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                input.value = '';
                if (preview) {
                    preview.innerHTML = '';
                    preview.insertAdjacentHTML('afterbegin', originalPreviewHTML);
                }
                if (nameDisplay) nameDisplay.textContent = '';
                input.classList.remove('border-red-300');
            });
        }
    });

    // Auto-dismiss alerts
    document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
        const delay = parseInt(el.dataset.autoDismiss) || 5000;
        setTimeout(() => {
            el.style.transition = 'all 0.5s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => el.remove(), 500);
        }, delay);
    });

    // Number formatting for currency inputs
    document.querySelectorAll('[data-format-currency]').forEach(input => {
        input.addEventListener('input', function() {
            let val = this.value.replace(/[^0-9]/g, '');
            if (val) {
                this.value = parseInt(val).toLocaleString('id-ID');
            }
        });

        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                input.value = input.value.replace(/\./g, '');
            });
        }
    });
});
