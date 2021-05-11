@include("incl/header")
<h1>Insert Ticket</h1>
        <form method="POST" id="input_form" action="/ticketInput/addTicket" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="ticket_type">Type</label>
                <select id="ticket_type" class="form-control" name="ticket_type" id="ticket_type">
                    @foreach($types as $type)
                        <option value="{{$type['name']}}">{{$type['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="department_id">Department</label>
                <select id="department_id" class="form-control" name="department_id" id="Department">
                    @foreach($departments as $department)
                        <option value="{{$department['id']}}">{{$department['name']}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input required id="subject" type="text" name="subject" class="form-control">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea required id="message" class="form-control" name="message" cols="40" rows="5"></textarea>
                <!-- <input id="message" type="text" name="message" class="form-control"> -->
            </div>
            <div class="form-group">
                <label for="Attachments">Attachments</label>
                <input type="file" accept="image/*" name="Attachments[]" id="Attachments" multiple>
            </div>

            <input type="button" class="btn btn-primary" onclick="fireAlert()" value="Submit">
        </form>
         <script>
            function fireAlert(){
                Swal.fire(
                    'Ticket submitted',
                    '',
                    'success'

                ).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("input_form").submit()
                    }
                })
            }
         </script>

        </div>
    </body>
</html>
