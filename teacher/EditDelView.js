$(document).ready(function () {

    $('td').click(function(){
        let row_index = $(this).parent().index();
        let col_index = $(this).index();

        if (col_index === 1){
            // console.log(row_index)
            // console.log(col_index)
            window.location.assign("edit.php?id=" + row_index)
        }else if (col_index ===2){

            window.location.assign("delete.php?id=" + row_index)
        }else if (col_index === 3){

            window.location.assign("view.php?id=" + row_index)
        }

            });

})




