jQuery(function($) {
    $('#filter').on('submit', function(e) {
        e.preventDefault();

        var country = [];
        $('input[name="country[]"]:checked').each(function() {
            country.push(this.value);
        });

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'filter_posts',
                country: country
            },
            success: function(response) {
                $('#first-grid').remove();
                $('#response').html(response);
            }
        });
    });
});