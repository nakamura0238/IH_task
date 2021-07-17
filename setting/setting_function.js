let url = '../setting/setting_function.php'



// POSTリクエスト
$('button.js-btn-quit').on('click', (e) => {
    // 入力確認
    password = $('input.js-password').val();

    // パスワード確認
    $.ajax({
        type:'POST',
        url: url,
        data: {'user_password' : password, 'status' : 'quit'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        // パスワード確認完了
        if (data) {
            console.log('OK')
            // モーダル表示
            $('div.js-quit-modal').fadeIn(0)
        } else {
            $('p.js-password-alert').html('パスワードが違います')
        }
    }).fail(() => {
    })

})

// モーダル閉じる
$('button.js-close-modal').on('click', () => {
    $('div.js-quit-modal').fadeOut(0)
})
$(document).click(function(event){
    let target = $(event.target);
    if(target.hasClass('js-quit-modal')) {
        target.fadeOut(100);
    }
});
