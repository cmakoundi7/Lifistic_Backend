<?php
	$endpoint = 'send_invitation';
	// ********************************************************************************
	// INCLUDE HEADERS + SECURE 
	include '../headers/tools.h';
	include '../headers/sessions.h';
	include '../headers/functions/sessionCheck.h';	
	include '../headers/functions/notifications.h';	
	include '../poc/token-manager.php';
	include '../poc/notification-manager.php';

	// ********************************************************************************
	// SESSION + SECURITY	
	session_start();
	if(!php_req_valid()) return;
	secure_inputs();
	$userid = checkSession($_SESSION[$GLOBALS['APP']."USERID"], $endpoint);	
	
	// ********************************************************************************
	// GET & CHECK POSTED VAR		
	
	$user = "";
	
	if(isset($_POST['user']))
		$user = $_POST['user'];

	if(isset($_POST['avatar']))
		$profilePicture = $_POST['avatar'];		
		
	if(isset($_POST['pseudo']))
		$pseudo = $_POST['pseudo'];			
			 
	if($user == ""){
		debug("send_invitation","warning","user is empty");
		return;
	}
	
	$user = strtolower(substr($user,0,256));

	// ********************************************************************************
	// INTEGRITY CHECK	
	if($user == $userid){
		$answer['invitation_sent'] = false;
		debug("send_invitation","warning","self invitation impossible");
		echo json_encode($answer);
		return;
	}
	
	// ********************************************************************************
	// GET FRIEND PENDING INVITATIONS, FRIEND LIST & BLOCKED LIST	
	$conn = connect_w1();
	w_tx_start($conn);
	
	$sql = "SELECT NAME_S as NAME, DATA_S->'$.\"pending_invites\"' as PENDING_INVITES, DATA_S->'$.\"friends\"' as FRIENDS, DATA_S->'$.\"blocked\"' as BLOCKED FROM LIFISTIC_1.LIFISTIC_USERS WHERE USER_S = '$user' AND STATUS_ID = 1"; 

	$res = mysqli_query($conn,$sql);
	if(!$res){
		$answer['invitation_sent'] = false;
		debug("send_invitation","warning","mysql error: ".mysqli_error($conn));
		echo json_encode($answer);
		return;
	}
	
	$row = mysqli_fetch_array($res);
	
	if(!$row){
		debug("send_invitation","warning","user not found");
		$answer['invitation_sent'] = false;
		echo json_encode($answer);
		return;
	}	
	
	$pending_invites = json_decode($row['PENDING_INVITES']);
	
	$friends = json_decode($row['FRIENDS'],true);
	
	$blocked = json_decode($row['BLOCKED'],true);
	
	$userName = $row['NAME'];
	
	// ********************************************************************************
	// CHECK IF FRIEND LIST IS POPULATED
	
	// le listing d'amis vide est desormais un array sans données [], faire le checking la dessus
	
	if($friends !=null)
		if(in_array($userid,$friends)){
			tx_end($conn);
			$answer['invitation_sent'] = false;
			debug("send_invitation","warning","already a friend");
			echo json_encode($answer);
			return;
		} 			
	
	if($blocked !=null)
		if(in_array($userid,$blocked)){
			tx_end($conn);
			$answer['invitation_sent'] = false;
			debug("send_invitation","warning","blocked user");
			echo json_encode($answer);
			return;
		} 		
	
	// ********************************************************************************
	// CHECK IF THERE ARE INVITATIONS PENDING
	
	if($pending_invites == null || $pending_invites == ""){
		// IF NO CREATE AN ARRAY WITH USERID
		$pending_invites = array($userid);
	
	} else {

		// IF YES CHECK USER EXISTENCE THEN ADD IN FRIEND PENDING LIST
		if(!in_array($userid, $pending_invites))
			$pending_invites[] = $userid;
			else {
			$answer['invitation_sent'] = true;	
			tx_end($conn);
			$answer['token'] = get_token();
	
			echo json_encode($answer);
			debug("action-verbose-2","info",session_id().": ".json_encode($answer));
	
			return;
			}
		
	}

	$pending_invites = json_encode($pending_invites, JSON_UNESCAPED_UNICODE);	
	
	// ********************************************************************************
	// UPDATE FRIEND PENDING INVITATIONS LIST
	
	$sql = "UPDATE LIFISTIC_1.LIFISTIC_USERS SET DATA_S = JSON_SET(DATA_S, '$.\"pending_invites\"', CAST('$pending_invites' AS JSON)) WHERE USER_S = '$user' AND STATUS_ID = 1"; 
	
	$res = mysqli_query($conn,$sql);
	
	if(!$res){
		$answer['invitation_sent'] = false;
		debug("send_invitation","warning","mysql error: ".mysqli_error($conn));
	} else {
		$answer['invitation_sent'] = true;	
		$answer['token'] = get_token();
	}
	tx_end($conn);

	
	// ********************************************************************************
	// NOTIFICATIONS
	$notification = createNotification($userid, $user, $profilePicture, false, "invitation", $endpoint);
	sendInAppNotification($notification, $user, $endpoint);
	sendFirebaseNotification($userid, $user, ucfirst($pseudo)." vous a envoyé une invitation.", $endpoint, "invitation");	
	
	// ********************************************************************************
	// WRAP & SEND
	$answer['results'] = true;	
	echo json_encode($answer);
	debug("action-verbose-2","info",session_id().": ".json_encode($answer));
	
?>