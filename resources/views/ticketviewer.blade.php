@include("incl/header")

    <div class="row">
        <div class="col-lg-6">
            <p id="id" hidden>{{$result['id']}}</p>
            <h1 class="mt-2">Ticket - {{$result['id']}}</h1>
            
            @can("edit ticket")
                <select onchange="updateTicket()" id="type" class="form-control mb-4 w-25 mt-3">
                    <option selected value="{{$result['type']}}">{{$result['type']}}</option>
                    @foreach($types as $type)
                        <option value="{{$type['name']}}">{{$type['name']}}</option>
                    @endforeach
                </select>
            @else
                <div id="type" class="form-control mb-4 w-25 mt-3">
                    {{$result['type']}}
                </div>
            @endcan

        </div>

        @can("edit ticket")
            <div class="col-lg-6 d-flex">
                <div class="ml-auto">
                        <p class="text-right">
                            Submitted by {{$result['person_name']}}<br/>
                            {{$result['email']}}<br/>
                            @if($result['status'] == 'closed')
                                <a href="{{ url('/openTicket/'.$result['id']) }}" class="btn btn-primary mt-4">Open Ticket</a>
                            @else
                                <a href="{{ url('/closeTicket/'.$result['id']) }}" class="btn btn-primary mt-4">Close Ticket</a>
                            @endif
                        </p>

                </div>
            </div>
        @endcan
        
    </div>
<div class="row">
    <div class="col-lg-7">
        <h4 class="pt-2">{{$result['subject']}}</h4>
            @can("edit ticket")
                <textarea id="message" class="form-control text-area">{{ltrim(rtrim($result['message']))}}</textarea>
            @else
                <div id="message" class="form-control text-area">{{ltrim(rtrim($result['message']))}}</div>
            @endcan
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
        
        @can("edit ticket")
            <form method="post" class="text-right" action="/ticketviewer/editTicketAttachements" enctype="multipart/form-data">
                @csrf
                <input class="ml-auto" type="file" accept="image/*" name="Attachments[]" id="Attachments" multiple>
                <input hidden name="id" value="{{$result['id']}}">
                <input type="submit" class="btn btn-primary mt-2 btn-view" value="Submit">
            </form>
        @endcan

        
        @isset($AssignedPersons)
        @if(count($AssignedPersons)>0)
        <div class="employee employee_background mt-2">
            <h4 class="pt-2">Assigned Employees</h4>

                @foreach($AssignedPersons as $assignedPerson)
                    <div class="d-flex"> {{$assignedPerson['username']}}
                        @can("unassign employee")
                            <a class="assignedPerson ml-auto" href="#" onclick='removeTicketPerson(<?= $assignedPerson["id"] ?>)'> Remove from ticket</a>
                        @endcan
                    </div>
                @endforeach
            </div>
        @endif
        @endisset

        @can("assign employee")
            <div class="employee employee_background mt-2">
                <h4 class="pt-2">Assign an Employees</h4>

                <input class="form-control" name="searchbox" id="input" type="text" placeholder="Enter name" onchange="getSuggestions(event)" onkeyup="getSuggestions(event)">
                
                <input id="userSearchbox" type="submit" class="btn btn-primary mt-2 btn-view" value="Assign" onclick="addTicketPerson()">
            </div>
        @endcan
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
    var ticketPersonSearchText = ""
    function updateTicket() {
        var data = {
            type: $('#type').val(), 
            id : $('#id').html()
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/updateTicket",
            data: data,
        })
    }

    function addTicketPerson() 
    {
        var data = {
            username : ticketPersonSearchText,
            ticket_id : "{{$result['id']}}"
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/assignTicketPersonByUsername",
            data: data,

            succes: function(data){
                console.log(data);
            }
        })
    }

    function removeTicketPerson(person_id) {
        var data = {
            person_id : person_id,
            ticket_id : "{{$result['id']}}"
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/unassignTicketPerson",
            data: data,
        })
    }

    function getSuggestions(event)
    {
        var SearchString = event.target.value;
        ticketPersonSearchText = SearchString;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/getUserAutoFill",
            data: {
            username : SearchString,
            },

            success: function (data) {

                var userSuggestions = [];
                for (let index = 0; index < data.length; index++) {
                    const user = data[index];
                    userSuggestions.push(user.username);
                }

                //console.log(userSuggestions);

                $(event.target).autocomplete({
                    source: userSuggestions
                })
            },
        })

    }

    $(document).ready(function() {
        $('.gallery_pics').click(function(e) {
            // Change Selector Here
            $(this).toggleClass('fullscreen');
        });
    });


    var oldVal = "";
    $("#message").on("change keyup paste", function() {
        var currentVal = $(this).val();
        if(currentVal == oldVal) {
            return; //check to prevent multiple simultaneous triggers
        }
        oldVal = currentVal;
        //action to be performed on textarea changed
        console.log(currentVal)
        var dataMessage = {message: currentVal, id : $('#id').html()}
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType : 'json',
            url: "/updateTicketMessage",
            data: dataMessage,
        })
    });
</script>
</body>
</html>
