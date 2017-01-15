/**
 * Daniel Cobb
 * ASL - nmbley v1.0
 * 1-8-2017
 */
$(document).ready(function(){

    // Hide view comments link if there are no comments
    $('.comments').each(function(i){
        if($(this).children().length === 0){
            $(this).closest('article').find('.comment').hide();
        }
        else{
            $(this).closest('article').find('.comment').show();
        }
    });

    // Confirmation for delete
    $('.delete-form').on('submit', function(){
        return confirm("This is forever, are you sure??");
    });

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
    // toggle the add comment form
    $('.blue-action').on('click', function(e){
        e.preventDefault();
        $(this).closest('article').find('.add-comment').slideToggle();
    })

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

    // set up the reply modal data
    $('.reply').on('click', function(e){
        var postId = $(this).attr('data-postid');
        var commentId = $(this).attr('data-commentid');
        $('#replycomid').val(commentId);
        $('#replypostid').val(postId);
        e.preventDefault();
        $('.comment-modal').fadeIn(function(){
            closeModal();
        });
    })

    // closes modals
    function closeModal(){
        $('.modal').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            $('.modal').fadeOut();
        });
        // stops form clicks from propagating to the modal
        $('.comment-form-reply, .comment-form-edit, #edit-text, #editphoto').on('click', function(e){
            e.stopPropagation();
        });

        // submits a reply to a comment
        $('#reply-submit').on('click', function(e){
            e.preventDefault();
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
    };
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
    // search for categories
    $('#catsearch').on('keyup', function(){
        var data = $(this).val();
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
// call catSearch and closeModal
catSearch();
closeModal();
cancelListen();
// End JS
})
