<!-- The Modal -->
<form action="{{ url('password/email') }}" id="forgetpassform" method="POST" enctype="multipart/form-data">
<div class="modal" id="forgetpassmodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Enter email</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" id="email_forgot">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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