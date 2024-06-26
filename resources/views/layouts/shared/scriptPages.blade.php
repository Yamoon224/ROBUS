
<script type="text/javascript">

    $(document).ready(function(){

    $('textarea').on("input", function() {
    var maxlength = $(this).attr("maxlength");
    var currentLength = $(this).val().length;

    if (currentLength >= maxlength) {
        return console.log("You have reached the maximum number of characters.");
    }

   console.log(maxlength - currentLength + " chars left");
    });

 });
</script>


