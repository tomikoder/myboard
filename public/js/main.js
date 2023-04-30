$(function() {

    var debug = false;
    var open_form = null;
    var elem_to_update, elem_to_update2, elem_to_update3, new_post, new_post_raw_html, new_post_raw_html2, text;
    var block = false;
    var curr_notify = BD['notify_count'];
    var errors;

    key = "Your pusher key";

    var pusher = new Pusher(key, {cluster: 'eu',
                                                     encrypted: true,
                                                     authEndpoint: '/broadcasting/auth',
                                                     auth: {
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                        }
                                                    }});
    function validate_input1(string) {
        if (string == "") {
            if (!errors.includes("Field is empty")) errors.push("Field is empty");
        } 
        if (string.trim().length != string.length) {
            if (!errors.includes("Password can't have whitespaces.")) errors.push("Password can't have whitespaces.");
        }
        if (string.length < 6) {
            if (!errors.includes("Password must have min 6 characters.")) errors.push("Password must have min 6 characters.");
        }
    }

    $("#forget").on('click', function(event) {
        event.preventDefault();
        $("#loginmodal").find(".close").trigger("click");
        $("#forgetpassmodal").modal().show();  
     });

     $("#forgetpassform").on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                alert("Link was sended. Check email.");
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Bad email");
            }
        });

     });



    $(document).on('submit', '#change_pass_form', function(event) {
        event.preventDefault();
        $("#change_pass_errors").empty();
        errors = [];
        old_pass =  $(this).find("#old_pass").val();
        new_pass =  $(this).find("#new_pass").val();
        new_pass2 = $(this).find("#new_pass_retry").val();
        input = [old_pass, new_pass, new_pass2];

        input.forEach(validate_input1);

        if (new_pass.length != new_pass2.length) {
            errors.push("The passwords have different lengths.");
        } 
        if (new_pass != new_pass2) {
            errors.push("The passwords are different.");
        } 

        
        if (errors.length > 0) {
            for (let i in errors) {
                $("#change_pass_errors").append('<p style="color:red;">' + errors[i] + '</p>');
            }
            return;
        }

                
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                alert(data["msg"]);
                alert("Password was changed.");
            },
            error: function (xhr, textStatus, errorThrown) {
                alert("Bad password");
            }
        });
    });                                                
    
    $("#sendmessageform").on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                alert("Message was send.");
                $("#sendmessagemodal").find(".close").trigger("click");;  
            }
        });
     });
                                                                                                         
    $("#notifys").on('click', function(event) {
        if (!('user_id' in BD)) {
            event.preventDefault();
            $('#loginmodal').modal('show');
        }
    });                                                 
   
    var channel = pusher.subscribe('private-channel-' + BD['user_id']);
    channel.bind('pusher:subscription_succeeded', function(members) {});
    channel.bind('test', function(data) {
        curr_notify++; 
        $("#notifys").find("number").text(curr_notify);

        alert("You have new notification.");
    });

    function sleep (time) {
        return new Promise((resolve) => setTimeout(resolve, time));
      }
    
    loged_users_list = $("#loged_users_list");
    
  
    var presence_channel = pusher.subscribe('presence-online');
    presence_channel.bind('pusher:subscription_succeeded', function(members) {
        count = presence_channel.members.count;
        $("#online_users").text(count);
        presence_channel.members.each(function (member) {
            a = `<li id=user_list${member.id} class="list-group-item"><a href="/panel/${member.id}">${member.info.name}</a></li>`;
            loged_users_list.append(a);
        });
    });
    
    presence_channel.bind("pusher:member_removed", (member) => {

        sleep(1000).then(() => {
            count--;
            $(document).find("#user_list" + member.id).remove();
        });
      });

    presence_channel.bind("pusher:member_added", (member) => {
        sleep(1000).then(() => {
            count++;
            $("#online_users").text(count);
            a = `<li id=user_list${member.id} class="list-group-item"><a href="panel/${member.id}">${member.info.name}</a></li>`;
            loged_users_list.append(a);
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
    $("#search_button").on('click', function(event) {
        event.preventDefault();
        var search_data = $("#search_input").val();        
        window.location.replace('/search/?q=' + search_data); 
    });

    $.get("/js/post.html", function(html_string) {
        new_post_raw_html = html_string;
     });

     $.get("/js/sub_post.html", function(html_string) {
        new_post_raw_html2 = html_string;
     });

     $(document).on('click', ".like_post", function(event) {
        event.preventDefault();
        if (!('user_id' in BD)) {
            $('#loginmodal').modal('show');
        }

        this_tag = $(this);
        is_liked = this_tag.attr("liked");
        is_liked = parseInt(is_liked); 
        $.ajax({
            url: $(this).attr('href'),
            type: "POST",
            dataType: 'json',
            data: {"liked": is_liked},
            success: function (data) {
                if (is_liked) {
                    this_tag.attr("liked", 0);
                    this_tag.find("i").removeClass("fa-arrow-down").addClass("fa-arrow-up");
                    curr_num = parseInt(this_tag.find("number").text()) -1;
                    this_tag.find("number").text(curr_num);
                }
                else {
                    this_tag.attr("liked", 1);
                    this_tag.find("i").removeClass("fa-arrow-up").addClass("fa-arrow-down");
                    curr_num = parseInt(this_tag.find("number").text()) +1;
                    this_tag.find("number").text(curr_num);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        })
     }); 

    $(".comment_reply_form").on('submit', function(event) {
        parent = $(this).parent();
        text = $(this).find(".sub_comm").val();
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                open_form.val("");
                new_post = $(new_post_raw_html2);
                new_post.find(".comment_author").text(data["comment_auth"] + " ").attr("href", "/panel/" + data['user_id']);
                new_post.find(".comment_author").parent().append('<small class="comment_date">' + data["comment_date"] + "</small>");
                new_post.find(".comment_text").text(text);
                new_post.find(".del_comment").attr("href", "/delete/comment" + data["comment_id"]);
                new_post.find(".edit_comment").attr("href", "/edit/comment" + data["comment_id"]);
                new_post.find(".comment_edit_form").attr("action", "/edit/comment" + data["comment_id"]);
                open_form.toggle();
                elem_to_update.text("Reply");
                elem_to_update.removeClass("hide_reply_comm_form");
                elem_to_update.addClass("reply_comment");
                open_form = null;
                parent.find(".sub_comm").val("");
                parent.append(new_post);    
            }
        });
    }); 
 
    $("#commentform").on('submit', function(event) {
        event.preventDefault();
        text = $(this).find("#comm").val();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                new_post = $(new_post_raw_html);
                new_post.find(".comment_author").text(data["comment_auth"] + " ").attr("href", "/panel/" + data['user_id']);
                new_post.find(".comment_author").parent().append('<small class="comment_date">' + data["comment_date"] + "</small>");
                new_post.find(".comment_text").text(text);
                new_post.find(".del_comment").attr("href", "/delete/comment" + data["comment_id"]);
                new_post.find(".edit_comment").attr("href", "/edit/comment" + data["comment_id"]);
                new_post.find(".comment_edit_form").attr("action", "/edit/comment" + data["comment_id"]);
                $("#comments hr").after(new_post);
                $("#commentform #comm").val("");
            },
            error: function (xhr, textStatus, errorThrown) {
                 if (xhr.status == 429) {
                    alert("Too many comments! Max one per minute.")
                 }
            },
            complete: function () {
            }
        });
    });

    $(document).on('click', '.reply_comment', function(event) {
        if (block == true) return;
        block = true;
        event.preventDefault();
        $(this).removeClass("reply_comment");
        $(this).addClass("hide_reply_comm_form");
        $(this).text("Hide");
        this_tag = $(this);
        parent = $(this).parent().parent();
        form = parent.find(".comment_reply_form:first");
        
        if (open_form != null) {
            open_form.toggle();
            if (elem_to_update.hasClass("hide_edit_comm_form")) {
                elem_to_update.text("Edit");
                elem_to_update.removeClass("hide_edit_comm_form");
                elem_to_update.addClass("edit_comment");
            }
            else {
                elem_to_update.text("Reply");
                elem_to_update.removeClass("hide_reply_comm_form");
                elem_to_update.addClass("reply_comment");
            }
        }
        open_form = form;
        elem_to_update = this_tag;
        form.show();
        block = false;
    });

    $(document).on('click', '.edit_comment', function(event) {
        if (block == true) return;
        block = true;
        event.preventDefault();
        $(this).removeClass("edit_comment");
        $(this).addClass("hide_edit_comm_form");
        $(this).text("Hide");
        this_tag = $(this);
        parent = $(this).parent().parent();
        text_tag = parent.find(".comment_text:first");
        date_tag = parent.find(".comment_date:first");
        form = parent.find(".comment_edit_form:first");
        
        if (open_form != null) {
            open_form.toggle();
            if (elem_to_update.hasClass("hide_edit_comm_form")) {
                elem_to_update.text("Edit");
                elem_to_update.removeClass("hide_edit_comm_form");
                elem_to_update.addClass("edit_comment");
            }
            else {
                elem_to_update.text("Reply");
                elem_to_update.removeClass("hide_reply_comm_form");
                elem_to_update.addClass("reply_comment");
            }
        }

        elem_to_update = this_tag;
        elem_to_update2 = text_tag;
        elem_to_update3 = date_tag;
        open_form = form;  
        comment_txt = parent.find("p:first").text(); /* 1 */
        form.find("textarea").val(comment_txt);
        form.show();
        block = false;
    });


    $(document).on('submit', '.comment_edit_form', function(event) {
        block = true;
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
                elem_to_update.text("Edit");
                elem_to_update2.text(data['comm']);    
                elem_to_update3.text(data['new_date']);
                open_form.toggle();
                elem_to_update.removeClass("hide_edit_comm_form");
                elem_to_update.addClass("edit_comment");
                open_form = null;
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {
                block = false;
            }
        });
    });

    $(document).on('click', '.hide_edit_comm_form', function(event) {
        if (block ==  true) return;
        block = true;
        event.preventDefault();
        $(this).removeClass("hide_edit_comm_form");
        $(this).addClass("edit_comment");
        $(this).text("Edit");
        open_form.toggle();
        open_form.val("");
        open_form = null;
        elem_to_update = null;
        block = false;
    });

    $(document).on('click', '.hide_reply_comm_form', function(event) {
        if (block ==  true) return;
        block = true;
        event.preventDefault();
        $(this).removeClass("hide_reply_comm_form");
        $(this).addClass("reply_comment");
        $(this).text("Reply");
        open_form.toggle();
        open_form.val("");
        open_form = null;
        elem_to_update = null;
        block = false;
    });

    $(document).on('click', '.del_comment', function(event) {
        event.preventDefault();
        parent = $(this).parent().parent();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function (data) {
                parent.remove();
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    });

    $("#redir_to_regi_mod").on('click', function(event) {
        event.preventDefault();
        $('#loginmodal').modal('toggle');
        $('#signupmodal').modal('show');
    });

    $("#friends").on('click', function(event) {
        event.preventDefault();
        if (!('user_id' in BD)) {
            $('#loginmodal').modal('show');
        }
    });

    $("#your_posts, #messages").on('click', function(event) {
        if (!('user_id' in BD)) {
            event.preventDefault();
            $("#loginmodal").modal('show');        
        }
    });

    $('.edit_post').on('click', function(event) {
        event.preventDefault();
        link = $(this).attr('href');
        title = $(this).parent().parent().find(".post_title").text();
        text  = $(this).parent().parent().find(".post_text").text();
        form = $("#editpostform");
        form.attr('action', link);
        form.find("#title").val(title);
        form.find("#text").val(text);    
        $('#editpost').modal('show');
    });

    $('#editpostform').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: "POST",
            dataType: 'json',
            success: function (data) {
                location.reload();
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    });


    $('.del_post').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            data: {},
            type: "POST",
            dataType: 'json',
            success: function (data) {

                document.location.href="/";
            },
            error: function (xhr, textStatus, errorThrown) {
            }
        });
    });

    $('#loginform').on('submit', function(event) {
        form = $(this);
        event.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
    
                    parseresp = JSON.parse(xhr.responseText)["errors"];
                    form.find("#login_errors").text(parseresp);
                }
            });
    });

    $('#signupform').on('submit', function(event) {
        form = $(this);
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: "POST",
            dataType: 'json',
            success: function (data) {
                location.reload();
            },
            error: function (xhr, textStatus, errorThrown) {
                errors = JSON.parse(xhr.responseText);
                alert(errors);
                error_txt = "";
                for (e in errors) {
                    error_txt += errors[e] + "\n";
                }
                form.find("#register_errors").text(error_txt);
            }
        });
    });
    
});