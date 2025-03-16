<?php
session_start();

if (isset($_SESSION['username'])) {
  	# Database connection file
  	include 'app/db.conn.php';

  	include 'app/helpers/user.php';
  	include 'app/helpers/chat.php';
  	include 'app/helpers/opened.php';
  	include 'app/helpers/timeAgo.php';

  	if (!isset($_GET['user'])) {
  		header("Location: home.php");
  		exit;
  	}

  	# Getting User data
  	$chatWith = getUser($_GET['user'], $conn);

  	if (empty($chatWith)) {
  		header("Location: home.php");
  		exit;
  	}

  	$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

  	opened($chatWith['user_id'], $conn, $chats);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Chat App</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- Custom CSS -->
	<style>
		/* Chat Container */
		.chat-box {
			height: 400px;
			overflow-y: auto;
			background-color: #f8f9fa;
			border-radius: 10px;
			padding: 10px;
		}

		/* Message Styling */
		.rtext {
			background-color: #007bff;
			color: white;
			max-width: 70%;
			padding: 10px;
			border-radius: 10px 10px 0 10px;
			margin-left: auto;
			margin-bottom: 10px;
			animation: slideInRight 0.3s ease;
		}

		.ltext {
			background-color: #e9ecef;
			color: black;
			max-width: 70%;
			padding: 10px;
			border-radius: 10px 10px 10px 0;
			margin-right: auto;
			margin-bottom: 10px;
			animation: slideInLeft 0.3s ease;
		}

		/* Animations */
		@keyframes slideInRight {
			from { transform: translateX(100%); opacity: 0; }
			to { transform: translateX(0); opacity: 1; }
		}

		@keyframes slideInLeft {
			from { transform: translateX(-100%); opacity: 0; }
			to { transform: translateX(0); opacity: 1; }
		}

		/* Online Indicator */
		.online {
			width: 10px;
			height: 10px;
			background-color: #28a745;
			border-radius: 50%;
			display: inline-block;
			margin-right: 5px;
		}

		/* Delete Button */
		.delete-btn {
			cursor: pointer;
			color: #dc3545;
			margin-left: 10px;
			font-size: 12px;
		}

		.delete-btn:hover {
			text-decoration: underline;
		}

		/* Input Area */
		.input-group {
			margin-top: 10px;
		}

		textarea {
			resize: none;
			border-radius: 10px;
			height: 40px !important;
		}

		/* Back Button */
		.back-btn {
			font-size: 24px;
			color: #007bff;
			text-decoration: none;
		}

		.back-btn:hover {
			color: #0056b3;
		}

		/* Profile Picture */
		.profile-pic {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			object-fit: cover;
		}

		/* Send Button */
		#sendBtn {
			border-radius: 10px;
			margin-left: 10px;
		}
	</style>
	<!-- Favicon -->
	<link rel="icon" href="img/logo.png">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 shadow p-4 rounded">
    	<!-- Back Button -->
    	<a href="home.php" class="back-btn">&#8592;</a>

    	<!-- Chat Header -->
    	<div class="d-flex align-items-center">
    	   	<img src="uploads/<?=$chatWith['p_p']?>" class="profile-pic">
            <h3 class="display-4 fs-sm m-2">
               	<?=$chatWith['name']?> <br>
               	<div class="d-flex align-items-center" title="online">
               	    <?php if (last_seen($chatWith['last_seen']) == "Active") { ?>
               	        <div class="online"></div>
               	        <small class="d-block p-1">Online</small>
               	  	<?php } else { ?>
               	        <small class="d-block p-1">
               	         	Last seen: <?=last_seen($chatWith['last_seen'])?>
               	        </small>
               	  	<?php } ?>
               	</div>
            </h3>
    	</div>

    	<!-- Chat Box -->
    	<div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
    	    <?php if (!empty($chats)) {
                foreach($chats as $chat) {
                    if ($chat['from_id'] == $_SESSION['user_id']) { ?>
						<p class="rtext align-self-end border rounded p-2 mb-1">
						    <?=$chat['message']?> 
						    <small class="d-block"><?=$chat['created_at']?></small>
						    <span class="delete-btn" onclick="deleteMessage(<?=$chat['chat_id']?>, 'me')">Delete for me</span>
						    <span class="delete-btn" onclick="deleteMessage(<?=$chat['chat_id']?>, 'all')">Delete for everyone</span>
						</p>
                    <?php } else { ?>
					<p class="ltext border rounded p-2 mb-1">
					    <?=$chat['message']?> 
					    <small class="d-block"><?=$chat['created_at']?></small>
					</p>
                    <?php }
                }
    	    } else { ?>
               <div class="alert alert-info text-center">
				   <i class="fa fa-comments d-block fs-big"></i>
	               No messages yet, Start the conversation
			   </div>
    	   	<?php } ?>
    	</div>

    	<!-- Input Area -->
    	<div class="input-group mb-3">
    	   	<textarea cols="3" id="message" class="form-control" placeholder="Type a message..."></textarea>
    	   	<button class="btn btn-primary" id="sendBtn">
    	   	   	<i class="fa fa-paper-plane"></i>
    	   	</button>
    	</div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
    	// Scroll to the bottom of the chat box
    	var scrollDown = function(){
            let chatBox = document.getElementById('chatBox');
            chatBox.scrollTop = chatBox.scrollHeight;
    	}

    	scrollDown();

    	// Send message
    	$(document).ready(function(){
            $("#sendBtn").on('click', function(){
            	let message = $("#message").val();
            	if (message == "") return;

            	$.post("app/ajax/insert.php", {
            		message: message,
            		to_id: <?=$chatWith['user_id']?>
            	}, function(data, status){
                    $("#message").val("");
                    $("#chatBox").append(data);
                    scrollDown();
            	});
            });

            // Auto update last seen
            let lastSeenUpdate = function(){
            	$.get("app/ajax/update_last_seen.php");
            }
            lastSeenUpdate();
            setInterval(lastSeenUpdate, 10000);

            // Auto refresh chat
            let fetchData = function(){
            	$.post("app/ajax/getMessage.php", {
            		id_2: <?=$chatWith['user_id']?>
            	}, function(data, status){
                    $("#chatBox").append(data);
                    if (data != "") scrollDown();
            	});
            }
            fetchData();
            setInterval(fetchData, 500);
    	});

    	// Delete message
    	function deleteMessage(chatId, type) {
    		if (confirm("Are you sure you want to delete this message?")) {
    			$.post("app/ajax/deleteMessage.php", {
    				chat_id: chatId,
    				type: type
    			}, function(data, status){
    				location.reload(); // Refresh the page
    			});
    		}
    	}
    </script>
</body>
</html>
<?php
} else {
  	header("Location: index.php");
   	exit;
}
?>