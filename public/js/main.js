/**
 * Daniel Cobb
 * ASL - nmbley v2.0
 * 1-22-2017
 */
$(document).ready(function(){

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


/**
 *
 * Global Event Listeners
 *  ex: Forms - Stop Click Propagation so modal doesn't close
 *
 */

    // Stop Modal Forms from closing Modal
    $('form').on('click', function(e){
        e.stopPropagation();
    });

    // Confirmation for delete
    $('.delete-form').on('submit', function(){
        return confirm("This is forever, are you sure??");
    });

    // alert area fade out
    $('.alert').on('click', function(){
        $('.alert').fadeOut(2000);
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


/**
 *
 * Basic Page Setup features
 *  ex: Messages: change icon if user has unread mail
 */

    // Get direct messages and change mail icon if needed
    function getMsgs(){
        var mail = '<i class="fa fa-envelope" aria-hidden="true"></i>';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // send data
        $.ajax({
            type:"GET",
            url:"/api/message/mail",
            success: function(data){
                if(data.length > 0){
                    $('.nav').find('#messages').html(mail);
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }


    // Hide view comments link if there are no comments
    $('.comments').each(function(i){
        if($(this).children().length === 0){
            $(this).closest('article').find('.comment').hide();
        }
        else{
            $(this).closest('article').find('.comment').show();
        }
    });

    // toggle the add comment form
    $('.blue-action').on('click', function(e){
        e.preventDefault();
        $(this).closest('article').find('.add-comment').slideToggle();
    });

    // toggle the current comments
    $('.comment').on('click', function(e){
        e.preventDefault();
        $(this).closest('article').find('.comments').slideToggle();
        if($(this).text() == "View Comments"){
            $(this).text("Hide Comments");
        }
        else{
            $(this).text("View Comments");
        }
    });

    // open the create category form
    $('.create').on('click', function(){
        $('.small-cat-form').slideToggle();
    });

    // hide the replies on user messages
    $('.hide-rep').on('click', function(e){
        e.preventDefault();
        $(this).closest('article').find('.replies').slideToggle();
        if($(this).text() == "Hide Replies"){
            $(this).text("Show Replies");
        }
        else{
            $(this).text("Show Replies");
        }
    });

    // hide view replies if there are none
    $('.replies').each(function(i){
        if($(this).children().length === 0){
            $(this).closest('article').find('.hide-rep').hide();
        }
        else{
            $(this).closest('article').find('.hide-rep').show();
        }
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    /**
     *
     * Functions
     *  ex: canelListen: clears on inputs on form when cancel is clicked
     */


    // Function that clears inputs on cancel and hides suggestions
    function cancelListen(){
        $('.cancel').on('click', function(e){
            e.preventDefault();
            $('input[id="title"], input[id="cat"]').val('');
            $('textarea').val('');
            $('.suggestions').hide();
        });
    }

    // function for the category search for suggestions
    function catSearch(){
        // Clears all inputs
        $('#photo input[id="cat"], #text input[id="cat"]').val('');
        $('textarea').val('');
        $('.suggestions').hide();
        // When someone is typing in the category input
        $('.cat').on('keyup', function(){
            // find the form this belongs too and get the value
            var data = $(this).closest('form').find('.cat').val();
            // if the data is empty hide the suggestions box
            if(data.length === 0){
                $(this).closest('form').find('.suggestions').fadeOut().html(' ');
            }
            // if there is data in the input, use ajax to get a list of categorys that contain the input
            else{
                // ajax request for list of categorys
                $.ajax({
                    type: "GET",
                    url: "/api/search/"+ data,
                    dataType: "json",
                    success: function(response){
                        // if the ajax is successful show the suggestions box
                        $('.suggestions').show(function(){
                            // set up variables
                            var i;
                            var display = 'Suggestions:<br>';
                            // if the length of the response is 0 the category does not exist
                            if(response.length == 0){
                                $(this).closest('form').find('.suggestions').html('Suggestions:<br>This category doesn\'t exist yet!');
                                $(this).closest('form').find('button').attr('disabled', true);
                            }
                            // if the length is greater than zero, loop through results
                            else{
                                for(i=0; i < response.length; i++ ){
                                    // display a link with the categories name
                                    display += '<a href="#" class="suggestlink" data-name="'+ response[i].name +'" data-id="'+response[i].catId+'">'+response[i].name+'</a>';
                                }
                                // find the suggestions box and display the results
                                $(this).closest('form').find('.suggestions').html(display);
                                $(this).closest('form').find('button').attr('disabled', false);
                                // add listen function for clicks on the category name links to add them to category input
                                addListen();
                            }
                        });
                    }

                });

            }
        });
    }

    // Add listen creates and event listener/updates the listener for category suggestions
    function addListen(){
        // when someone clicks on a suggested link
        $('.suggestlink').on('click', function(e){
            // prevent the default action
            e.preventDefault();
            // get the category name and id from the link
            var value = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            // add the name to the cat input
            $(this).closest('form').find('#cat').val(name);
            // fade out the suggestions box
            $(this).closest('.suggestions').fadeOut();
        });
    }

    // closes modals
    function closeModal(){
        $('.modal').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            $('.modal').fadeOut();
        });

    };


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    /**
    *
    * Create a New Post Form
    *  Sets up the Text or Image form for new posts
    */


    // Sets up the image post form
    $('#imgPost').on('click', function(e){
        e.preventDefault();
        // update the search and event listener
        catSearch();
        cancelListen();
        // fade out text form and fade in photo form
        $('#text').fadeOut(1000, function(){
            $('#photo').fadeIn(function(){
                // update search and event
                catSearch();
                cancelListen();
            });
        });
    });
    // set up text posts
    $('#textPost').on('click', function(e){
        e.preventDefault();
        // update search and event listener
        catSearch();
        cancelListen();
        // fade out photo and fade in text
        $('#photo').fadeOut(1000, function(){
            $('#text').fadeIn(function(){
                // update search and event listener
                cancelListen();
                catSearch();
            });
        });
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    /**
     *
     * Replies
     *
     */

    // submits a reply to a comment
    $('#reply-submit').on('click', function(e){
        e.preventDefault();
        // Set needed values
        var postId = $('#replypostid').val();
        var parent = $('#replycomid').val();
        var msg = $('#newreply').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: "api/reply",
            dataType: "json",
            data:{
                'postId': postId,
                'msg' : msg,
                'parent' : parent
            },
            success: function(response){

            }

        });
        // fades out modal
        $('.modal').fadeOut();
        location.reload();

    });


    // set up the reply modal data
    $('.reply').on('click', function(e){
        // get needed values
        var postId = $(this).attr('data-postid');
        var commentId = $(this).attr('data-commentid');
        // set values
        $('#replycomid').val(commentId);
        $('#replypostid').val(postId);
        e.preventDefault();
        $('.comment-modal').fadeIn(function(){
            closeModal();
        });
    })


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    /**
     *
     * Edits
     *
     */

    // edit a post
    $('.edit').on('click', function(e){
        e.preventDefault();
        var post = $(this).attr('data-postid');
        localStorage.setItem('post', post);
        // get the post data and set up the update forms
        $('.post-edit').fadeIn(function(){
            $.ajax({
                type: "GET",
                url: "/api/getpost/"+ post,
                dataType: "json",
                success: function(response){
                    if (response[0].type === 5){
                        // edit a photo
                        $('#edit-text').hide('fast',function(){
                            $('#editphoto').fadeIn(function(){
                                $('#editphoto').find('#title').val(response[0].title);
                                $('#editphoto').find('#cat').val(response[0].name);
                                $('#editphoto').find('#postid').val(localStorage.post);
                                $('#editphoto').find('#pic').attr('src', '.'+response[0].image);
                                $('#photocancelbutton').on('click', function(){
                                    $('.post-edit').fadeOut();
                                });
                            });

                        });
                    }
                    else{
                        // edit text
                        $('#editphoto').hide('fast',function(){
                            $('#edit-text').fadeIn(function(){
                                $('#edit-text').find('#postid').val(localStorage.post);
                                $('#edit-text').find('#title').val(response[0].title);
                                $('#edit-text').find('#post').val(response[0].text);
                                $('#edit-text').find('#cat').val(response[0].name);
                                $('#editcancelbutton').on('click', function(){
                                    $('.post-edit').fadeOut();
                                });
                            });
                        });
                    }
                }
            })
        });
    });

    // edit a comment
    $('.com-edit').on('click', function(e){
        var commentid = $(this).attr('data-commentid');
        e.preventDefault();
        $('.comment-edit').fadeIn(function(){
            // get comment info and set up edit form
            $.ajax({
                type: "GET",
                url: "/api/getcomment/"+ commentid,
                dataType: "json",
                success: function(response){
                    $('.comment-edit').find('#editcom').val(response[0].msg);
                    $('.comment-edit').find('#commentid').val(commentid);
                }
            });
        });
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    /**
     *
     * Category Search
     *
     */


    // search for categories
    $('#catsearch').on('keyup', function(){
        // get the data to search for
        var data = $(this).val();
        // if data is empty show no results
        if(data.length === 0){
            $('.cat-list').html('');
        }
        $.ajax({
            type: "GET",
            url: "/api/category/search/"+ data,
            dataType: "json",
            success: function(response){
                var output = '';
                if(response.length == 0){
                    $('.cat-list').html('It seems like nothing matches, maybe you should make this category!');
                }
                // if the length is greater than zero, loop through results
                else{
                    for(i=0; i < response.length; i++ ){
                        // display a link with the categories name
                        output += '<a href="/c/'+response[i].name+'" class="follow-link" data-name="'+ response[i].name +'" data-id="'+response[i].catId+'">'+response[i].name+'</a>';
                    }
                    // find the list box and display the results
                    $('.cat-list').html(output);
                }
            }
        });
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

    /**
     *
     * Follow/Unfollow Category
     *
     */

    // follow a category
    $('#catfollow').on('click', function(e){
        e.preventDefault();
        var data = $(this).attr('data-id');
        // set up the csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // send data to api
        $.ajax({
            type:"POST",
            url:"/api/category/follow",
            data:{
                'id': data
            },
            success: function(){
                location.reload();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    // unfollow a category
    $('#catunfollow').on('click', function(e){
        e.preventDefault();
        var data = $(this).attr('data-id');
        // set up csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // send data
        $.ajax({
            type:"delete",
            url:"/api/category/unfollow",
            data:{
                'id': data
            },
            success: function(){
                location.reload();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    /**
     *
     * Like/Unlike Post
     *
     */

     // Like/Unlike a post
    $('.orange-action').on('click', function(){
        // get the action to follow
        var action = $(this).attr('data-action');
        // get the post id
        var id = $(this).attr('data-id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // if action is like
        if(action == "like"){
            $.ajax({
                type:"POST",
                url:"/api/like",
                data:{
                    'id': id
                },
                success: function(){
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
        // if action is unlike
        else {
            $.ajax({
                type:"delete",
                url:"/api/unlike",
                data:{
                    'id': id
                },
                success: function(){
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }


    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    /**
     *
     * Create a Category
     *
     */

     // create a new category to post into
    $('.createcat').on('click', function(e){
        e.preventDefault();
        // get the name for the new category
        var newCat = $(this).closest('form').find('#newcat').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/newcategory",
            data:{
                'name': newCat
            },
            success: function(response){
                // alert success
                $('.alert').addClass('alert-success');
                $('.alert').html(response);
                $('.alert').fadeIn();
            },
            error: function (response) {
                // alert error
                $('.alert').addClass('alert-danger');
                $('.alert').html(response);
                $('.alert').fadeIn();
            }
        });
    });



/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


    /**
     *
     *  Direct Messaging
     *
     */

     // setup a message to a user by clicking on their user name
    $('.useroptions').on('click', function(e){
        e.preventDefault();
        // get vars
        var reciever = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        // set form values
        $('#user-name').html(name);
        $('#recieve').val(reciever);
        // open the message modal
        $('.message-modal').fadeIn(function(){
            $('.message-form').on('click', function(e){
                e.stopPropagation();
            });
            $('.cancel').on('click', function(){
                $('.message-modal').fadeOut();
            });
        });

    });

    // send a new message
    $('#message-sub').on('click', function(e){
        e.preventDefault();
        // get the values
        var recieve = $('#recieve').val();
        var message = $('#message').val();
        var subject = $('#subject').val();
        // send the data
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/message/send",
            data:{
                'recieve': recieve,
                'message': message,
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
    });


    // mark a message as read
    $('.read').on('click', function(e){
        e.preventDefault();
        // get msg id
        var msg = $(this).attr('data-msg');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/message/markread",
            data:{
                'id': msg
            },
            success: function(response){
                location.reload();
            },
            error: function (response) {

            }
        });

    });

    // delete a message
    $('.del').on('click', function(e){
        e.preventDefault();
        // get message id
        var msg = $(this).attr('data-msg');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/message/deleteread",
            data:{
                'id': msg
            },
            success: function(response){
                location.reload();
            },
            error: function (response) {

            }
        });

    });

    // reply to a user using the reply button
    $('.reply-btn').on('click', function(e){
        e.preventDefault();
        // get msg info
        var name = $(this).closest('article').find('.useroptions').attr('data-name');
        var reciever = $(this).attr('data-to');
        var parent = $(this).attr('data-msg');
        // set up the reply form
        $('.message-reply').find('#parent').val(parent);
        $('.message-reply').find('#recieve').val(reciever);
        $('.message-reply').find('#user-name').html(name);
        $('.message-reply').fadeIn(function(){
            $('.cancel').on('click', function(){
                $('.message-modal').fadeOut();

            });
        });
    });

    // send a message reply
    $('#message-rep').on('click', function(e){
        e.preventDefault();
        // get values
        var parent = $(this).closest('.message-form').find('#parent').val();
        var message = $(this).closest('.message-form').find('#message').val();
        var recieve = $(this).closest('.message-form').find('#recieve').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:"POST",
            url:"/api/message/reply",
            data:{
                'recieve': recieve,
                'message': message,
                'parent': parent
            },
            success: function(response){
                // success close modal
                $('.message-modal').fadeOut();
            },
            error: function (response) {
                // alert error
                $('.message-form').find('.alert').addClass('alert-danger').html('Your message could not be sent.');
                $('.alert').on('click', function(){
                    $('.alert').fadeOut(2000);
                });
            }
        });
    });

    // reply by clicking a user name in message box
    $('.useroptionsrep').on('click', function(e){
        e.preventDefault();
        // get data
        var reciever = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var parent = $(this).attr('data-msg');
        // set up form data
        $('.message-reply').find('#user-name').html(name);
        $('.message-reply').find('#recieve').val(reciever);
        $('.message-reply').find('#parent').val(parent);
        // open form
        $('.message-reply').fadeIn(function(){
            $('.message-form').on('click', function(e){
                e.stopPropagation();
            });
            $('.cancel').on('click', function(){
                $('.message-modal').fadeOut();
            });
        });

    });


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


// call functions
getMsgs();
catSearch();
closeModal();
cancelListen();



// End JS
})
