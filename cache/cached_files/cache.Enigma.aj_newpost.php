<div id="rightBarContent">
	<p class="aNewPostHeading"> New Post </p>
	<form action="#" method="POST" enctype="multipart/form-data" id="newPost">
	<div class="aNewPostLeftPane">
		Topic / Post Name <sup>*</sup> :
	</div>
	<div class="aNewPostRightPane">
		<input type="text" name="postTitle" id="postTitle"/> 
	</div>
	<div class="aNewPostLeftPane">
		Topic / Post Details <sup>**</sup> :
	</div>
	<div class="aNewPostRightPane">
		<textarea rows="10" cols = "50" id="postDetails" name="postDetails"></textarea> 
	</div>
	<div class="aNewPostCenterPane">
		<!-- Upload field goes here -->
		<input type="file" name="file_1"/>
		<div id="newUploadHere"></div>
		<a href="#" id="addMoreUpload" >Add More</a>
	</div>
	<div id="aNewPostMsg">
		<sub>*</sub> - Needs to be atleast 6 charecters long. <br />
		<sub>**</sub> - Needs to be atleast 10 Charecters long.
	</div>
	<div class="aNewPostSubmit">
		<input type="submit" name="submitPost" id="submitButton" value="Submit Post"/>
	</div>
	</form>
</div>