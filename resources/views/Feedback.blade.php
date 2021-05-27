@include("incl/header")
        <h1 class="mt-2">Feedback</h1>
        <form method="POST" id="input_form" action="/Feedback/new" enctype="multipart/form-data">
            @csrf
        <div id="FeedbackBox" class="Filter-Item ">
            <label for="FeedbackBox" class="Filter-Label">Feedback</label>
            <textarea name="FeedbackBox" type="text" class="Filter-Value VerticalResize DisplayBlock"cols="40" rows="5" value=""></textarea>
        </div>
        <h3>What score would you give your ticket resolution</h3>
        <select class = "Score" name="Score">
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
        <h3>and was there anything not satisfactory</h3>
        <div id="myRadioGroup">
            <input type="radio" name="Feedback" id="Yes"value="Yes">Yes
            <input type="radio" name="Feedback" id="No" checked="checked" value="No">No    
        </div>

        <input value="{{$ticket_id}}" Name="ticket_id"></input>
        <button type='submit' name='SubmitFeedback'> Submit </button>
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
