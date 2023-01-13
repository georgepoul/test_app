$(document).ready(function () {

    $('td').click(function () {
        let row_index = $(this).parent().index();
        let col_index = $(this).index();

        if (col_index === 1) {

            window.location.assign("deleteCourses.php?id=" + row_index)
        } else if (col_index === 2) {
        }

    });

})




