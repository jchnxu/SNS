<div id="update-password-modal" title="修改密码">
    <div class="error-message"></div>
  <form>
  <fieldset>
    <label for="password">原密码</label>
    <input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" />
    <label for="new_password">新密码</label>
    <input type="password" name="new_password" id="new-password" value="" class="text ui-widget-content ui-corner-all" />
    <label for="password">新密码确认</label>
    <input type="password" name="new_password_confirm" id="new-password-confirm" value="" class="text ui-widget-content ui-corner-all" />
  </fieldset>
  </form>
</div>

<div id="settings-outer" class="container">
    <div id="user-profile">
        <div class="image-uploader">
            <img id="user-avatar" src="<?php $url = trim($this->session->userdata('user_avatar_url')); echo $url === '' ? base_url('image/smiley.jpg') : $url; ?>" />
            <div id="upload-avatar-button" class="image-upload-button"></div>
            <div class="hide-child">
                <input type="file" id="fileupload-avatar" />
            </div>                
        </div>
        <div id="user-nickname-wrapper">
            <label>昵称</label>
            <input id="user-nickname"name="user-nickname" value="<?php echo $this->session->userdata('user_nickname'); ?>" >
            <div id="update-nickname-button">修改</div>
        </div>
        <div id="user-password-wrapper">
            <div id="update-password-button">修改密码</div>
        </div>
    </div>
    <div id="sns-management">
    </div>
</div>

<!-- modals -->
<script type="text/javascript">
</script>

<!-- file upload dependencies -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/fileupload/vendor/jquery.ui.widget.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fileupload/jquery.iframe-transport.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fileupload/jquery.fileupload.js" ></script>
<script type="text/javascript">
$(function(){

    // fileupload for user avatar 
    $("#upload-avatar-button").click(function(){
        $("#fileupload-avatar").trigger("click");
    });
    $("#fileupload-avatar").fileupload({
        url: "<?php echo base_url(); ?>ajax/do_upload_avatar",
        formData:{user_id: <?php echo $this->session->userdata['user_id'] ?>},
        dataType: 'json',
        done: function(e ,data) {
            console.log(data);
            $.each(data.result.files, function (index, file) {
                if ('error' in file){
                    alert('上传失败了。。。');
                }
                else {
                    $("#user-avatar").attr('src', "<?php echo base_url(); ?>" + file.url);
                }
            });
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


    // update password modal
    $( "#update-password-modal" ).dialog({
        autoOpen: false,
        height: 300,
        width: 400,
        modal: true,
        buttons: {
            "更改": function() {
                dialog = this; 
                $.post(
                    "<?php echo base_url('ajax/update_password'); ?>",
                    {
                        password: $("#password").val(),
                        new_password: $("#new-password").val(),
                        new_password_confirm: $("#new-password-confirm").val(),
                        user_id: <?php echo $this->session->userdata('user_id'); ?>
                    },
                    function(data, status) {
                        if (data.errno == 0) {
                            alert(data.msg);
                            $(dialog).dialog("close");
                        }
                        else {
                            $("#update-password-modal").find(".error-message").html(data.errmsg);
                        }
                        
                    },
                    "json"
                );
            },
            "取消": function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            // clean the form
            $("#password").val("");
            $("#new-password").val("");
            $("#new-password-confirm").val("");
            $("#update-password-modal").find(".error-message").html("");
        }
    });

    // update password button
    $( "#update-password-button" )
      .button()
      .click(function() {
        $( "#update-password-modal" ).dialog( "open" );
      });



    // update nickname
    $("#update-nickname-button")
        .button()
        .click(function() {
            $.post(
                "<?php echo base_url('ajax/update_nickname'); ?>",
                {
                    new_nickname: $("#user-nickname").val(),
                    user_id: <?php echo $this->session->userdata('user_id'); ?>
                },
                function(data, status) {
                    if (data.errno == 0) {
                        alert(data.msg);
                    }
                    else {
                        alert(data.errmsg);
                        // fall back to original nickname
                        $("#user-nickname").val(data.original_nickname);
                        $("#user-nickname").focus();
                    }
                    
                },
                "json"
            );
        });
})

</script>
