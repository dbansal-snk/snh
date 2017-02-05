medicine = function() {

     
    function calculate_med_price()
    {
        var totalSellingMedCount = $("[id*='selling_med_detais']").length;
        var totalAmount          = 0;
        for (var i = 1; i <= totalSellingMedCount; i++) {
            var medicine_id = $("#medicine_id" + i).val();
            if (medicine_id != '' && $.isNumeric(medicine_id) && medicine_id > 0) {
                var mrp = medicine_price_details[medicine_id];
                // if the loose item is being sold
                if ($('#selling_loose_quantity' + i).is(":checked") && loose_item_mrp[medicine_id] > 0) {
                    mrp = loose_item_mrp[medicine_id];
                }

                var quantity = $('#med_quantity' + i).val();
                var amount   = quantity * mrp;
                amount       = amount.toFixed(2);
                if ($.isNumeric(amount) == true) {
                    $('#med_amount' + i).val(amount);
                    totalAmount = parseFloat(totalAmount) + parseFloat(amount);
                    totalAmount = totalAmount.toFixed(2);
                } else {
                    $('#med_amount' + i).val(0);
                }
            }
        }
        
        
        if ($.isNumeric(totalAmount) == true) {
            var discount      =  $('#discount').val();
            var totalDiscount = 0;
            if ($.isNumeric(discount) == true && discount > 0) {
                totalDiscount = ((totalAmount*discount)/100).toFixed(2);
                totalAmount   = totalAmount - totalDiscount;
            }
            
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
        var labelCount = $("[id*='selling_med_detais']").length + 1;
        // keep the medicine details count in the hidden parameter
        $('#total_med_details').val(labelCount);
        
        
        var seprator = $('<div>').attr({
            class: 'seprator-dooted-lines'
        });
        
        // MEDICINE LIST ELEMENT
        var mouterDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        mouterDiv.append(seprator);
        
        var mlabel = $('<label>').attr({
            class: 'control-label',
            id: 'selling_med_detais'
        }).appendTo(mouterDiv);
        
        mlabel.html('<span class="medicine_label">Medicine' + labelCount + '</span>');
        
        var minnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(mouterDiv);
        
        $('<select>').attr({
            id: 'medicine_id' + labelCount,
            name: 'medicine_id' + labelCount,
            onchange: 'medicine.calculate_med_price(), medicine.can_sell_loose_item(' + labelCount +');',
        }).appendTo(minnerDiv);
        
        mouterDiv.appendTo('#med_details_container');
        
         $('<option>').val(0).text('--Select Medicine--').appendTo('#medicine_id' + labelCount);
         
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
            class: 'control-group , loose_med_option' + labelCount,
            id: 'sold_med_details' + labelCount
        });
        
        var llabel = $('<label>').attr({
            class: 'control-label'
        }).appendTo(louterDiv);
        
        llabel.html('<span class="loose_quant_label">Selling Loose Quantity' + labelCount + '</span>');
        
        var linnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(louterDiv);

        louterDiv.appendTo('#med_details_container');
        
        $('<input>').attr({
            type: 'checkbox',
            id: 'selling_loose_quantity' + labelCount,
            name: 'selling_loose_quantity' + labelCount,
            onchange: 'medicine.calculate_med_price();'
        }).appendTo(linnerDiv);
        
        
        // QUANTITY ELEMENT
        var outerDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var label = $('<label>').attr({
            class: 'control-label'            
        }).appendTo(outerDiv);
        
        label.html('<span class="quantity_label">Quantity' + labelCount + '</span>');
        
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
        
        qlabel.html('<span class="amount_label">Amount' + labelCount + '</span>');
        
        var ainnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(aouterDiv);
        
        $('<input>').attr({
            type: 'text',
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
        
        // keep the medicine details count in the hidden parameter
        var labelCount = $("[id*='selling_med_detais']").length;
        $('#total_med_details').val(labelCount);
        
        
        // change the label of the medicine details which is being sold to patient
        var medDetailCount = 2;
        $('*[class*=medicine_label]:visible').each(function() {
           $(this).html('Medicine' + medDetailCount);
           medDetailCount++;
        });
        
        medDetailCount = 2;
        $('*[class*=loose_quant_label]:visible').each(function() {
           $(this).html('Selling Loose Quantity' + medDetailCount);
           medDetailCount++;
        });
        
        medDetailCount = 2;
        $('*[class*=quantity_label]:visible').each(function() {
           $(this).html('Quantity' + medDetailCount);
           medDetailCount++;
        });
        
        medDetailCount = 2;
        $('*[class*=amount_label]:visible').each(function() {
           $(this).html('Amount' + medDetailCount);
           medDetailCount++;
        });
    }
    
    function can_sell_loose_item(getPosition) {
        var looseQuantityContainer = 'loose_quantity_element' + getPosition;
        var getMedicineId = $('#medicine_id' + getPosition).val();
        
        if ('undefined' != typeof medicine_loose_item_info[getMedicineId] && 0 == medicine_loose_item_info[getMedicineId]) {
            $('#' + looseQuantityContainer).hide();
            $('.loose_med_option' + getPosition).hide();
        } else {
            $('#' + looseQuantityContainer).show();
            $('.loose_med_option' + getPosition).show();
        }
    }
    
    function validate_medicine_sale_form() {
        var totalSellingMedCount = $("[id*='selling_med_detais']").length;
        var erroMessage = '';
        for (var i=1; i<=totalSellingMedCount; i++) {
            var selectedMedValue = $('#medicine_id' + i).val();
            if (selectedMedValue == 0) {
                erroMessage += 'Please select Medicine' + i + '\n';
            }
        }
        
        if ('' != erroMessage) {
            alert(erroMessage);
            return false;
        }
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
        },
        
        can_sell_loose_item : function(id) {
            can_sell_loose_item(id);
        },
        
        validate_medicine_sale_form : function() {
            validate_medicine_sale_form();
        }
    }
}();