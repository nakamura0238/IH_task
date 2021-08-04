let url = '../group/group_function.php'





// グループ参加
$('button.js-enter-group-index').on('click', (e) => {
    group_index = $(e.target).val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'group_index' : group_index, 'status' : 'enter'},
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

// メンバー招待
$('button.js-btn-invitation').on('click', (e) => {
    let param = (new URL(document.location)).searchParams;
    let group_index = param.get('group_index')
    inv_user_index = $(e.target).val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'group_index' : group_index, 'inv_user_index' : inv_user_index, 'status' : 'invitation'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        if (data.result) {
            $(e.target).html('招待中')
        }
    }).fail(() => {
    })
})

// メンバー削除
$('button.js-member-delete').on('click', (e) => {
    let param = (new URL(document.location)).searchParams
    let group_index = param.get('group_index')
    user_index = $(e.target).val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'group_index' : group_index, 'user_index' : user_index, 'status' : 'member_delete'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        if (data.result) {
            $(e.target).html('退出済')
        }
    }).fail(() => {
    })
})


// 招待削除
$('button.js-inv-delete').on('click', (e) => {
    let param = (new URL(document.location)).searchParams
    let group_index = param.get('group_index')
    inv_user_index = $(e.target).val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'group_index' : group_index, 'inv_user_index' : inv_user_index, 'status' : 'inv_delete'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        if (data.result) {
            $(e.target).html('招待取消')
        }
    }).fail(() => {
    })
})
