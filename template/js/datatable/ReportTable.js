ReportTable = function() {
    var dataTableId;
    var _allFilterContainer = createDomEl("div",["allFiltersContainer"],null, null);
    var _filters = {};
    var _columnDefs = [];
    var _tableObj;
    var _columnOrder = [];
    
    function init() {

    }
    
    function loadTableData(tableId, url, data, columns, additionalData) {
        dataTableId = $('#' + tableId);

        var cols = [];
        // get the list of report's columns    
        $.each(columns, function( key, value ) {
            cols.push({'data': value});
        });
        
        applyFilter(tableId, data);
        loadTable(url, cols, data,additionalData);
    }
    
  
    
    function loadTable(url, cols, data, additionalData) {
        dataTableId.DataTable().destroy();
        _tableObj = dataTableId.DataTable( {
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
            // this function is to be called before firing the query to fetch the data for the reports
            fnServerParams: function ( aoData ) {
                if ('undefined' != typeof data && '' != data) {
                    $.each(data, function (value, key) {
                        aoData.push({'name':value, 'value': key });
                    });
                }
                
                aoData.push({'name':"filterOptions", 'value': _filters });
                if ('undefined' != typeof additionalData && '' != additionalData) {
                    $.each(additionalData, function (key, value) {
                        aoData.push({'name':key, 'value':value}); 
                    });
                }
            },
            columnDefs: _columnDefs,
            order: _columnOrder,
            fnDrawCallback: function() {
                $('.loader').hide();
            }
        });
    }
    
    
    function applyFilter(tableId, data) {
        buildFilters(data.report_config.content.columns);
        $( "#apply_filter" ).click(function() {
            $('.loader').show();
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

    function getReportConfig(configUrl, listUrl, tableId, additionalData) {
        $('.loader').show();
        var data = {};
        $.ajax({
            url: configUrl,
            success: function(response){
                data.report_config = response;
                data.columns       = getReportColumns(response.content.columns);
                loadTableData(tableId, listUrl, data, data.columns, additionalData);
            }
        });
        
        return data;
    }
    
    function getReportColumns(configData) {
        var columns     = [];
        var columnCount = 0;
        $.each(configData, function (key, value) {
            // hide the column in the data table if it is not required
            if ('undefined' != typeof value.isColumn && false == value.isColumn) {
                _columnDefs.push({
                    'targets' : columnCount, 
                    'visible' : false
                });
                
                
            } else if ('undefined' != typeof value.isLink && true == value.isLink) {
                // create the hyperlink in the datatable
                _columnDefs.push({
                    'targets' : columnCount, 
                    'visible' : true,
                    'render': function ( data, type, row, meta ) {
                        if(type === 'display'){
                            var id = value.additionDetails;
                            data = '<a href="' + value.linkUrl + encodeURIComponent(row[id]) + '">' + data + '</a>';
                            data += '<span class""></span>';
                        }
                        return data;
                    }
                });
            }
            
            // sort the column by default
            if ('undefined' != typeof value.defaultSort && true == value.defaultSort) {
                _columnOrder.push([columnCount, value.defaultSortOrder]);   
            }

            columns.push(key);
            columnCount++;
        });
        
        return columns;
    }
    
    function reDrawDataTable() {
        _tableObj.draw();
    }

    
    return {
        init: function() {
            
        },
        getReportConfig: function(configUrl, listUrl, tableId, additionalData) {
            return getReportConfig(configUrl, listUrl, tableId, additionalData);
        },
        loadTableData: function(tableId, url, data, columns) {
            loadTableData(tableId, url, data, columns);
        },
        getTableObj: function() {
            return _tableObj;
        },
        drawTable: function() {
            reDrawDataTable();
        }
    }
}();




TableRowMenu = function() {
	var _menuDiv =$('<div class="tableRowMenu">');
	var timer;
	var mouseOn = false;
	function positionMenu(domEl) {

	    var width = $(domEl).outerWidth();
	    var height =  $(domEl).outerHeight();
		var pos = $(domEl).position();
		pos.top = pos.top;
		pos.left = pos.left + (width/2);
	    var windowWidth = $( window ).width();
	    if (pos.left > windowWidth -60) {
	        pos.left = pos.left - width*4 ;
	    }
	    $(_menuDiv).css({
	        position:"absolute",
	    	minWidth:   "200px",
	        top: pos.top + "px",
	       left: pos.left  + "px"
	    });

	}
	function showMenu(htmlStr) {
		$(_menuDiv).children().remove();
		$(_menuDiv).append(htmlStr);
		$(_menuDiv).show();
		timer = setTimeout(function(){
				if(!mouseOn) {
					hideMenu() ;
				}
			},5000);
		$(_menuDiv).mouseenter(function(){
			clearTimeout(timer);
		});
		$(_menuDiv).mouseleave(function(){
			hideMenu();
		})
	}

	function hideMenu() {
		_menuDiv.hide();
	}

	return {
		showMenu:function(domEl,htmlStr) {
			positionMenu(domEl);
			showMenu(htmlStr);
		},
		hideMenu:function() {
			hideMenu();
		},
		getElement:function() {
			return _menuDiv;
		}


	}
}();