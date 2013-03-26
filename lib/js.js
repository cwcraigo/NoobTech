

function get_videos(str) {

    var xmlhttp;

    //If empty then hide.
    if (str=="") {

      document.getElementById("video_list").innerHTML="";
      return false;

    }

    //Create new object.
    if (window.XMLHttpRequest){

        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();

    } else {

        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    //Function to print out response
    xmlhttp.onreadystatechange=function() {

        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            document.getElementById("video_list").innerHTML=xmlhttp.responseText;
        }
    }

    //Open object and send it.
    xmlhttp.open("GET","/lib/movie_search.php?tag="+str,true);
    xmlhttp.send();

    // return false;
}

// --------------------------------------------------------------------------
// FUNCTION
function login_form() {
    document.getElementById("loginDiv").style.display = "inherit";
} // end login_form()
// --------------------------------------------------------------------------











