import './styles/app.scss';

const $ = require('jquery');

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('datatables.net-responsive-bs4');
require('datatables.net-select-bs4');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip()

    $('table.table-data').DataTable({
        paging: false,
        order: [],
    });
});