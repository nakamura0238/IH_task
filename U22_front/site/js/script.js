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

  // ログインボタン リンク
  var link_h = $('div.link').height();
  var button_h = $('div.button').height();

  if(link_h > button_h){
    $('div.button').height(link_h + 'px');
  } else {
    $('div.link').height(button_h + 'px');
  }

  // カテゴリー
//   $('div.js-categoryL').change(function(){
//     $(this).parent().children('div.js-categoryM').removeClass('js-displayHidden');
//   });
//   $('div.js-categoryM').change(function(){
//     $(this).parent().children('div.js-categoryS').removeClass('js-displayHidden');
//   });

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

  window.onload = switchByWidth;
  window.onresize = switchByWidth;

  // フォーム追加
  $('.js-addForm').click(function(){
    $('main').append(
      "<div class='category'>"+
      // categoryL
        "<div class='form-item categoryL js-categoryL'>"+
          "<p class='formLabel js-formLabel'>CategoryL</p>"+
          "<select name='category'>"+
            "<option selected disabled hidden style='display: none' value=''></option>"+
            "<option value='1'>1</option>"+
            "<option value='1'>2</option>"+
            "<option value='1'>3</option>"+
          "</select>"+
        "</div>"+
        
        // categoryM
        "<div class='form-item categoryM js-categoryM'>"+
          "<p class='formLabel js-formLabel'>CategoryM</p>"+
          "<select name='category'>"+
            "<option selected disabled hidden style='display: none' value=''></option>"+
            "<option value='1'>1</option>"+
            "<option value='1'>2</option>"+
            "<option value='1'>3</option>"+
          "</select>"+
        "</div>"+
        
        "<div class='clear-fix'></div>"+
        
        // categoryS
        "<div class='form-item categoryS js-category'>"+
          "<p class='formLabel js-formLabel'>CategoryS</p>"+
          "<input type='text' name='name' id='name' class='form-style' autocomplete='off'/>"+
        "</div>"+
      "</div>"
    );
  });

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


  // モーダル
  $('.inviteButton').click(function(){
    $('.followerModal').fadeIn();
  });
  $('.modalClose').click(function(){
    $('.followerModal').fadeOut();
  });

});
