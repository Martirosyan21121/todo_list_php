$(document).ready(function () {
    $('.cart').on('change', 'select[name="status"]', function () {
        let status = $(this).val();
        let itemId = $(this).closest('.cart-item').find('input[name="itemId"]').val();
        console.log(itemId, 'Item ID');

        $.ajax({
            type: 'POST',
            url: '../todo/add_task.php',
            data: {status: status, itemId: itemId},
            success: function (response) {
                console.log(response);
                location.reload()
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });

});
