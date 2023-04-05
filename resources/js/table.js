var table = {
    tableRef: null,
    keyInput: $("#apikey"),


    init: function( selector ) {
        table.tableRef = $(selector).DataTable({
            "columnDefs": [
                { "targets": [1,2,3,4,5], "searchable": false },
                { targets: -1, data: null, defaultContent: '<a class ="edit-btn btn btn-success">Edit</button><a class ="delete-btn btn btn-danger">Delete</button>'},
            ],
            processing: true,
            serverSide: true,
            ajax: '/subscribers',
        });

        table.addListeners(selector);
    },

    addListeners: function(selector) {
        // Editing a record
        $(selector + ' tbody').on('click', '.edit-btn', function () {
            var data = table.tableRef.row($(this).parents('tr')).data();
            console.log(data);
        });

        // Deleting a record
        $(selector + ' tbody').on('click', '.delete-btn', function () {
            var data = table.tableRef.row($(this).parents('tr')).data();
            console.log(data);
        });
    }
 
};
 
$.when( $.ready ).then(function() {
    table.init('#subscriber_table')
});