@include("incl/header")


<div class="row">
    <div class="col-lg-6 col-12">
        <h1>Edit user</h1>
        <form method="POST" action="/admin/users/{{$id}}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input required type="text" class="form-control" id="email" name="email" value="{{$user->email}}">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input required type="text" class="form-control" id="username" name="username" value="{{$user->username}}">
            </div>
            <div class="form-group">
                <label for="jobtitle">Job title</label>
                <input required type="text" class="form-control" id="jobtitle" name="jobtitle" value="{{$user->job_title}}">
            </div>
            <div class="form-group">
                <label for="tell">Telefone number</label>
                <input required type="text" class="form-control" id="tell" name="tell" value="{{$user->tell}}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input required type="password" class="form-control" id="password" name="password">
            </div>
            <input required type="submit" class="btn btn-primary" value="Update person"/>
        </form>
    </div>
    <div class="col-lg-6 col-12">
        <h1>Edit permissions</h1>
    </div>
</div>
</div>
</body>
</html>