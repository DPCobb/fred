<div class='modal message-modal message-reply'>
    <form class="message-form" action="/api/message/reply" method="POST">
        <div class="alert"></div>
        <h5>Reply To <span id="user-name"></span></h5>
        {{ csrf_field() }}
        <input type="hidden" name="parent" id="parent" value=""/>
        <textarea placeholder="Add your message" id="message" name="message"></textarea>
        <input type="hidden" name="recieve" id="recieve" value="" required/>
        <button type="submit" id="message-rep"><i class="fa fa-check" aria-hidden="true"></i></button>
        <a href="#" id="cancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
    </form>
</div>
