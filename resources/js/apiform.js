var apiForm = {
    keyInput: $("#apikey"),

    init: function( ) {
        apiForm.addListeners();
    },
 
    addListeners: function() {
        $('#addKey').on('click', function() {
            let isValid = apiForm.validate();
            if (isValid) {
                apiForm.submitForm();
            }
        });
    },
 
    validate: function() {
        return apiForm.keyInput.val() != '';
    },

    submitForm: function() {
        apiForm.showLoading();
        $.ajax({
            url: "/save",
            dataType: 'json',
            type: 'post',
            contentType: 'application/json',
            data: JSON.stringify({'key': apiForm.keyInput.val()})

        }).done(function(response) {
            apiForm.hideLoading();
            Swal.fire(
                'API Key saved',
                '',
                'success'
            ).then(function() {
                location.reload();
            });
        }) .fail(function(xhr, status, error) {
            apiForm.hideLoading();
            let response = JSON.parse(xhr.responseText);
            Swal.fire(
                'Invalid data',
                response.errors,
                'error'
            );
        });
    },

    showLoading: function() {
        $('#api_form .loading').removeClass('d-none');
        Swal.showLoading();
    },

    hideLoading: function() {
        $('#api_form .loading').addClass('d-none');
        Swal.close();
    },
};
 
$.when( $.ready ).then(function() {
    apiForm.init()
});