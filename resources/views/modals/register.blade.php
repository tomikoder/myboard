<!-- The Modal -->
<form action="{{ url('register') }}" id="signupform" method="POST" enctype="multipart/form-data">
<div class="modal" id="signupmodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Registration</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-danger"><small id="register_errors"></small></p>
            <div class="form-group">
                        <label for="name">Username:</label>
                        <input type="text" name="name" class="form-control" id="name">
                        <p class="text-danger"><small id="err_login"></small></p>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" id="email">
                        <p class="text-danger"><small id="err_login"></small></p>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <p class="text-danger"><small id="err_password"></small></p>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Confirm Password</label>
                        <input type="password" class="form-control" id="password" name="password_confirmation">
                        <p class="text-danger"><small id="err_password"></small></p>
                    </div>
                    <div class="form-check">
                    <label class="form-check-label" for="">Agree with terms</a></label><br>
                    <input type="checkbox" name="agree_with_terms"></label>
                </div>
		  </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <input type="submit" value="Submit">
      </div>
    </div>
  </div>
</form>  
</div>