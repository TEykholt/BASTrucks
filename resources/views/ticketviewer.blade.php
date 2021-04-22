@include("incl/header")
@foreach($results as $result)
        <h1 class="mt-2">Ticket - {{$result['id']}}</h1>
        <select  class="form-control mb-4 w-25" >
            <option selected value="{{$result['type']}}">{{$result['type']}}</option>
            <option value="Bug">Bug</option>
        </select>
        <div class="row">
            <div class="col-lg-7">
                <h4 class="pt-2">{{$result['subject']}}</h4>
                <p>
                    {{$result['message']}}
                </p>
            </div>
            <div class="col-lg-5 employee_background">
                <div class="employee">
                    <h4 class="pt-2">Employees</h4>
                    <div class="d-flex"> Tom Eykholt <a class="ml-auto" href="#">Add to ticket</a></div>
                    <div class="d-flex"> Tjerk Zeilstra <a class="ml-auto" href="#">Add to ticket</a></div>
                    <div class="d-flex"> Rik den Breejen <a class="ml-auto" href="#">Add to ticket</a></div>
                </div>
            </div>
        </div>
@endforeach


@foreach($logs as $log)
    <!--ToDo: Get all logs from ticket-->
    <h4 class="mt-2">Log</h4>
    <table class="table">
        <tr class="thead">
            <th>Date</th>
            <th>Name</th>
            <th>Message</th>
        </tr>

        <tr class="trow">
            <td>{{$log['message']}}</td>
            <td>{{$log['date_created']}}</td>
            <td>{{$log['created_by']}}</td>
        </tr>

    </table>
@endforeach
</div>
</body>
</html>
