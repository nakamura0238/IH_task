let url = '../follow/follow_fuction.php'

// // モーダル表示
// $('button.js-btn-follow').on('click', () => {
//     $('div.js-follow-modal').fadeIn(0)
// })

// // POSTリクエスト
// $('button.js-follow-submit').on('click', (e) => {
//     follower_index = $(e.target).val()
//     $.ajax({
//         type:'POST',
//         url: url,
//         data: {'follower_user' : follower_index, 'status': 'request'},
//         dataType: 'json',
//         error : function(XMLHttpRequest, textStatus, errorThrown) {
//             console.log("ajax通信に失敗しました");
//             console.log("XMLHttpRequest : " + XMLHttpRequest.status);
//             console.log("textStatus     : " + textStatus);
//             console.log("errorThrown    : " + errorThrown.message);}
//     }).done((data) => {
//         if (data.result) {
//             $('button.js-btn-follow').html('following')
//             $('button.js-follow-submit').html('unfollow')
//             $('p.modal-message').html('フォロー解除します')
//         } else {
//             $('button.js-btn-follow').html('follow')
//             $('button.js-follow-submit').html('follow')
//             $('p.modal-message').html('フォローします')
//         }
//     }).fail(() => {
//     })
// })

// // モーダル閉じる
// $('button.js-close-modal').on('click', () => {
//     $('div.js-follow-modal').fadeOut(0)
// })
// $(document).click(function(event){
//     let target = $(event.target);
//     if(target.hasClass('js-follow-modal')) {
//         target.fadeOut(100);
//     }
// });


// POSTリクエスト
$('button.js-follow-submit').on('click', (e) => {
    follower_index = $(e.target).val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'follower_user' : follower_index, 'status': 'request'},
        dataType: 'json',
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);}
    }).done((data) => {
        if (data.result) {
            $('button.js-btn-follow').html('unfollow')
            // $('button.js-follow-submit').html('unfollow')
            // $('p.modal-message').html('フォロー解除します')
        } else {
            $('button.js-btn-follow').html('follow')
            // $('button.js-follow-submit').html('follow')
            // $('p.modal-message').html('フォローします')
        }
    }).fail(() => {
    })
})