<script>
// onload
$(function() {

    // upload new sets of flashcards
    $("#cardUpload").on('click', function(event) {
        window.location.href = "<?php echo $templ['engine_web_loc']; ?>";
    });


}); // end onload
</script>
