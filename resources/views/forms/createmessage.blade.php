<div class='modal message-modal'>
    <form class="message-form" action="/api/message/send" method="POST">
        <div class="alert"></div>
        <h5>Send a Message to <span id="user-name"></span></h5>
        {{ csrf_field() }}
        <input type="text" name="subject" id="subject" required placeholder="Subject"/>
        <textarea placeholder="Add your message" id="message" name="message"></textarea>
        <input type="hidden" name="recieve" id="recieve" value="" required/>
        <button type="submit" id="message-sub"><i class="fa fa-check" aria-hidden="true"></i></button>
        <a href="#" id="cancelbutton" class="button-round red cancel"><i class="fa fa-times" aria-hidden="true"></i></a>
    </form>
</div>
