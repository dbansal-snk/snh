medicine = function() {

    var _batch_list_url          = 'index.php?pharmacist/get_medicine_batch_list';
    var _med_distributor_url     = 'index.php?pharmacist/get_med_distributor_details';
    var _sold_to_patient_url     = 'index.php?pharmacist/sold_to_patient';
    var _check_med_duplicacy_url = 'index.php?pharmacist/check_dupliate_medicine_sale';
    var _generate_bill_url       = 'index.php?pharmacist/generate_medicine_bill';
     
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
            $('#loose_item_quantity').val('');
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
            onchange: 'medicine.calculate_med_price(), medicine.can_sell_loose_item(' + labelCount +'), medicine.getMedicineBatchList(' + labelCount +');',
        }).appendTo(minnerDiv);
        
        mouterDiv.appendTo('#med_details_container');
        
         $('<option>').val(0).text('--Select Medicine--').appendTo('#medicine_id' + labelCount);
         
        $.each (medicineList, function( key, value ) {
            if ('undefined' != typeof value && '' != value) {
                $('<option>').val(key).text(value).appendTo('#medicine_id' + labelCount);
            }
        });
        
        // BATCH LIST ELEMENT
        var bOuterDiv = $('<div>').attr({
            class: 'control-group',
            id: 'sold_med_details' + labelCount
        });
        
        var bLabel = $('<label>').attr({
            class: 'control-label'            
        }).appendTo(bOuterDiv);
        
        bLabel.html('<span class="batch_label">Batch' + labelCount + '</span>');
        
        var bInnerDiv = $('<div>').attr({
            class: 'controls'
        }).appendTo(bOuterDiv);
        
        bOuterDiv.appendTo('#med_details_container');
        
        $('<select>').attr({
            id: 'batch_id' + labelCount,
            name: 'batch_id' + labelCount
        }).appendTo(bInnerDiv);
      
        
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
        $('.loader').show();
        var totalSellingMedCount = $("[id*='selling_med_detais']").length;
        var erroMessage = '';
        for (var i=1; i<=totalSellingMedCount; i++) {
            var selectedMedValue = $('#medicine_id' + i).val();
            var medQuantity      = $('#med_quantity' + i).val();
            if (selectedMedValue == 0) {
                erroMessage += 'Please select Medicine' + i + '.\n';
            }
            
            if (medQuantity <= 0) {
                erroMessage += 'Please enter the Quantity' + i + '.\n';
            }
        }
        
        if ('' != erroMessage) {
            $('.loader').hide();
            alert(erroMessage);
            return false;
        } else {
            check_dupliate_medicine_sale();
        }
    }
    
    function validate_medicine_stock_form()
    {
        var errorMessage = '';
        
        if ($('#medicine_id').val() == 0) {
            errorMessage += 'Please select the medicine' + '\n';
        }
        
        if ($('#manufacture_company_id').val() == 0) {
            errorMessage += 'Please select the manufacturing company' + '\n';
        }
        
        if ($('#vendor_id').val() == 0) {
            errorMessage += 'Please select the vendor name' + '\n';
        }
        
        if ($('#is_loose_item').is(":checked") && $('#loose_item_quantity').val() <= 0) {
            errorMessage += 'Please mention the loose item quantity' + '\n';
        }

        if (errorMessage != '') {
            alert(errorMessage);
            return false;
        }
    }
    
    function check_dupliate_medicine_sale()
    {
        var formDetails = $('#med_prescribed_form').serializeArray();
        
        $.ajax({
            type: 'POST',
            url: _check_med_duplicacy_url,
            data: formDetails,
            success: function(response)
            {
                $('.loader').hide();
                if ('undefined' != typeof response.error && true == response.error) {
                    alert('You have recently sold the same medcines to the patient ' + response.content.patient_name);
                } else {
                    save_prescribed_details();
                }
            }
        });
    }
    
    function save_prescribed_details()
    {
        var formDetails = $('#med_prescribed_form').serializeArray();
        
        $.ajax({
            type: 'POST',
            url: _sold_to_patient_url,
            data: formDetails,
            success: function(response)
            {
                $('.loader').hide();
                if ('undefined' != typeof response.content.error && true == response.content.error) {
                    alert(response.content.message);
                } else {
                    window.location.href = 'index.php?pharmacist/manage_prescription';
                }
            }
        });
    }
    
    function generate_medicine_bill(id)
    {
        if ('undefined' != typeof id && '' != id && null != id) {
            $.ajax({
                type: 'POST',
                url: _generate_bill_url,
                data: {'medicine_sale_id': id},
                success: function(response)
                {
                    var popupWin = window.open('', '_blank', 'width=1000,height=1000');
                    popupWin.document.open();
                    popupWin.document.write(response.content);
//                    popupWin.document.close();
//                    window.print();
                }
            });

//            window.print();
//            document.body.innerHTML = id;
        }
    }
    
    function get_medicine_batch_list(medicineOrder)
    {
        var medicineId = $('#medicine_id' + medicineOrder).val();
        $('#batch_id' + medicineOrder).empty();
        if (medicineId > 0) {
            var postdata = {};
            postdata['medicine_id'] = medicineId;
            $('.loader').show();
            $.ajax({
                type: 'POST',
                url: _batch_list_url,
                data: postdata,
                success: function(response)
                {
                    $('.loader').hide();
                    if ('undefined' != typeof response.content && '' != response.content) {
                        $.each(response.content, function( index, value ) {
                            var newOption = $('<option>');
                            newOption.attr('value',value.batch).text(value.batch);
                            $('#batch_id' + medicineOrder).append(newOption);
                        });
                    }
                }
            });
        }
    }
    
    function get_med_distributor_details()
    {
        var medicineId = $('#medicine_id').val();
        $('#manufacture_company_id option:not(:first)').remove();
        $('#vendor_id option:not(:first)').remove();
        
        if (medicineId > 0) {
            var postdata            = {};
            postdata['medicine_id'] = medicineId;
            
            $.ajax({
                type: 'POST',
                url: _med_distributor_url,
                data: postdata,
                success: function(response)
                {
                    if ('undefined' != typeof response.content && '' != response.content) {
                        if ('undefined' != typeof response.content.manufacture_list && '' != response.content.manufacture_list) {
                            $.each(response.content.manufacture_list, function( index, value ) {
                                if (value.id > 0) {
                                    var newOption = $('<option>');
                                    newOption.attr('value',value.id).text(value.name);
                                    $('#manufacture_company_id').append(newOption);
                                }
                            });
                        }
                    
                        if ('undefined' != typeof response.content.vendor_list && '' != response.content.vendor_list) {
                            $.each(response.content.vendor_list, function( index, value ) {
                                if (value.id > 0) {
                                    var newOption = $('<option>');
                                    newOption.attr('value',value.id).text(value.name);
                                    $('#vendor_id').append(newOption);
                                }
                            });
                        }
                    }
                }
            });
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
        },
        
        validate_medicine_stock_form : function() {
            return validate_medicine_stock_form();
        },
        
        generate_medicine_bill : function(id) {
            generate_medicine_bill(id);
        },
        
        getMedicineBatchList : function(medicineOrder) {
            get_medicine_batch_list(medicineOrder);
        },
        
        get_med_distributor_details: function() {
            get_med_distributor_details();
        },
        
        printPage: function() {
            window.print();
        }
    }
}();