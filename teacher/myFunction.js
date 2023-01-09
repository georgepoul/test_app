    function myFunction(x) {
    document.cookie = 'id = ' + x.rowIndex;
    console.log('cookie');

    // const idIndex = x.rowIndex;
    // console.log(idIndex);

    // $.ajax({
    //     url: "questions.php",
    //     method: "POST",
    //     data: { line: x.rowIndex},
    //     success: function(html){
    //         alert(html); /* checking response */
    //         $('#chat-body').html(html); /* add to div chat */
    //     }
    // });
}
