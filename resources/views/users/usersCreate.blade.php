@include("incl/header")

<h1>Create new user</h1>
<form method="POST" action="/admin/users/add">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input required type="text" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
        <label for="username">User name</label>
        <input required type="text" class="form-control" id="username" name="username">
    </div>
    <div class="form-group">
        <label for="jobtitle">Job Title</label>
        <input required type="text" class="form-control" id="jobtitle" name="jobtitle">
    </div>
    <div class="form-group">
        <label for="tell">Telephone number</label>
        <input required type="text" class="form-control" id="tell" name="tell">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" class="form-control" id="password" name="password" >
    </div>
    <input required type="submit" class="btn btn-primary" value="Create new person"/>
</form>

</div>
</body>
</html>