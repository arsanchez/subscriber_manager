var table = {
    tableRef: null,
    keyInput: $("#apikey"),


    init: function( selector ) {
        table.tableRef = $(selector).DataTable({
            "columnDefs": [
                { targets: -1, data: null, defaultContent: '<a class ="delete-btn btn btn-danger">Delete</button>'},
                {
                    targets:0,
                    render: function ( data, type, row, meta ) {
                        if(type === 'display') {
                            data = '<a href="/subscribers/' + row[5] +'/edit">' +  data + '</a>';
                        }
                        return data;
                    }
                }
            ],
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                "url": '/subscribers',
                data: function (d) {
                    d.email_search = $("#subscriber-email").val();
                }
              },
        });

        table.addListeners(selector);
    },

    addListeners: function(selector) {
        // Deleting a record
        $(selector + ' tbody').on('click', '.delete-btn', function () {
            var data = table.tableRef.row($(this).parents('tr')).data();
            let id = data[5];
            
            $.ajax({
                url: "/subscribers/" + id ,
                type: 'DELETE',
                success: function() {
                    table.tableRef.ajax.reload();
                }
            });
        });

        $('#subscriber-email').on("input", function() {
            let email = $(this).val();
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) || email == '' ) {
                table.tableRef.ajax.reload();
            }
        });
    }
 
};
 
$.when( $.ready ).then(function() {
    table.init('#subscriber_table');
});