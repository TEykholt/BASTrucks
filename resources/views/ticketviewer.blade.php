@include("incl/header")

    <div class="row">
        <div class="col-lg-6">
            <h1 class="mt-2">Ticket - {{$result['id']}}</h1>
            <select class="form-control mb-4 w-25">
                <option selected value="{{$result['type']}}">{{$result['type']}}</option>
                @foreach($types as $type)
                    <option value="{{$type['name']}}">{{$type['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6 d-flex">
            <div class="ml-auto">
                @if($result['status'] == 'closed')
                    <a href="{{ url('/openTicket/'.$result['id']) }}" class="btn btn-primary mt-4">Open Ticket</a>
                @else
                    <a href="{{ url('/closeTicket/'.$result['id']) }}" class="btn btn-primary mt-4">Close Ticket</a>
                @endif
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
        <div class="d-flex gallery_pics_holder">
            @if($attachment != null)
                @foreach($attachment as $atta)
                    <div class="gallery_pics">
                        <img class="attachment" src="{{ URL::asset('uploaded_files/'.$atta['name'])}}" alt="logo">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="employee employee_background">
            <h4 class="pt-2">Employees</h4>
            <div class="d-flex"> Tom Eykholt <a class="ml-auto" href="#">Add to ticket</a></div>
            <div class="d-flex"> Tjerk Zeilstra <a class="ml-auto" href="#">Add to ticket</a></div>
            <div class="d-flex"> Rik den Breejen <a class="ml-auto" href="#">Add to ticket</a></div>
        </div>
    </div>
</div>

<div class="row">
<h4 class="mt-2">Log</h4>
    <table class="table">
    <tr class="thead">
        <th>Date</th>
        <th>created by</th>
        <th>Message</th>
    </tr>
@foreach($logs as $log)
        <tr class="trow">
            <td>{{$log['created_at']}}</td>
            <td>{{$log['created_by']}}</td>
            <td>{{$log['message']}}</td>
        </tr>
@endforeach
    </table>
</div>
<script>
    $(document).ready(function() {
        $('.gallery_pics').click(function(e) {
            // Change Selector Here
            $(this).toggleClass('fullscreen');
        });
    });
</script>
</body>
</html>
