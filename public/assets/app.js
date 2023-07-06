const ajaxForm = (context) => {

    context.find('BUTTON').hide();

    let formData;
    let contentType;
    let processData;
    if (context.prop('enctype') === 'multipart/form-data') {
        formData = new FormData(context[0]);
        contentType = false;
        processData = false;
    } else {
        formData = context.serialize();
        contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        processData = false;
    }

    const successCallback = context.data('successcallback') ? eval(context.data('successcallback')) : false;

    const defaultSuccessCallback = (response) => {

        context.find('BUTTON').show();
        location.href = response.redirectUrl;
    }

    $.ajax({
        url: context.prop('action'),
        method: context.prop('method'),
        contentType: contentType,
        processData: processData,
        data: formData,
        success: successCallback ? (response) => successCallback(response, context) : defaultSuccessCallback,
        error: function (xhr, status, error) {

            context.find('BUTTON').show();

            const response = $.parseJSON(xhr.responseText);

            const errors = Object.keys(response.errors).map(function (k) {
                return response.errors[k]
            });
            const errorHtml = `
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                    ${errors.map(err => `${Object(err) === err ? err[0] : err}`).join('<br />')}
                </div>
                `;
            context.find('.form-error').html(errorHtml);
        }
    });
};

$('body').on('submit', '.ajaxForm', function (event) {
    event.preventDefault();
    ajaxForm($(this));
});
