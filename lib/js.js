

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

}

// --------------------------------------------------------------------------
// FUNCTION
function login_form() {
    document.getElementById("loginDiv").style.display = "inherit";
} // end login_form()
// --------------------------------------------------------------------------

function suggestion_pagination(page) {

    var xmlhttp;

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
            document.getElementById("suggestion_div").innerHTML = "";
            document.getElementById("suggestion_div").innerHTML=xmlhttp.responseText;
        }
    }

    //Open object and send it.
    xmlhttp.open("GET","/lib/suggestion_pagination.php?page="+page,true);
    xmlhttp.send();
}


function video_pagination(page) {

    var xmlhttp;

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
            document.getElementById("video_div").innerHTML = "";
            document.getElementById("video_div").innerHTML=xmlhttp.responseText;
        }
    }

    //Open object and send it.
    xmlhttp.open("GET","/lib/video_pagination.php?page="+page,true);
    xmlhttp.send();

}

function comment_pagination(page,id) {

    var xmlhttp;

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
            document.getElementById("comment_div").innerHTML = "";
            document.getElementById("comment_div").innerHTML=xmlhttp.responseText;
        }
    }

    //Open object and send it.
    xmlhttp.open("GET","/lib/comment_pagination.php?page="+page+"id="+id,true);
    xmlhttp.send();

}

function like(type,id) {
console.log(type+' / '+id);
    var xmlhttp;

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
            if (type == 'suggestion') {
                document.getElementById("suggestion_div").innerHTML = "";
                document.getElementById("suggestion_div").innerHTML=xmlhttp.responseText;
            } else {
                document.getElementById("like_count").innerHTML = "";
                document.getElementById("like_count").innerHTML=xmlhttp.responseText;
            }

        }
    }

    //Open object and send it.
    xmlhttp.open("GET","/lib/like.php?type="+type+"&id="+id,true);
    xmlhttp.send();

}