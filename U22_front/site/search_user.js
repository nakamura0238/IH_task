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
                    let innerHTML = `
                        <a href="./user.php?index=${data.user_index}">
                            <p>name : ${data.name}</p>
                            <p>id : ${data.user_id}</p>
                        </a>
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