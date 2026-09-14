document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search');
    const box = document.getElementById('suggest-box');
    if (!input || !box) return;

    let timer = null;
    input.addEventListener('input', function () {
        const q = input.value.trim();
        clearTimeout(timer);
        if (!q) { box.innerHTML = ''; return; }
        timer = setTimeout(function () {
            fetch('ajax_search.php?q=' + encodeURIComponent(q))
                .then(function (res) { return res.json(); })
                .then(function (rows) {
                    if (!rows.length) {
                        box.innerHTML = '<div class="suggest-item">No matches</div>';
                        return;
                    }
                    box.innerHTML = rows.map(function (r) {
                        return '<a class="suggest-item" href="edit_product.php?id=' + r.id + '">' +
                            r.product_name + ' <small>' + r.category + '</small></a>';
                    }).join('');
                });
        }, 200);
    });
});