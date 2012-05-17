function postblog()	{
	if(window.XMLHttpRequest)   {
		xmlhttp = new XMLHttpRequest();
	} else {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)    {
			if(xmlhttp.responseText == 1)	{
					alert('Blog Post has been entered.');
					window.location = "../blog.php";
			}else {
					alert(xmlhttp.responseText);
			}
		}
	}
	
	data = 'blog_post_title=' + document.getElementById('blogpost_title').value + '&blog_post_body=' + document.getElementById('blogpost_body').value + '&blog_post_picture_name=' + document.getElementById('image_file_code').value;
	xmlhttp.open('POST','../includes/scripts/admin_blog_post.php',true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xmlhttp.send(data);
}

function loginUser()	{
	if(window.XMLHttpRequest)   {
		xmlhttp = new XMLHttpRequest();
	} else {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	}
	
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)    {
			//Got to modify this peice of code to change the text color if wrong and stuff.
			if(xmlhttp.responseText == 1)	{
					alert('Login Succesfull. Redirecting to Index');
					window.location = "./index.php";
			}else {
					alert(xmlhttp.responseText);
			}
		}
	}
	var username = document.getElementById('login_username').value;
	var password = document.getElementById('login_password').value;
	var cookieSet = 0;
	//Lets check if the user wants to set a cookie or not.
	if(document.getElementById('login_cookie_set').checked == 1)	{
		cookieSet = 1;
	}
	
	
	if(username.length > 3 && password.length > 3)	{
		data = 'username=' + document.getElementById('login_username').value + '&password=' + document.getElementById('login_password').value + '&cookieSet='+cookieSet;
		xmlhttp.open('POST','./includes/scripts/user_login.php',true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlhttp.send(data);
	} else {
		if(username.length < 3 )	{
			alert('Username Is too Short');
		} else {
			alert('Password is Too Short');
		}
	}
}