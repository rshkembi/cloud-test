define([
    'Magento_Ui/js/grid/columns/column'
], function (Column) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'Aheadworks_Blog/ui/listing/cells/name'
        },
        getName: function(row) {
            return row[this.index];
        },
        getUrl: function(row) {
            return row['name_url'];
        }
    });
});
