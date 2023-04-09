var subscribersForm = {
    name: $('#subscriber-name'),
    email: $('#subscriber-email'),
    country: $('#subscriber-country'),

    init: function( ) {
        subscribersForm.setUpValidation();
        subscribersForm.addListeners();
        $('form').validate();
    },

    setUpValidation: function() {
        $.validator.setDefaults({
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                  $(element).addClass("is-valid").removeClass("is-invalid");
            },
        
            errorElement: 'span',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                if(element.parent('.form-control').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    },
 
    addListeners: function() {
        $('#add-sub-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#add-sub-form').valid()) {
                let data = JSON.stringify({
                    name: subscribersForm.name.val(),
                    email: subscribersForm.email.val(),
                    country: subscribersForm.country.val(),
                })
                subscribersForm.submitForm("/subscribers", data, 'post');
            }
        });

        $('#edit-sub-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#edit-sub-form').valid()) {
                let subId = $('#sub_id').val();
                let data = JSON.stringify({
                    name: subscribersForm.name.val(),
                    country: subscribersForm.country.val(),
                })
                subscribersForm.submitForm("/subscribers/" + subId, data, 'put');
            }
        });
    },

    submitForm: function(endpoint, data, method) {
        let successMessage =  method == 'put' ? 'Subscriber updated' : 'Subscriber created';
          
        subscribersForm.showLoading();
        $.ajax({
            url: endpoint,
            dataType: 'json',
            type: method,
            contentType: 'application/json',
            data: data
        }).done(function(response) {
            subscribersForm.hideLoading();
            Swal.fire(
                successMessage,
                '',
                'success'
            ).then(function() {
                window.location.href = '/'
            });
        }) .fail(function(xhr, status, error) {
            subscribersForm.hideLoading();
            let response = JSON.parse(xhr.responseText);
            Swal.fire(
                'Invalid data',
                response.errors,
                'error'
            );
        });
    },

    showLoading: function() {
        Swal.showLoading();
    },

    hideLoading: function() {
        Swal.close();
    },
};
 
$.when( $.ready ).then(function() {
    subscribersForm.init()
});