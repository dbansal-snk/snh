medicine = function() {

     
    function calculate_med_price()
    {
        var totalSellingMedCount = $("div[id*='selling_med_detais']").length;
        var totalAmount          = 0;
        for (var i = 1; i <= totalSellingMedCount; i++) {
            var medicine_id = $("#medicine_id" + i).val();
            if (medicine_id != '' && $.isNumeric(medicine_id) && medicine_id > 0) {
                var mrp = medicine_price_details[medicine_id];
                // if the loose item is being sold
                if ($('#selling_loose_quantity' + i).is(":checked")) {
                    mrp = loose_item_mrp[medicine_id];
                }

                var quantity = $('#med_quantity' + i).val();
                var amount   = quantity * mrp;
                
                if ($.isNumeric(amount) == true) {
                    $('#med_amount' + i).val(amount);
                    totalAmount = totalAmount + amount;
                } else {
                    $('#med_amount' + i).val(0);
                }
            }
        }
        
        
        if ($.isNumeric(totalAmount) == true) {
            $('#total_amount').val(totalAmount);
        } else {
            $('#total_amount').val(0);
        }
    }
    
    function get_loose_item_derails()
    {
        if ($('#is_loose_item').is(":checked")) {
            $('#loose_item_details').show();
        } else {
            $('#loose_item_details').hide();
        }
        
    }
    
    function add_medicine_container()
    {
        var labelCount = $("div[id*='selling_med_detais']").length + 1;
        // MEDICINE LIST ELEMENT
        var mouterDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var mlabel = $('<label>').attr({
            class: 'control-label',
            id: 'selling_med_detais'
        }).appendTo(mouterDiv);
        
        mlabel.html('Medicine' + labelCount);
        
        var minnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(mouterDiv);
        
        $('<select>').attr({
            id: 'medicine_id' + labelCount,
            name: 'medicine_id' + labelCount,
            onkeyup: 'medicine.calculate_med_price();'
        }).appendTo(minnerDiv);
        
        mouterDiv.appendTo('#med_details_container');
        
        $.each (medicineList, function( key, value ) {
            if ('undefined' != typeof value && '' != value) {
                $('<option>').val(key).text(value).appendTo('#medicine_id' + labelCount);
            }
        });
        
        // OPTION TO REMOVE DETAILS
        $('<div>').attr({
            id: labelCount,
            class: 'removeMedicine',
            onclick: 'medicine.remove_medicine_container(' + labelCount +');'
        }).appendTo(minnerDiv);
        
        
        // LOOSE QUANTITY ELEMENT
        var louterDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var llabel = $('<label>').attr({
            class: 'control-label'            
        }).appendTo(louterDiv);
        
        llabel.html('Selling Loose Quantity' + labelCount);
        
        var linnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(louterDiv);

        louterDiv.appendTo('#med_details_container');
        
        $('<input>').attr({
            type: 'checkbox',
            id: 'selling_loose_quantity' + labelCount,
            name: 'selling_loose_quantity' + labelCount,
            onkeyup: 'medicine.calculate_med_price();'
        }).appendTo(linnerDiv);
        
        
        // QUANTITY ELEMENT
        var outerDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var label = $('<label>').attr({
            class: 'control-label'            
        }).appendTo(outerDiv);
        
        label.html('Quantity' + labelCount);
        
        var innerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(outerDiv);
        
        outerDiv.appendTo('#med_details_container');
        
        $('<input>').attr({
            type: 'number',
            id: 'med_quantity' + labelCount,
            name: 'quantity' + labelCount,
            class: 'validate[required]',
            onkeyup: 'medicine.calculate_med_price();'
        }).appendTo(innerDiv);
        
        
        // AMOUNT ELEMENT
        var aouterDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var qlabel = $('<label>').attr({
            class: 'control-label'            
        }).appendTo(aouterDiv);
        
        qlabel.html('Amount' + labelCount);
        
        var ainnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(aouterDiv);
        
        $('<input>').attr({
            type: 'number',
            id: 'med_amount' + labelCount,
            name: 'amount' + labelCount,
            readonly: 'readonly'
        }).appendTo(ainnerDiv);
        
        aouterDiv.appendTo('#med_details_container');
        
    }
    
    function remove_medicine_container(id)
    {
        $('*[id*=sold_med_details'+ id +']:visible').each(function() {
           $(this).remove();
        });
    }

    return {
        calculate_med_price : function() {
            calculate_med_price();
        },
        
        get_loose_item_derails : function() {
            get_loose_item_derails();
        },
        
        add_medicine_container : function() {
            add_medicine_container();
        },
        
        remove_medicine_container : function(id) {
            remove_medicine_container(id);
        }
    }
}();