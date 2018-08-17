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


function mailingType() {
    type = $('#mailing-type').val();

    $('.field-mailing-emails_array').hide();
    $('.field-mailing-list_id').hide();
    $('.mailing-linked-models').hide();

    if (type == '0')
        $('.field-mailing-emails_array').show();
    if (type == '1') {
        $('.mailing-linked-models').show();
    }
    if (type == '2') {
        $('.field-mailing-list_id').show();
    }
}

$(document).on('change', '#mailing-type', function () {
    mailingType();
})