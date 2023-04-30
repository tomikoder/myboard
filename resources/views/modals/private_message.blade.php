<!-- The Modal -->
<form action="{{ url('send/message/'.$user->id) }}" id="sendmessageform" method="POST" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="modal" id="sendmessagemodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add new post</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
                <div class="form-group">
                    <label for="text">Your text</label>
                    <textarea rows="6" cols="78" name="text" id="text"></textarea>
                </div>
		</div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <input type="submit" value="Submit">
      </div>
    </div>
  </div>
</div>
</form>  