$(document).ready(function(){

  // 入力フォーム
  var formInputs = $('input[type="text"],input[type="password"],select');
  formInputs.focus(function() {
    $(this).parent().children('p.js-formLabel').addClass('formTop');
  });

  formInputs.focusout(function() {
    if ($.trim($(this).val()).length == 0){
      $(this).parent().children('p.js-formLabel').removeClass('formTop');
    }
  });

  $('p.formLabel').click(function(){
    $(this).parent().children('.form-style').focus();
  });

  // チェックボックス
  $('label[for=checkbox]').click(function(){
    if($(this).prev().hasClass('checked')){
      $(this).prev().removeClass('checked');
    } else {
      $(this).prev().addClass('checked');
    }
  });

  // ログインボタン リンク
  var link_h = $('div.link').height();
  var button_h = $('div.button').height();

  if(link_h > button_h){
    $('div.button').height(link_h + 'px');
  } else {
    $('div.link').height(button_h + 'px');
  }

  // ハンバーガー
  $('.js-navBtn').click(function(){
    $('nav.nav , .btn-line').toggleClass('open');
  });

  // タブメニュー
  $('li').click(function(){
    var getClass = $(this).attr('class').split(" ")[0];
    $('div.tabOpen').removeClass('tabOpen');
    $('li.tabMenuOpen').removeClass('tabMenuOpen');
    $('div#' + getClass).addClass('tabOpen');
    $('li.' + getClass).addClass('tabMenuOpen');
  });

  // スライドメニュー
  $('.js-slideBtn').click(function(){
      $(this).parent().parent().find('.js-slideContent').slideToggle();
      $(this).toggleClass('js-btnRotate');
  });



  // レスポンシブ用 
  function switchByWidth(){
    if(window.matchMedia('(min-width: 1000px)').matches) {
      if($('.js-slideBtn').hasClass('js-btnRotate')){
        $(this).removeClass('js-btnRotate');
        $('.js-slideContent').slideDown();
      }
    }
  }

  // window.onload = switchByWidth;
  window.onresize = switchByWidth;

  // 画像反映
    var nowImg = $('img.js-setting').attr('src');
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];
    if (! file.type.match('image.*')) {
      $(this).val('');
      alert("画像ファイルを選択してください。");
      $('img.js-setting').attr('src', nowImg);
      return;
    }

    var reader = new FileReader();
    reader.onload = function() {
      $('img.js-setting').attr('src', reader.result);
    }
    reader.readAsDataURL(file);
  });

  // モーダル展開
  $('button.js-btn-inv').on('click', () => {
    $('div.js-inv-modal').fadeIn(0);
  });

  // モーダル閉じる
  // $('button.js-close-modal').on('click', () => {
  //   $('div.js-quit-modal').fadeOut(0)
  // })
  $(document).click(function(event){
    let target = $(event.target);
    if(target.hasClass('js-inv-modal')) {
        target.fadeOut(100);
    }
  });


//   // 招待モーダル
//   $('.inviteModalBtn').click(function(){
//     $('.followerModal').fadeIn();
//   });
//   $('.modalClose').click(function(){
//     $('.followerModal').fadeOut();
//   });

//   // 退会モーダル
//   $('button.js-quiteButton').click(function(){
//     $('.quit-modal').fadeIn();
//   });
//   $('button.js-close-modal').click(function(){
//     $('.quit-modal').fadeOut();
//   });
});