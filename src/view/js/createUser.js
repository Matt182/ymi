function ajaxCreateUser()
{
    var xhr = new XMLHttpRequest();
    var formData = new FormData(document.forms.createUser);

    xhr.open('POST','/create', true);
    xhr.onreadystatechange=function()
    {
        if (xhr.readyState==4 && xhr.status==200) {
            document.getElementById("userList").innerHTML = xhr.responseText;
        }
    }
    xhr.send(formData);

}
