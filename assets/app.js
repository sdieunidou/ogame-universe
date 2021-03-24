import './styles/app.scss';

const $ = require('jquery');

require('bootstrap');
require('datatables.net-responsive-bs4');
require('datatables.net-select-bs4');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip()

    const table = $('table.table-data').DataTable({
        paging: false,
        order: [],
    });

    function format (selector) {
        return $('#' + selector).html();
    }

    $('table.table-data tbody').on('click', 'td.details-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row( tr );

        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child( format($(this).data('full')) ).show();
            tr.addClass('shown');
        }
    });
});