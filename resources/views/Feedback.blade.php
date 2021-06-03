@include("incl/header")
        <h1 class="mt-2">Feedback</h1>
        <form method="POST" id="input_form" action="/Feedback/new" enctype="multipart/form-data">
            @csrf

        <p>What score would you give your ticket resolution</p>
        <select class = "Score form-control" name="Score">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
        <p>Was there anything not satisfactory</p>
        <div   id="myRadioGroup">
            <input   type="radio" name="Feedback" id="Yes"value="Yes">Yes
            <input  type="radio" name="Feedback" id="No" checked="checked" value="No">No
        </div>
            <div id="FeedbackBox" class="mb-2" >
                <label for="FeedbackBox" class="Filter-Label">Feedback</label>
                <textarea name="FeedbackBox" type="text" class=" form-control"cols="40" rows="5" value=""></textarea>
            </div>
        <input value="{{$ticket_id}}" Name="ticket_id" hidden />
        <button class="btn btn-primary" type='submit' name='SubmitFeedback'> Submit </button>
    </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#FeedbackBox").hide();
        $('input:radio[name=Feedback]').change(function() {
            if (this.value == 'Yes') {
                $("#FeedbackBox").show();
                }
            else if(this.value == 'No'){
                $("#FeedbackBox").hide();
            }
            });
        });
        </script>
    </body>
</html>
