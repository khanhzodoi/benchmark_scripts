// Prevent any jQuery code from running before the document is finished loading (is ready).
// All of functions in the below scope is for the adding post of the web
$(function() {
	
	// Function to validate a chosen file from local host
	$("#file").change(function() {
		$("#mess").empty(); // To remove the previous error message
		var file = this.files[0];
		var imagefile = file.type;
        var match= ["image/jpeg","image/png","image/jpg"];
        var sourceLink = "http://localhost:8888/Facebook_MVC/src/";
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
		{
			$('#previewing').attr('src', sourceLink + 'img/noimage.png');
			$("#mess").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
		}
		else
		{
			var reader = new FileReader();
			reader.onload = (function (e) {
				$("#file").css("color","green");
				$('#image_preview').css("display", "block");
				$('#previewing').attr('src', e.target.result);
				$('#previewing').attr('width', '250px');
				$('#previewing').attr('height', '230px');
			});
			reader.readAsDataURL(this.files[0]);
			
		}
	});

	// Function to upload file to server, then it get the result that is sent by server side
	// 	It can be uploaded the image successfully or it can be some errors that server sent back to client
	$("#uploadimage").on('submit',(function(e) {
		e.preventDefault();
		$("#message").empty();
		$('#loading').show();
	
		$.ajax({
			url: "/Facebook_MVC/Home/HandleImage", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
			$('#loading').hide();
			$("#mess").html(data);
			}
		});	
	}));

	// New post can toggle between hiding and showing the adding post element
	$("#newpostbtn").on('click',(function() {
		$("#frmaddnewpost").toggle();
	}));

});

// ========================================================================================


/* To process when the document does not reload yet(for async) 
	All below functions are used for posts exclude the adding post. The adding post is process above this line
*/

	// Function to show a post for editing 
	function showEditBox(editobj, id, imageId=null) {
		$('#frmaddnewpost').hide();
		$(editobj).prop('disabled', true); // Disable the html element
		

        var sourceLink = "http://localhost:8888/Facebook_MVC/src/";

		if(imageId == null) {
			imageId = 'fake_id';
		} 

		$("#image_" + imageId).hide(); // Hide the image box

		var currentImagePath = $("#image_" + imageId + " .image-content").attr("src"); // Get current image path of the post
        var currentMessage = $("#message_" + id + " .message-content").text(); // Get current message of the post
		if(typeof currentImagePath === "undefined") currentImagePath = sourceLink + 'img/noimage.png';

		$("#post_"+id).css("text-align", "center");
		var editImage = '<form id="uploadimage_' + imageId + '" action="" method="post"  onsubmit="uploadImage(event, ' + '\'' + imageId + '\'' + ')" enctype="multipart/form-data">\
                				<div id="image_preview_' + imageId + '"><img id="previewing_' + imageId + '" src="' + currentImagePath +'" style="width: 250px; height: 230px;"/></div>\
                				<div id="selectImage' + imageId + '">\
								<label>Select Your Image</label><br/>\
								<input style="margin:0 auto" type="file" name="file" class="file" id="file_' + imageId + '" onChange="changePreviewImage(event, ' + '\'' + imageId + '\'' + ')" required />\
								<input type="submit" value="Update photo" class="submit" />\
								</div>\
						</form>\
						<h4 id="loading_' + imageId + '" >loading..</h4>\
            			<div id="mess_' + imageId + '"><img src="' + $("#image_" + imageId + " .image-content").attr("src") +'" id="file_src" alt="No image" style="display:none;"></div>';
		
		// Insert the above new block to update the image of the post that is identified by given id
		$("#post_" + id).prepend(editImage);

		// Insert the new block to update the message of the post that is identified by the given id
		var editMarkUp = '<textarea rows="5" cols="60" id="txtmessage_'+id+'" >' + currentMessage
						+ '</textarea><br><button name="ok" onClick="callCrudAction(\'edit\','+ '\'' + imageId + '\', ' + id + ')">Save</button><button name="cancel" onClick="cancelEdit(\''
						+ currentMessage.replace(/\"/g, '`') +'\', '+ '\'' + imageId + '\', ' + id + ')">Cancel</button>';
		$("#message_" + id + " .message-content").html(editMarkUp);


		$("#message_" + id).css("text-align", "center");

			
	};

	// Function to cancel the edit process
	function cancelEdit(message, imageId, id) {
		// Convert all the char ` to the char "
		message = message.replace(/\`/g, '"');

		// Remove all the edit block for the post
		$("#post_" + id + " #uploadimage_" + imageId).remove();
		$("#post_" + id + " #loading_" + imageId).remove();
		$("#post_" + id + " #mess_" + imageId).remove();

		// Show again the post with the image and message
		$("#post_" + id + " #message_" + id + " .message-content").html(message);
		$("#post_" + id + " #image_" + imageId).show();
		$("#post_" + id +  " .btnEditAction").prop('disabled', false); // Enable the edit button
	};

	// Function to preview image after validation for each post
	function changePreviewImage(event, imageId) {
		$("#mess_" + imageId).empty(); // To remove the previous error message
		var file = $('#file_' + imageId).prop('files')[0]; // Get file is chosen by the input tag
		var imagefile = file.type; // Get file type
        var match= ["image/jpeg","image/png","image/jpg"];
        var sourceLink = "http://localhost:8888/Facebook_MVC/src/";
		if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))) {
			$('#previewing_' + imageId).attr('src', sourceLink + 'img/noimage.png');
			$("#mess_" + imageId).html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
			return false;
		}
		// If the given is a image file, preview it to the previewing tag for user to see it before they hit update photo
		else {
			var reader = new FileReader();
			reader.onload = (function (e) {

				$("#file_" + imageId).css("color","green");
				$('#image_preview_' + imageId).css("display", "block");
				$('#previewing_' + imageId).attr('src', e.target.result);


				$('#previewing_' + imageId).attr('width', '250px');
				$('#previewing_' + imageId).attr('height', '230px');

			});
			reader.readAsDataURL(file);	
		}
	};

	// Function to update image of a specific post when user edit the post
	function uploadImage(e, imageId) {
		e.preventDefault();
		$("#mess_" + imageId).empty();
		$('#loading_' + imageId).show();

		jQuery.ajax({
			url: "/Facebook_MVC/Home/HandleImage", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data: new FormData(document.getElementById('uploadimage_' + imageId)), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data)   // A function to be called if request succeeds
			{
				$('#loading_' + imageId).hide();
				$("#mess_" + imageId).html(data);
				//$("#message_" + id + " .message-content").show();
			}
		});
	};

	
	// Function to prepare the data for the "create, update, retrieve, delete" actions for the server script
	// 	Then send it to the server by ajax technique.
	// 	Finally, add the result given from the server to the post on the web
	function callCrudAction(action, imageId, id) {

		$("#loaderIcon").show();
		var queryString;
		switch(action) {
			case "add":
				queryString = 'action='+action+'&txt_message='+ $("#txtmessage").val().replace(/\'/g, '"');
				
			break;
			case "edit":
				queryString = 'action='+action+'&message_id='+ id + '&txt_message='+ $("#txtmessage_"+id).val().replace(/\'/g, '"') + '&image_id=' + imageId;
				
			break;
			case "delete":
				queryString = 'action='+action+'&message_id='+ id;
			break;
		}
			 
		jQuery.ajax({
			url: "/Facebook_MVC/Home/CrudActionPost",
			data:queryString,
			type: "POST",
			success:function(data){
                var sourceLink = "http://localhost:8888/Facebook_MVC/src/";
				switch(action) {
					case "add":
						$('#frmaddnewpost').hide();
						$("#file_src").remove();
						$("#post-list-box").prepend(data); $("#mess").text(""); $('#previewing').attr('src', sourceLink + 'img/noimage.png');

					break;
					case "edit":

						$("#uploadimage_" + imageId).remove();
						$("#loading_" + imageId).remove();
						$("#mess_" + imageId).hide();
						
						// Edit the message block for post
						$("#message_" + id + " .message-content").html(data);
						
						// Update new image
						// If image box exists, change the image path to the new uploaded image path
						if($("#image_" + imageId).length > 0) {
							
							if($("#post_" + id +  " #file_src").length > 0 && $("#post_" + id +  " #file_src").attr("src") !== "") {
								
								$("#image_" + imageId + " .image-content").attr("src", $("#post_" + id +  " #file_src").attr("src"));
							}
							$("#image_" + imageId).show();
						}
						// Otherwise, create the image block for the post, attach a new image path to the image block
						// 	Then add the image block for the post
						else {
							if($("#post_" + id + " #file_src").length > 0 && $("#post_" + id + " #file_src").attr("src") !== "undefined") {
								var newImageBox = '<div class="image-box" id="image_' + imageId +'">\
													<img class="image-content" src="' + $("#file_src").attr("src") + '" alt="" class="img-rounded" id="Panel_Image" style="width: 250px; height: 230px;margin: 0 auto">\
													</div>';
								$("#post_" + id).prepend(newImageBox);
							}
							
						}
						
						$("#post_" + id + " .btnEditAction").prop('disabled', false); // Enable the edit button
						$("#mess_" + imageId).remove();
						$("#post_" + id).css("text-align", "left");
						window.location.reload();
					break;
					case "delete":
						$('#post_'+id).remove();

					break;
				}
				$("#txtmessage").val('');
				$("#loaderIcon").hide();
			},
			error:function (){}
		});
	};

// ========================================================================================
