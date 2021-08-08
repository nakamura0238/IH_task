let url = "./search_function.php"

$('button.js-btn-search').on('click', () => {
    let word = $('input.js-search').val().trim()
    if (word != '') {
        // 非同期通信処理
        $.ajax({
            type: 'POST',
            url: url,
            data: {'word' : word},
            dataType: 'json'
        }).done((data) => {
            $('div.result').empty()
            if (data) {
                if (data.user_id != data.my_id) {
                    if (data.picture) {
                        picture = "../images/user/" + data.picture
                    } else {
                        picture = "../images/user/default.png"
                    }
                    let innerHTML = `
                    <div class="item">
                        <a href="./user_page.php?index=${data.user_index}">
                            <div class="info">
                                <img src="${picture}" alt="iconImage">
                                <h2>${data.name}</h2>
                            </div>
                        </a>
                    </div>
                    `
                    $('div.result').html(innerHTML)
                } else {
                    $('div.result').html('あなたのIDです')
                }
            } else {
                $('div.result').html('ユーザーが見つかりません')
            }
        })
    }
})