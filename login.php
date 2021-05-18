<!--login-->
<div class="card login-form-box position-absolute top-50 start-50 p-5 shadow">
    <h1 class="card-title text-center">Login</h1>

    <form action="" method="post" class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-lg-3">
                <label for="userid" class="col-form-label">UserID</label>
            </div>
            <div class="col-lg-9">
                <input type="text" name="userid" id="userid" class="form-control newMsg-form-font">
                <span class="error_msg"><?= isset($user_err)? $user_err: ''; ?></span>
            </div>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-lg-3">
                <label for="password" class="col-form-label">Password</label>
            </div>
            <div class="col-lg-9">
                <input type="password" name="password" id="password" class="form-control newMsg-form-font">
                <span class="error_msg"><?= isset($pass_err)? $pass_err: ''; ?></span>
            </div>
        </div>
        <span class="error_msg"><?= isset($err_msg)? $err_msg: ''; ?></span>
        <div class="text-center mt-5 w-100">
            <input type="submit" name="login" value="login" class="btn btn-secondary login-btn px-5 w-100">
        </div>
    </form>
</div>