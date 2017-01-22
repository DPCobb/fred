<script type="text/babel">
    var Comments = React.createClass({
        render(){
            return(
                <div className='well'>
                    test
                </div>
            )
        }
    });

    var Post = React.createClass({

        getInitialState(){
            return {posts: [],
                showComment:false
            }
        },
        componentDidMount(){
             this.posts = [];
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:"GET",
                url:"/api/home",
                success: function(response){
                    this.posts = response;
                    this.updateState(this.posts)
                }.bind(this),
                error: function (response) {
                    console.log(response)
                }
            });
        },
        updateState(data){
            this.setState({
                posts: data
            });
        },
        comment(e){
            console.log('entered')
            console.log(this.state.showComment)
            e.preventDefault();
            this.setState({showComment: !this.state.showComment})
        },
        postDisplay() {
            return (
                <article className="text-post" key={post.postId}>
                    <h5>{post.title}</h5>
                    <h6>Posted in <a href={url}  title={post.name}>{post.name}</a> by <a href="#" className="useroptions" data-id={post.user} data-name={name}>{post.fname} {post.lname}</a></h6>
                    <p>{post.text}</p>
                    <div className="buttons">
                        <div className="button-action" title="Points">{post.likes}</div>
                        <div className="button-action blue-action" title="Add Comment"><i className="fa fa-plus" aria-hidden="true"></i></div>
                            {post.likedBy && post.likedBy == post.userId ?(
                                <div className="button-action orange-action liked" title="You liked this!" data-action="unlike" data-id={post.postId}><i className="fa fa-heart" aria-hidden="true"></i></div>
                            ):(
                                <div className="button-action orange-action" title="Favorite" data-action="like" data-id={post.postId}><i className="fa fa-heart-o" aria-hidden="true"></i></div>
                            )}
                        <a href="#" title="View Comments" className="small-link comment" onClick={() => this.comment(e).bind(this)}>View Comments</a>
                        <a href="#" title="Report" className="small-link">Report This Post</a>
                    </div>
                    <div className="add-comment well">

                    </div>
                    <div className="comments">
                        {< Comments / >}
                    </div>
                </article>
            )
        },
        render(){
            return (
                <div>
                {this.state.posts.map(function(post){
                    var url = 'c/' + post.name;
                    var name = post.fname + ' ' + post.lname
                    this.postDisplay()
                    }
                )}
                </div>
            )
        }
    });
        ReactDOM.render(<Post />, document.getElementById('feed'))

</script>
