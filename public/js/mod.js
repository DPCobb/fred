/**
 *  Daniel Cobb
 *  Nmbley v3.0
 *  1-24-17
 *  Mod.js - used for mod/admin options
 *
 */

$(document).ready(function(){
    // Display the pagination for the mod view
    $('.pagination').css('display', 'inline-block');
    var alert = $('#mod-alert');

    // get mods returns a list of moderators to disable the add mod button
    function getMods(){
        var category = $('#handle').val()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"GET",
            url:"/mod/mods/"+category,
            success: function(response){
                // enable/disable adding as mod
                $('.addmod').removeClass('disabled')
                var total = response.length
                var i = 0
                for(i; i < total; i++){
                    var target = $('.addmod[data-id="'+response[i].userId+'"]')
                    target.addClass('disabled')
                    i++
                }
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // retrieves the ban list
    function getBans(){
        var category = $('#handle').val()
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"GET",
            url:"/mod/bans/"+category,
            success: function(response){
                $('.mod-ban').removeClass('disabled')
                var total = response.length
                var i = 0
                // change button for ban or unban
                for(i; i < total; i++){
                    var target = $('.mod-ban[data-id="'+response[i].userId+'"]')
                    target.removeClass('mod-ban').addClass('mod-unban').text('Remove Ban')
                    i++
                }
                $('.mod-unban').on('click', function(e){
                    var user = $(this).attr('data-id');
                    var category = $('#handle').val();
                    unbanUser(user, category);
                });
            },
            error: function (response) {
                console.log(response)
            }
        });
    }


    // function with all the pages listeners
    function listeners(){
        $('.unflag').on('click', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            removeFlag(id);
        });
        $('.addmod').on('click', function(e){
            e.preventDefault();
            var user = $(this).attr('data-id');
            var target = $(this).closest('article');
            var cat = $(this).attr('data-cat');
            addMod(user, target, cat);
        });
        $('.removemod').on('click', function(e){
            e.preventDefault();
            var user = $(this).attr('data-id');
            var target = $("div[data-key='"+user+"']");
            var cat = $(this).attr('data-cat');
            removeMod(user, target, cat);
        });
        $('#modpost').on('click', function(e){
            e.preventDefault();
            var title = $(this).closest('.mod-post-form').find('#title').val()
            var post = $(this).closest('.mod-post-form').find('#post').val()
            var cat = $(this).closest('.mod-post-form').find('#cat').attr('data-catId')
            console.log(title, post, cat)
            modPost(title, post, cat)
        });
        $('.mod-ban').on('click', function(e){
            e.preventDefault();
            var user = $(this).attr('data-id');
            var category = $('#handle').val();
            banUser(user, category);
        });
        $('.mod-unban').on('click', function(e){
            e.preventDefault();
            var user = $(this).attr('data-id');
            var category = $('#handle').val();
            unbanUser(user, category);
        });
        // deletes the post
        $('.mod-del').on('click', function(e){
            e.preventDefault();
            var post = $(this).attr('data-id');
            var postid = post
            var modal = $('.reason-modal')
            var op = $('article[data-key="'+post+'"]')
            var user = op.find('a[class="modoptions"]').attr('data-id');
            modal.fadeIn();
            modal.find('#postid').val(postid);
            modal.find('#user').val(user);
            modDel(post)
        });
        // sends a message to user about post being deleted
        $('#mod-submit-reason').on('click',function(e){
            e.preventDefault()
            sendDelMessage();
        });
        $('.mod-msg').on('click', function(e){
            e.preventDefault()
            var msg = $('.message-modal');
            var target = $(this).closest('article');
            var user = $(this).attr('data-id');
            var name = target.find('.modoptions').attr('data-name');
            msg.find('#user-name').html(name)
            msg.find('#recieve').val(user)
            msg.fadeIn();
            $('.cancel').on('click', function(e){
                e.preventDefault();
                $('.message-modal').fadeOut();
            });
            $('#message-sub').on('click', function(e){
                e.preventDefault();
                var form = $('.message-form')
                var reciever = form.find('#recieve').val()
                var subject = form.find('#subject').val()
                var text = form.find('#message').val()
                modSendMsg(reciever, subject, text);
            })
        });
        $('.messagemod').on('click', function(e){
            e.preventDefault()
            var msg = $('.message-modal');
            var target = $(this);
            var user = target.attr('data-id');
            var name = target.attr('data-name');
            msg.find('#user-name').html(name)
            msg.find('#recieve').val(user)
            msg.fadeIn();
            $('.cancel').on('click', function(e){
                e.preventDefault();
                $('.message-modal').fadeOut();
            });
            $('#message-sub').on('click', function(e){
                e.preventDefault();
                var form = $('.message-form')
                var reciever = form.find('#recieve').val()
                var subject = form.find('#subject').val()
                var text = form.find('#message').val()
                modSendMsg(reciever, subject, text);
            })
        });
        $('.mod-del-com').on('click',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var target = $(this).closest('div');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"POST",
                url:"/mod/comment/delete",
                data:{
                    'id': id
                },
                success: function(response){
                    target.hide()
                },
                error: function (response) {
                    console.log(response)
                }
            });
        })

    }

    // call functions
    listeners();
    getMods();
    getBans();

    // removes flag from reported post
    function removeFlag(id){
        var post = id;
        var target = $("article[data-key='"+id+"']");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/unflag",
            data:{
                'id': post
            },
            success: function(response){
                // remove the flagged class
                target.removeClass('flagged');
                target.find('.unflag').hide();
                alert.html('Flag Removed!').show();
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // add a moderator
    function addMod(user, target, cat){
        var user = user;
        var target = target;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/addmod",
            data:{
                'id': user,
                'catId': cat
            },
            success: function(response){
                // disable add mod
                target.find('.addmod').addClass('disabled');
                alert.html('Moderator Added!').show();
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // remove moderator
    function removeMod(user, target, cat){
        var target = target;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/removemod",
            data:{
                'user': user,
                'cat': cat
            },
            success: function(response){
                // hide the moderator
                target.hide();
                alert.html('Moderator Removed!').show();
                getMods();
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // post as a mod
    function modPost(title, post, cat){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/modpost",
            data:{
                'title': title,
                'post': post,
                'cat': cat
            },
            success: function(response){
                alert.html('Post Added!').show();
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // ban a user
    function banUser(user, category){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/banuser",
            data:{
                'user': user,
                'cat': category
            },
            success: function(response){
                alert.html('User Banned!').show();
                // getBans to update buttons
                getBans();
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // unban user
    function unbanUser(user, category){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/unbanuser",
            data:{
                'user': user,
                'cat': category
            },
            success: function(response){
                alert.html('The Ban was lifted!').show();
                // getBans to update buttons
                getBans();
                var target = $('.mod-unban[data-id="'+user+'"]')
                target.html('<i class="fa fa-ban" aria-hidden="true"></i> Ban User');
                target.removeClass('mod-unban').addClass('mod-ban');
                listeners()
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // del a post as a mod
    function modDel(post){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/delpost",
            data:{
                'post': post
            },
            success: function(response){
                // disable delete
                alert.html('There goes that post!').show();
                var target = $('.mod-del[data-id="'+post+'"]')
                target.addClass('disabled')
            },
            error: function (response) {
                console.log(response)
            }
        });
    }

    // send a message to user that their post was removed by a mod
    function sendDelMessage(){
        // get the form info to pass
        var modal = $('.reason-modal')
        var postId = modal.find('#postid').val();
        var user = modal.find('#user').val();
        var reason = modal.find('#reasonfor').val()
        var subject = "One of your posts was deleted!"
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/mod/senddelmsg",
            data:{
                'postId': postId,
                'user': user,
                'title': subject,
                'reason':reason
            },
            success: function(response){
                modal.fadeOut()
            },
            error: function (response) {
                console.log(response.responseText)
            }
        });

    }

    // send a mod message
    function modSendMsg(reciever, subject, text){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/message/send",
            data:{
                'recieve': reciever,
                'message': text,
                'subject': subject
            },
            success: function(response){
                // fade out modal
                $('.message-modal').fadeOut();
            },
            error: function (response) {
                // display an error
                $('.message-form').find('.alert').addClass('alert-danger').html('Your message could not be sent.');
                $('.alert').on('click', function(){
                    $('.alert').fadeOut(2000);
                });
            }
        });
    }

});
