$(function () {
    $('#product-form').on('submit', function (e) {
        const name = $.trim($('#product_name').val());
        const category = $.trim($('#category').val());
        const price = $('#price').val();
        const qty = $('#quantity').val();
        let msg = '';
        if (!name || !category || !price || !qty) {
            msg = 'Please fill product name, category, price and quantity.';
        } else if (isNaN(price) || Number(price) < 0) {
            msg = 'Price must be a number 0 or more.';
        }
        if (msg) {
            e.preventDefault();
            $('#form-error').text(msg).prop('hidden', false);
        }
    });
});