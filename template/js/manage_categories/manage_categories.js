manage_category = function() {
    
    var addMedUrl = 'index.php?pharmacist/manage_medicine_category/create';
    function addMedicine() {
//        var formDetails = $('#add_medicine').serializeArray();
var name = $('#add_medicine #name').val();
var desc = $('#add_medicine #description').val();
        $.ajax({
            type: 'POST',
            url: addMedUrl,
            data: {'name': name, 'descrption': desc, 'proxy_url':addMedUrl},
            success: function(response)
            {
                if ('undefined' != typeof response.content.error && true == response.content.error) {
                    alert(response.content.message);
                } else {
                    location.reload();
                }
            }
        });
    }

    function updateMedicine()
    {
        var formDetails = $('#update_medicine').serializeArray();
        $.ajax({
            type: 'POST',
            url: 'index.php?pharmacist/manage_medicine_category/edit/do_update',
            data: formDetails,
            success: function(response)
            {
                if ('undefined' != typeof response.content.error && true == response.content.error) {
                    alert(response.content.message);
                } else {
                    window.location.href = 'index.php?pharmacist/manage_medicine_category';
                }
            }
        });
    }

    return {
        addMedicine: function() {
            addMedicine();
        },

        updateMedicine: function() {
            updateMedicine();
        }
    }
}();
