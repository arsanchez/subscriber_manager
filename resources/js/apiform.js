var apiForm = {
    init: function( ) {
        apiForm.addListeners();
    },
 
    addListeners: function() {
        $('#addKey').on('click', function() {
            let isValid = apiForm.validate();
            console.log(isValid);
        });
    },
 
    validate: function() {
        return false;
    },

    submitForm: function() {

    }
 
};
 
$.when( $.ready ).then(function() {
    apiForm.init()
});