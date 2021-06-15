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

    <!-- <div class="col-lg-6 col-12">
        <h1>Test</h1>
        <form method="POST" action="/admin/roles/update">
            @csrf
            <div class="form-group">
                <label for="id">id</label>
                <input required type="text" class="form-control" id="id" name="id" value="{{$id}}">
            </div>
            <div class="form-group">
                <label for="name">name</label>
                <input required type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="checked">checked</label>
                <input required type="checkbox" class="form-control" id="checked" name="checked" value="{{$user->username}}">
            </div>
            <input required type="submit" class="btn btn-primary" value="Test"/>
        </form>
    </div> -->

    <div class="col-lg-6 col-12">
        <h1>Edit permissions</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Has permision</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $result)
                <tr>
                    
                    <td> {{$result->name}} </td>
                    <td>
                        <input type="checkbox" name="{{$result->name}}" id="{{$result->name}}" aria-label="" onchange='updaterole("{{$result->name}}")' <?php foreach($userroles as $UR){ if ($UR === $result->name) {
                            echo("checked");
                        }} ?> >
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</body>
</html>

<script>
function updaterole(name){
    const cb = document.getElementById(name);

    var data = {
        name : name,
        id : "{{$id}}",
        checked : cb.checked
    }

    console.log(data);

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        dataType : 'json',
        url: "/admin/roles/update",
        data: data,

        succes: function(data){
            console.log(data);
        }
    });
}
</script>