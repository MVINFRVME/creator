/**
 * Ссылки на код (GitHub) и скринкасты.
 */
window.PROJECT_LINKS = {
  githubBase: 'https://github.com/MVINFRVME/creator/blob/main',

  // путь от корня репо → кнопка «Код»
  code: {
    '1.1': '01-php/task1-1.php',
    '1.2': '01-php/task1-2.php',
    '1.3': '01-php/task1-3.php',
    '1.4': '01-php/task1-4.php',
    '1.5': '01-php/task1-5.php',
    '2.1': '02-frontend/task2-1.html',
    '2.2': '02-frontend/task2-2.html',
    '2.3': '02-frontend/task2-3.html',
    '2.4': '02-frontend/task2-4.html',
    '3.1': '03-bitrix/task3-1-multisite-guide.md',
    '3.2': '03-bitrix/task3-2-bonus/',
    '3.3': '03-bitrix/task3-3-faq/',
    '3.4': '03-bitrix/task3-4-component/',
    '3.5': '03-bitrix/task3-5-property/',
  },

  video: {
    '3.1': 'https://disk.yandex.ru/i/kJU1-ibNPcon3g',
    '3.2': 'https://disk.yandex.ru/i/NnQW2r_sr7IGmg',
    '3.3': 'https://disk.yandex.ru/i/nMpvNGN_YSCeXA',
    '3.4': 'https://disk.yandex.ru/i/FRRjyPLNrFsp1w',
    '3.5': 'https://disk.yandex.ru/i/0yPiXrdyEyavyQ',
    '4.1': 'https://disk.yandex.ru/i/xc-DiS1McG-Bmg',
    '4.2': 'https://disk.yandex.ru/i/y-oxhfkaqyT5GA',
    '4.3': 'https://disk.yandex.ru/i/BEWppKSLfWNJPA',
  },
};

(function () {
  function codeUrl(id) {
    var path = window.PROJECT_LINKS.code[id];
    if (!path) return null;
    return window.PROJECT_LINKS.githubBase.replace(/\/$/, '') + '/' + path.replace(/^\//, '');
  }

  function videoUrl(id) {
    return window.PROJECT_LINKS.video[id] || null;
  }

  function makeBtn(href, label, cls) {
    var a = document.createElement('a');
    a.href = href;
    a.className = cls;
    a.textContent = label;
    a.target = '_blank';
    a.rel = 'noopener noreferrer';
    if (href.charAt(0) === '#') {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        alert('Ссылка-заглушка. После пуша/загрузки видео обнови links.js');
      });
    }
    return a;
  }

  document.querySelectorAll('[data-task]').forEach(function (el) {
    var id = el.getAttribute('data-task');
    var wantCode = el.hasAttribute('data-code');
    var wantVideo = el.hasAttribute('data-video');
    var wrap = document.createElement('span');
    wrap.className = 'task-actions';

    if (wantCode) {
      var c = codeUrl(id);
      if (c) wrap.appendChild(makeBtn(c, 'Код на GitHub', 'task-link task-link-code'));
    }
    if (wantVideo) {
      var v = videoUrl(id);
      if (v) wrap.appendChild(makeBtn(v, 'Скринкаст', 'task-link task-link-video'));
    }
    el.appendChild(wrap);
  });
})();
