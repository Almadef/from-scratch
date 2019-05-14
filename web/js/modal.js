$('#coverAlbumModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var code = button.data('whatever');
    var modal = $(this);
    arr = code.split('_')//0-idAlbum, 1-coverPath, 2-titleAlbum
    modal.find('.modal-body #coverIdAlbum').val(arr[0]);
    $('#coverPathAlbum').attr('src', arr[1]);
    $('#coverPathAlbum').attr('alt', arr[2]);
});
$('#coverAlbumModal').on('hidden.bs.modal', function () {
    $('#updateTitleAlbum').val('');
    $('#updatePerformersAlbum').empty();
});

$('#updateAlbumModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('whatever');
    var modal = $(this);
    modal.find('.modal-body #updateIdAlbum').val(id);
    $.ajax({
        type: 'post',
        url: '/albumSettings/ajaxSelectUserAlbum',
        data: {'id': id},
        response: 'text',
        success: function (data) {
            var arr = JSON.parse(data);
            $('#updateTitleAlbum').val(arr['title']);
            arr['performers'].forEach(function (element) {
                if (element['selected']) {
                    $('#updatePerformersAlbum').append('<option selected value="' + element['id'] + '">' + element['title'] + '</option>');
                } else {
                    $('#updatePerformersAlbum').append('<option value="' + element['id'] + '">' + element['title'] + '</option>');
                }
            });
        }
    });
});
$('#updateAlbumModal').on('hidden.bs.modal', function () {
    $('#updateTitleAlbum').val('');
    $('#updatePerformersAlbum').empty();
    $('#errorMsgUpadte').empty();
});
$('#updateAlbumModal').on("click", "#updateBtn", function () {
    res=$().validate('validModal','updateAlbumModal');
    if(res) {
        id = $('#updateIdAlbum').val();
        title = $('#updateTitleAlbum').val();
        performers = $('#updatePerformersAlbum').val();
        $.ajax({
            type: 'post',
            url: '/albumSettings/ajaxUpdateAlbum',
            data: {'id': id, 'title': title, 'performers': performers},
            dataType: 'html',
            success: function (data) {
                if (data == '') {
                    window.location.reload();
                } else {
                    $('#errorMsgUpadte').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">\n' +
                        data +
                        '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '                        <span aria-hidden="true">×</span>\n' +
                        '                    </button>\n' +
                        '                </div>');
                }
            }
        });
    }
});
$('#addAlbumModal').on('show.bs.modal', function () {
    $.ajax({
        type: 'post',
        url: '/albumSettings/ajaxAllPerformers',
        response: 'text',
        success: function (data) {
            var arr = JSON.parse(data);
            arr.forEach(function (element) {
                $('#addPerformersAlbum').append('<option value="' + element['id'] + '">' + element['title'] + '</option>');
            });
        }
    });
});
$('#addAlbumModal').on('hidden.bs.modal', function () {
    $('#addTitleAlbum').val('');
    $('#addPerformersAlbum').empty();
    $('#errorMsgAdd').empty();
});
$('#addAlbumModal').on("click", "#createBtn", function () {
    res=$().validate('validModal','addAlbumModal');
    if(res) {
        title = $('#addTitleAlbum').val();
        performers = $('#addPerformersAlbum').val();
        $.ajax({
            type: 'post',
            url: '/albumSettings/ajaxCreateAlbum',
            data: {'title': title, 'performers': performers},
            dataType: 'html',
            success: function (data) {
                if (data == '') {
                    window.location.reload();
                } else {
                    $('#errorMsgAdd').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" align="left">\n' +
                        data +
                        '                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '                        <span aria-hidden="true">×</span>\n' +
                        '                    </button>\n' +
                        '                </div>');
                }
            }
        });
    }
});