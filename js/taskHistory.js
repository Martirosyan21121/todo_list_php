$(document).ready(function () {
    $('.modal-btn').click(function () {
        let itemId = $(this).val();
        let modalId = '#modal-' + itemId;
        $(modalId).css('display', 'block');
    });

    $('.close').click(function () {
        $(this).closest('.modal').css('display', 'none');
    });

    $(window).click(function (event) {
        if ($(event.target).hasClass('modal')) {
            $(event.target).css('display', 'none');
        }
    });
});
