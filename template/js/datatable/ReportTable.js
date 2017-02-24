ReportTable = function() {
    var dataTableId;
    var _allFilterContainer = createDomEl("div",["allFiltersContainer"],null, null);
    var _filters = {};
    
    function init(tableId, url, data, columns) {
        dataTableId = $('#' + tableId);;
        var cols = getReportColumns(columns);
        applyFilter(tableId, data);
        loadTable(url, cols, data);
    }
    
    function getReportColumns(columns) {
        var cols = [];
        $.each( columns, function( key, value ) {
            cols.push({'data': value});
        });
        
        return cols;
    }
    
    function loadTable(url, cols, data) {
        dataTableId.DataTable().destroy();
        dataTableId.DataTable( {
            bProcessing : true,
            bServerSide: true,
            bLengthChange: false,
            serverSide: true,
            ordering: true,
            pageLength: 50,
            searching: false,
            scrollY:        '600px',
            scrollCollapse: true,
            paging:         true,
            bJQueryUI: true,
            sServerMethod: 'POST',
            sAjaxSource: url,
            columns: cols,
            scroller: {
                loadingIndicator: true
            },
            "fnServerParams": function ( aoData ) {
                if ('undefined' != typeof data) {
                     $.each(data, function (value, key) {
                         aoData.push({'name':value, 'value': key });
                    });
                }
                
                aoData.push({'name':"filterOptions", 'value': _filters });
            }
        });
    }
    
    
    function applyFilter(tableId) {
        buildFilters();
        $( "#apply_filter" ).click(function() {
            get_filter_data();
            var table = $('#' + tableId).DataTable();
            table.ajax.reload();
        });
    }
    
    function buildFilters() {
        var _customFilter = createDomEl("select",null,null, 'filter');

        // create columns filters drop down
        var optionList = {};
        optionList['name'] = 'Medicine';
        optionList['mrp'] = 'M.R.P';
        optionList['total_stock'] = 'Total Stock';
        optionList['remaining_stock'] = 'Remaining Stock';
        optionList['revenue'] = 'Revenue';
        $.each(optionList, function (value, key) {
            var option = createDomEl("option");
            option.text = key;
            option.value = value;
            _customFilter.appendChild(option);
        });
        
        // create operators drop doen
        var _operatorFilter = createDomEl("select",null,null, 'filter_operator');
        var _operatorList            = {};
        _operatorList['CONTAINS']    = 'Contains';
        _operatorList['NOTCONTAINS'] = 'Does not contain';
        
        $.each(_operatorList, function (value, key) {
            var option = createDomEl("option");
            option.text = key;
            option.value = value;
            _operatorFilter.appendChild(option);
        });
        
        
        var filterValue = createDomEl("input",null,null, 'filter_value');
        
        _allFilterContainer.appendChild(_customFilter);
        _allFilterContainer.appendChild(_operatorFilter);
        _allFilterContainer.appendChild(filterValue);
        
        $("#filter_containers").append(_allFilterContainer);
    
    }
    
    function get_filter_data() {
        _filters          = {};
        var dataObj       = {};
        dataObj.fieldName = $('#filter').val();
        dataObj.operator  = $('#filter_operator').val();
        dataObj.value     = $('#filter_value').val();
        dataObj.type      = 'text';
        _filters          = {'filters' : dataObj};
    }
    
    function createDomEl(domType,classes,textChild, id) {
        var domEl = document.createElement(domType);
        if(classes != null) {
            for (var x in classes) {
                $(domEl).addClass(classes[x]);
            }
        }
        if(textChild != null) {
            domEl.appendChild(document.createTextNode(textChild));
        }
        if (typeof id !== 'undefined' && id != null) {
            domEl.id = id;
        }
        return domEl;
}
    
    return {
        init: function(tableId, url, data, columns) {
            init(tableId, url, data, columns);
        }
    }
}();