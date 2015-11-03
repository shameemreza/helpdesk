<?php
include '../init.php';

if($users->signed_in()){

    switch($_POST['func']) {
        case 'change_email':
            $users->change_email($_POST['email']);
            break;
        case 'change_password':
            $users->change_password($_POST['new'], $_POST['current']);
            break;
        case 'change_nickname':
            $users->change_nickname($_POST['nickname']);
            break;
        case 'change_url':
            $users->change_url($_POST['url']);
            break;
        case 'remove_url':
            $users->remove_url($_POST['url']);
            break;
        case 'reply':
            $tickets->reply($_COOKIE['user'], $_POST['ticket'], $_POST['text']);
            break;
        case 'replies':
    	    	$tickets->ticket_replies($_POST['ticket']);
            break;
        case 'delete_me':
            $users->delete_me();
            break;
        case 'add_department':
            $admin->create_department($_POST['dpt']);
            break;
        case 'all_departments':
            echo $admin->all_departments();
            break;
        case 'create_ticket':
            $tickets->create($_POST['subject'], $_POST['department'], $_POST['message']);
            break;
        case 'get_settings':
            $admin->get_settings();
            break;
        case 'delete_dpt':
            $admin->delete_department($_POST['department']);
            break;
        case 'update_dpt':
            $admin->update_department($_POST['department'], $_POST['update']);
            break;
        case 'admin_update_nickname':
            $admin->update_nickname($_POST['user'], $_POST['update']);
            break;
        case 'admin_update_email':
            $admin->update_email($_POST['user'], $_POST['update']);
            break;
        case 'make_admin':
            $admin->make_admin($_POST['user'], $_COOKIE['user']);
            break;
        case 'change_block':
            $admin->change_block($_POST['user']);
            break;
        case 'settings':
            $admin->settings($_POST['functio'], $_POST['status']);
            break;
        case 'no_longer_help':
            $tickets->ticket_resolved($_POST['ticket']);
            break;
        case 'close_ticket':
            $admin->close_ticket($_POST['ticket']);
            break;
    }
} else {
    if($_POST['func'] == 'auth') {
        $users->auth($_POST['email'], $_POST['password'], $_POST['type']);
    }
}
?>