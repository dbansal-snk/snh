common = function() {

    function ajaxRequest(url, data)
    {
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(response)
            {
                console.log(response);
                return response;
            }
        });
    }

    return {
        ajaxRequest : function(url, data) {
            return ajaxRequest(url, data);
        }
    }
}();
