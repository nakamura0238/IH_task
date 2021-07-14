let url = '../like/register_function.php'

$('select.js-genre-a').on('change', () => {
    let genre_a = $('select.js-genre-a').val()
    $.ajax({
        type:'POST',
        url: url,
        data: {'genre_a' : genre_a},
        dataType: 'json'
    }).done((data) => {
        $('select.js-genre-b').empty()
        if(data[0].in_genre_a == genre_a) {
            data.forEach(val => {
                $('select.js-genre-b').append(`<option value="${val.genre_b_index}">${val.genre_b_name}</option>`)
            });
        } 
        else {
            alert('値が不正です')
        }

    })
})