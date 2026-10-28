(function () {
	'use strict';

	var nav = document.getElementById('primary-nav');
	var navBtn = document.querySelector('[data-nav-toggle]');
	var searchBtn = document.querySelector('[data-search-toggle]');
	var searchPanel = document.querySelector('[data-search-panel]');

	if (navBtn && nav) {
		navBtn.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			navBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	if (searchBtn && searchPanel) {
		searchBtn.addEventListener('click', function () {
			var open = searchPanel.hasAttribute('hidden');
			if (open) {
				searchPanel.removeAttribute('hidden');
				var input = searchPanel.querySelector('input[type="search"]');
				if (input) { input.focus(); }
			} else {
				searchPanel.setAttribute('hidden', '');
			}
			searchBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !searchPanel.hasAttribute('hidden')) {
				searchPanel.setAttribute('hidden', '');
				searchBtn.setAttribute('aria-expanded', 'false');
				searchBtn.focus();
			}
		});
	}

	// FAQ accordion
	document.querySelectorAll('.faq__q').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var item = btn.closest('.faq__item');
			var open = !item.classList.contains('is-open');
			item.classList.toggle('is-open', open);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
})();
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.field select').forEach(function (select) {
		if (select.dataset.cs) return;
		select.dataset.cs = '1';

		var field = select.closest('.field');
		var wrap = document.createElement('div');
		wrap.className = 'cs';

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'cs__btn';

		var list = document.createElement('div');
		list.className = 'cs__list';
		list.setAttribute('role', 'listbox');

		var placeholder = select.options.length ? select.options[0].text : '';

		Array.prototype.forEach.call(select.options, function (opt) {
			if (opt.value === '') return; // السطر الأول (العنوان) مش بيظهر في القايمة
			var item = document.createElement('div');
			item.className = 'cs__item';
			item.setAttribute('role', 'option');
			item.dataset.value = opt.value;
			item.innerHTML = '<span></span><i class="cs__radio"></i>';
			item.firstChild.textContent = opt.text;
			item.addEventListener('click', function () {
				select.value = opt.value;
				select.dispatchEvent(new Event('change', { bubbles: true }));
				sync();
				close();
			});
			list.appendChild(item);
		});

		function sync() {
			var current = select.value;
			var selected = select.options[select.selectedIndex];
			btn.textContent = current ? selected.text : placeholder;
			btn.classList.toggle('is-placeholder', !current);
			list.querySelectorAll('.cs__item').forEach(function (it) {
				it.classList.toggle('is-selected', it.dataset.value === current);
			});
		}
		function open()  { wrap.classList.add('is-open');    field.classList.add('is-open'); }
		function close() { wrap.classList.remove('is-open'); field.classList.remove('is-open'); }

		btn.addEventListener('click', function () {
			wrap.classList.contains('is-open') ? close() : open();
		});
		document.addEventListener('click', function (e) { if (!wrap.contains(e.target)) close(); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });

		// بعد إرسال الفورم أو عمل reset يرجع للعنوان
		var form = select.closest('form');
		if (form) form.addEventListener('reset', function () { setTimeout(sync, 0); });
		document.addEventListener('wpcf7mailsent', function () { setTimeout(sync, 0); });

		wrap.appendChild(btn);
		wrap.appendChild(list);
		select.insertAdjacentElement('afterend', wrap);
		sync();
	});
});