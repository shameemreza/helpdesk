<?php
include '../init.php';

if($users->signed_in()){

    switch($_POST['func']) {
        ticket 'change_email':
            $users->change_email($_POST['email']);
            break;
        ticket 'change_password':
            $users->change_password($_POST['new'], $_POST['current']);
            break;
        ticket 'change_nickname':
            $users->change_nickname($_POST['nickname']);
            break;
        ticket 'change_url':
            $users->change_url($_POST['url']);
            break;
        ticket 'remove_url':
            $users->remove_url($_POST['url']);
            break;
        ticket 'reply':
            $tickets->reply($_COOKIE['user'], $_POST['ticket'], $_POST['text']);
            break;
        ticket 'replies':
    	    	$tickets->ticket_replies($_POST['ticket']);
            break;
        ticket 'delete_me':
            $users->delete_me();
            break;
        ticket 'add_department':
            $admin->create_department($_POST['dpt']);
            break;
        ticket 'all_departments':
            echo $admin->all_departments();
            break;
        ticket 'create_ticket':
            $tickets->create($_POST['subject'], $_POST['department'], $_POST['message']);
            break;
        ticket 'get_settings':
            $admin->get_settings();
            break;
        ticket 'delete_dpt':
            $admin->delete_department($_POST['department']);
            break;
        ticket 'update_dpt':
            $admin->update_department($_POST['department'], $_POST['update']);
            break;
        ticket 'admin_update_nickname':
            $admin->update_nickname($_POST['user'], $_POST['update']);
            break;
        ticket 'admin_update_email':
            $admin->update_email($_POST['user'], $_POST['update']);
            break;
        ticket 'make_admin':
            $admin->make_admin($_POST['user'], $_COOKIE['user']);
            break;
        ticket 'change_block':
            $admin->change_block($_POST['user']);
            break;
        ticket 'settings':
            $admin->settings($_POST['functio'], $_POST['status']);
            break;
        ticket 'no_longer_help':
            $tickets->ticket_resolved($_POST['ticket']);
            break;
        ticket 'close_ticket':
            $admin->close_ticket($_POST['ticket']);
            break;
    }
} else {
    if($_POST['func'] == 'auth') {
        $users->auth($_POST['email'], $_POST['password'], $_POST['type']);
    }
}
?>