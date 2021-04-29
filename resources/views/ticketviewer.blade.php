@include("incl/header")
@foreach($results as $result)
    <div class="row">
        <div class="col-lg-6">
            <h1 class="mt-2">Ticket - {{$result['id']}}</h1>
            <select class="form-control mb-4 w-25">
                <option selected value="{{$result['type']}}">{{$result['type']}}</option>
                <option value="Bug">Bug</option>
            </select>
        </div>
        <div class="col-lg-6 d-flex">
            <div class="ml-auto">
                <a href="{{ url('/closeTicket/'.$result['id']) }}" class="btn btn-primary mt-4">Close Ticket</a>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-lg-7">
        <h4 class="pt-2">{{$result['subject']}}</h4>
        <p>
            {{$result['message']}}
        </p>
    </div>
    <div class="col-lg-5 ">
        @if($result['attachment'] != null)
            <img class="attachment" src="{{ URL::asset('uploaded_files/'.$result['attachment'])}}" alt="logo">
        @endif
        <div class="employee employee_background">
            <h4 class="pt-2">Employees</h4>
            <div class="d-flex"> Tom Eykholt <a class="ml-auto" href="#">Add to ticket</a></div>
            <div class="d-flex"> Tjerk Zeilstra <a class="ml-auto" href="#">Add to ticket</a></div>
            <div class="d-flex"> Rik den Breejen <a class="ml-auto" href="#">Add to ticket</a></div>
        </div>
    </div>
@endforeach



</div>
</body>
</html>
