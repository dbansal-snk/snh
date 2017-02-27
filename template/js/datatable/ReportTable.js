ReportTable = function() {
    var dataTableId;
    var _allFilterContainer = createDomEl("div",["allFiltersContainer"],null, null);
    var _filters = {};
    
    function init() {
        
//        var cols = getReportColumns(columns);
//        applyFilter(tableId, data);
//        if ('undefined' != typeof columns) {
//            loadTable(url, columns, data);
//        }
    }
    
    function loadTableData(tableId, url, data, columns) {
        dataTableId = $('#' + tableId);

        var cols = [];
        // get the list of report's columns    
        $.each(columns, function( key, value ) {
            cols.push({'data': value});
        });
        
        applyFilter(tableId, data);
        loadTable(url, cols, data);
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
            fnServerParams: function ( aoData ) {
                if ('undefined' != typeof data && '' != data) {
                    $.each(data, function (value, key) {
                        aoData.push({'name':value, 'value': key });
                    });
                }
                
                aoData.push({'name':"filterOptions", 'value': _filters });
            }
        });
    }
    
    
    function applyFilter(tableId, data) {
        buildFilters(data.report_config.content.columns);
        $( "#apply_filter" ).click(function() {
            get_filter_data();
            var table = $('#' + tableId).DataTable();
            table.ajax.reload();
        });
    }
    
    function buildFilters(columns) {
        var _customFilter = createDomEl("select",null,null, 'filter');

        // create columns filters drop down
        var optionList = {};
        $.each(columns, function (key, value) {
            if ('undefined' != typeof value.isFilter && true == value.isFilter) {
                optionList[key] = value.name;
            }
        });
                    
//        optionList['name'] = 'Medicine';
//        optionList['mrp'] = 'M.R.P';
//        optionList['total_stock'] = 'Total Stock';
//        optionList['remaining_stock'] = 'Remaining Stock';
//        optionList['revenue'] = 'Revenue';
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

    function getReportConfig(url) {
        var data = {};
        $.ajax({
            url: url,
            success: function(response){
                data.report_config = response;
                data.columns       = getReportColumns(response.content.columns);
                loadTableData('medicine_stock_report', 'index.php?pharmacist/medicine_stock_report_list', data, data.columns);
            }
        });

        return data;
    }
    
    function getReportColumns(data) {
        var columns = [];
        $.each(data, function (key, value) {
            columns.push(key);
        });
        
        return columns;
    }

    
    return {
        init: function() {
            
        }, 
        getReportConfig: function(url) {
            return getReportConfig(url);
        },
        loadTableData: function(tableId, url, data, columns) {
            loadTableData(tableId, url, data, columns);
        }
    }
}();