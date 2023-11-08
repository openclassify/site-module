// Delete Alias
$(".sitealiasdel").on("click", function () {
    $.ajax({
        url: '/api/site/' + site_id + '/aliases/' + $(this).attr('data-id'),
        type: 'DELETE',
        success: function (data) {
            $('#mainloading').removeClass('d-none');
        },
        complete: function () {
            setTimeout(() => {
                location.reload();
            }, 5000);
        }
    });
});

// Change PHP
$('#sitephpversubmit').click(function() {
    $.ajax({
        url: '/api/site/'+site_id,
        type: 'PATCH',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
            'php': $('#sitephpver').val(),
        }),
        beforeSend: function() {
            $('#sitephpversubmit').html('<i class="fas fa-circle-notch fa-spin"></i>');
        },
        success: function(data) {
            $('#sitephpversubmit').empty();
            $('#sitephpversubmit').html('<i class="fas fas fa-edit"></i>');
            location.reload();
        },
    });
});