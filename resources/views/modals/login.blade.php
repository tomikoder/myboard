<!-- The Modal -->
<form action="{{ url('login') }}" id="loginform" method="POST" enctype="multipart/form-data">
<div class="modal" id="loginmodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-danger"><small id="login_errors"></small></p>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" id="email">
                        <p class="text-danger"><small id="err_login"></small></p>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password">
                        <p class="text-danger"><small id="err_password"></small></p>
                    </div>
                <div class="form-check">
                    <label class="form-check-label" for="remember">Remember me</a></label><br>
                    <input type="checkbox" name="remember" id="password"></label>
                </div>
                <div>
                  <a href="" id="redir_to_regi_mod">Sign up</a><br><br>
                  <a href="" id="forget">Send password </a><input style="display: none;" id="forget_input" type="text" name="forget" id="forget" value="your email or username"><br>
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
