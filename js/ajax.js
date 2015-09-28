$(document).ready(function() {
  
    $('#change_email').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'change_email',email: $('#cemail').val()}, function(data) {
            $('#mailErr').html(data);
        });
    });
    
    $('#change_password').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'change_password',new: $('#cnew').val(), current: $('#ccurrent').val()}, function(data) {
            $('#pwErr').html('<div class="alert">' + data + '</div>')
        });
    });
    
    $('#change_nickname').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'change_nickname',nickname: $('#cnickname').val()}, function(data) {
            $('#nickErr').html(data)
        });
    });
    
    $('#change_url').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'change_url',url: $('#curl').val()}, function(data) {
            $('#urlErr').html(data);
        });
    });
    
    $('#remove_url').click(function(e) {
        $.post('../core/process.php', {func: 'remove_url'}, function(data) {
            $('#curl').val('');
            $('#urlErr').html(data);
        });
    });
    
    $('#reply').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'reply', ticket: $.urlParam('id'), text: $('#rtext').val()}, function(data) {
            //alert(data);
            $.post('../core/process.php', {func: 'replies', ticket: $.urlParam('id')}, function(data) {
                $('#replies').empty();
                $('#replies').html(data);
                $('#rtext').val('');
            });
        });
    });
    
    $('#delete_account').submit(function(e) {
        e.preventDefault();
        var c = confirm('Are you sure you want to deactivate your account? This cannot be undone.');
        if(c) {
            $.post('../core/process.php', {func: 'delete_me'}, function(data) {
                location.reload();
            });
        } else {
          
        }
    });
    
    $('#add_department').submit(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'add_department', dpt: $('#dpt').val()}, function(data) {
            //alert(data);
           
            $.post('../core/process.php', {func: 'all_departments'}, function(data) {
                 $('#all_departments').empty();
                document.getElementById('all_departments').innerHTML += data;
                $('#dpt').val('');
            });
        });
       
    });
    
    $('#auth').submit(function(e) {
        e.preventDefault();
        if(document.getElementById("radio1").checked == true) {
            $.post('../core/process.php', {func: 'auth', email: $('#email').val(), password: $('#password').val(), type: 'returning_user'}, function(data) {
                if(data == 'success') {
                    console.log(data);
                    location.reload();
                } else {
                    $('#alerts').html('<div class="alert">' + data + '</div>');
                }
            });
        } else if(document.getElementById("radio2").checked == true) {
            $.post('../core/process.php', {func: 'auth', email: $('#email').val(), password: $('#password').val(), type: 'new_user'}, function(data) {
              if(data == 'success') {
                  console.log(data);
                  location.reload();
              } else {
                  $('#alerts').html('<div class="alert">' + data + '</div>');
              }
            });
          }
        
    });
    
    $('#create_ticket').submit(function(e) {
        e.preventDefault();
        
        $.post('../core/process.php', {func: 'create_ticket', subject: $('#subject').val(), department: $('#department').val(), message: $('#message').val()}, function(data) {
             
             var r = data.split(' ');
             if(r[0] == 'success') {
                location.href = '/ticket/?id=' + r[1];
             } else {
                $('#create_ticket_error').html(data);
             }
        });
    })
    
    if(document.getElementById('allow_self_delete') && document.getElementById('allow_signin') && document.getElementById('allow_register')) {
        $.post('../core/process.php', {func: 'get_settings'}, function(data) {
              var volgorde = ['allow_self_delete', 'allow_signin', 'allow_register', 'enable_protection'];
              var t = data.split(' ');
              for(var i = 0;i < t.length;i++) {
                  if(t[i] == 1) {
                      $('#' + volgorde[i]).prop('checked', true);
                  } else {
                      $('#' + volgorde[i]).prop('checked', false);
                  }
              }
        });
        
        $('#allow_self_delete').click(function() {
            if (!$(this).is(':checked')) {
                $.post('../core/process.php', {func: 'settings', functio: 'delete', status: 0}, function(data) {});
            } else {
                $.post('../core/process.php', {func: 'settings', functio: 'delete', status: 1}, function(data) {});
            }
        });
        
        $('#allow_signin').click(function() {
            if (!$(this).is(':checked')) {
                $.post('../core/process.php', {func: 'settings', functio: 'signin', status: 0}, function(data) {});
            } else {
                $.post('../core/process.php', {func: 'settings', functio: 'signin', status: 1}, function(data) {});
            }
        });
        
        $('#allow_register').click(function() {
            if (!$(this).is(':checked')) {
                $.post('../core/process.php', {func: 'settings', functio: 'register', status: 0}, function(data) {});
            } else {
                $.post('../core/process.php', {func: 'settings', functio: 'register', status: 1}, function(data) {});
            }
        });
        
        $('#enable_protection').click(function() {
            if (!$(this).is(':checked')) {
                $.post('../core/process.php', {func: 'settings', functio: 'protection', status: 0}, function(data) {});
            } else {
                $.post('../core/process.php', {func: 'settings', functio: 'protection', status: 1}, function(data) {});
            }
        });
    }
    
    $('#delete_dpt').click(function(e) {
      e.preventDefault();
        var c = confirm("Are you sure you want to delete this deparment?");
        if(c) {
          $.post('../core/process.php', {func: 'delete_dpt', department: $.urlParam('department')}, function(data) {
            if(data == 'success')
              location.href = 'site_management.php?success';
          });  
        }
        
    });
    
    $('#dptform').submit(function(e) {
        e.preventDefault();
        
        $.post('../core/process.php', {func: 'update_dpt', department: $.urlParam('department'), update: $('#dept_new').val()}, function(data) {
            $('#dptERR').html(data);
            $('#delete_dpt').html('Delete "' + $('#dept_new').val() + '"');
        });
    });
    
    $('#admin_update_nickname').click(function(e) {
        var c = confirm("Are you sure you want to update this user's details?");
        if(c) {
          $.post('../core/process.php', {func: 'admin_update_nickname', user: $.urlParam('user'), update: $('#nickname').val()}, function(data) {
              if(data == 'success')
                  $('#errors').html('<div class="alert success">The user\'s <b>nickname</b> has been updated successfully.</div>');
          });
        }
        
    });
    
    $('#admin_update_email').click(function(e) {
        var c = confirm("Are you sure you want to update this user's details?");
        if(c) {
          $.post('../core/process.php', {func: 'admin_update_email', user: $.urlParam('user'), update: $('#email').val()}, function(data) {
              if(data == 'success')
                $('#errors').html('<div class="alert success">The user\'s <b>email address</b> has been updated successfully.</div>');
          });
        }
    });
    
    $('#make_admin').click(function(e) {
        var c = confirm("Are you sure you want to make this user an administrator?");
        if(c) {
            $.post('../core/process.php', {func: 'make_admin', user: $.urlParam('user')}, function(data) {
              if(data == 'success')
                $('#errors').html('<div class="alert success">This user is now an administrator.</div>');
                $('#make_admin').html('User is admin');
                $('#make_admin').attr('id', '');
            });
        }
    });
    
    $('#change_access').click(function() {
        $.post('../core/process.php', {func: 'change_block', user: $.urlParam('user')}, function(data) {
              if($('#change_access').html() == 'Access denied') {
                  $('#change_access').html('Don\'t allow access');
              } else {
                  $('#change_access').html('Access denied');
              }
        });
    });
    
    $('#no_longer_help').click(function(e) {
        e.preventDefault();
        $.post('../core/process.php', {func: 'no_longer_help', ticket: $.urlParam('id')}, function(data) {
            if(data == 'success'){
                location.reload();
            }
        });
    });
    
    $('#close_ticket').click(function(e) {
        $.post('../core/process.php', {func: 'close_ticket', ticket: $.urlParam('id')}, function(data) {
            if(data == 'success'){
                location.reload();
            }
        }); 
    });
    
});

$.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	return results[1] || 0;
}
