var routeMailingSend = '';

$(document).on('change', 'form.table-mailing-autosubmit', function () {
    submitForm($(this));
})

$(document).on('keyup', 'form.table-mailing-autosubmit', function () {
    submitForm($(this));
})

function submitForm(form) {
    method = form.attr('method');
    action = form.attr('action');
    container = form.data('container');
    $.pjax.reload({
        url: action,
        method: method,
        container: container,
        data: form.serialize()
    })

}

function sendMailing(id) {
    if (!confirm('Вы уверены, что хотите отправить рассылку?'))
        return false;

    $.ajax({
        url: routeMailingSend,
        data: {id: id},
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#items'});
        },
        error: function (response) {
            processError(response);
        }
    })
}