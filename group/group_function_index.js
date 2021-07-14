let url = './group/group_function.php'

// POSTリクエスト
$('button.js-enter-group-index').on('click', (e) => {
    group_index = $(e.target).val()
    enter_index = $('input.js-enter-user-index').val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'group_index' : group_index, 'user_index' : enter_index, 'status' : 'enter'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        if (data.result) {
            $(e.target).html('参加しました')
        }
    }).fail(() => {
    })
})
