(function () {
    var body = document.body;
    var content = document.getElementById('content');
    var cover = document.getElementById('cover');
    var bgm = document.getElementById('bgm');
    var musicBtn = document.getElementById('music-btn');

    document.getElementById('open-btn').addEventListener('click', function () {
        content.hidden = false;
        cover.classList.add('hide');
        body.classList.remove('locked');
        window.scrollTo(0, 0);
        if (bgm) {
            musicBtn.hidden = false;
            bgm.play().then(function () { musicBtn.classList.add('on'); }).catch(function () {});
        }
        observe();
        if (location.hash) {
            var t = document.querySelector(location.hash);
            if (t) t.scrollIntoView();
        }
    });

    if (musicBtn) {
        musicBtn.addEventListener('click', function () {
            if (bgm.paused) { bgm.play(); musicBtn.classList.add('on'); }
            else { bgm.pause(); musicBtn.classList.remove('on'); }
        });
    }

    // Setelah submit RSVP, langsung buka isi undangan.
    if (location.hash === '#rsvp' && document.querySelector('.flash')) {
        document.getElementById('open-btn').click();
    }

    // Hitung mundur
    var cd = document.getElementById('countdown');
    if (cd) {
        var target = new Date(cd.dataset.target).getTime();
        var el = function (u) { return cd.querySelector('[data-u=' + u + ']'); };
        var tick = function () {
            var diff = Math.max(0, target - Date.now());
            el('d').textContent = Math.floor(diff / 864e5);
            el('h').textContent = Math.floor(diff % 864e5 / 36e5);
            el('m').textContent = Math.floor(diff % 36e5 / 6e4);
            el('s').textContent = Math.floor(diff % 6e4 / 1e3);
        };
        tick();
        setInterval(tick, 1000);
    }

    // Animasi muncul saat scroll
    function observe() {
        var items = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            items.forEach(function (i) { i.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (es) {
            es.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
            });
        }, { threshold: 0.15 });
        items.forEach(function (i) { io.observe(i); });
    }

    // Salin nomor rekening
    document.querySelectorAll('.copy').forEach(function (b) {
        b.addEventListener('click', function () {
            var v = b.dataset.copy;
            var done = function () { var o = b.textContent; b.textContent = 'Tersalin ✓'; setTimeout(function () { b.textContent = o; }, 1500); };
            if (navigator.clipboard) navigator.clipboard.writeText(v).then(done);
            else { var t = document.createElement('textarea'); t.value = v; document.body.appendChild(t); t.select(); document.execCommand('copy'); t.remove(); done(); }
        });
    });
})();
