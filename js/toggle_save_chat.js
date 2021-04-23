
$(document).on("click", ":submit", function (e){
    var url = "https://gibbl.io/controllers/toggle_class.php";
    var url2 = "https://gibbl.io/chat/browse.php";

    if($(this).val() === "Search"){
        var semester = document.getElementById("semester").value;
        var subject = document.getElementById("subject").value;
        var number = document.getElementById("number").value;

        if(semester !== "")
            url2 += '?semester='+semester+'';
        if(subject !== "")
            url2 += '&subject='+subject+'';
        if(number !== "")
            url2 += '&number='+number;

        window.location.replace(url2);
    }
    else{
        $.post(url, {
            course_code: $(this).val()
        });
        if(this.textContent === "Add"){
            this.textContent = "Remove";
        }
        else{
            this.textContent = "Add";
        }
    }

    return false;
});