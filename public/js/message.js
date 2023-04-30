
$(function() {

    elem = document.getElementById("msgs");
    elem.scrollTo(0, elem.scrollHeight);
    var new_post_raw_html3;
    var count;

    key = "Your pusher key";

    var pusher = new Pusher(key, {cluster: 'eu',
                                                     encrypted: true,
                                                     authEndpoint: '/broadcasting/auth',
                                                     auth: {
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                        }
                                                    }});
    addr = `presence-chat.${BD["msg_code"]}`;                                              
    var presence_channel = pusher.subscribe(addr);
    presence_channel.bind('pusher:subscription_succeeded', function(members) {
        count = presence_channel.members.count;
    });

    presence_channel.bind("pusher:member_removed", (member) => {count--;});
    presence_channel.bind("pusher:member_added", (member) => {count++;});


    $.get("/js/new_message.html", function(html_string) {
        new_post_raw_html3 = html_string;
     });


    presence_channel.bind("test", function(data) {
        new_post = $(new_post_raw_html3);
        new_post.find(".post_text").text(data["text"]);
        new_post.find("from").text(data["from"]); 
        new_post.find("small").text(data["date"]);
        $("#msgs").append(new_post);
        elem.scrollTo(0, elem.scrollHeight);
    });
    
    $("#send_reply").on('submit', function(event) {
        event.preventDefault();
        var data = $(this).serializeArray();
        if (count == 2) data.push({name: 'flag', value: null});        
        else            data.push({name: 'flag', value: BD["user_id"]}); 
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            dataType: 'json',
            data: data,
            success: function (data) {
                alert(data['msg']);
                $("#send_reply").find(".close").trigger("click"); 
            }
        });
     });
                                             
});

