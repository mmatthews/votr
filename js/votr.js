jQuery(document).ready( function() {

   jQuery(".user_vote").click( function(event) {

      event.preventDefault();

      comment_id = jQuery(this).attr("data-comment_id")
      nonce = jQuery(this).attr("data-nonce")
      url = jQuery(this).attr("data-link")

      if(jQuery(this).hasClass('upvote')){
         mydata = {action: "vote", comment_id : comment_id, nonce: nonce, direction: true}
      } else {
         mydata = {action: "vote", comment_id : comment_id, nonce: nonce, direction: false}
      }

      jQuery.ajax({
         type : "POST",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : mydata,
         handleError: function(response){
            console.log('AJAX failed. Please contact an administrator.');
         },

         success: function(response) {
            console.dir(response);
            if(response.denied){
               alert("You've already voted.");
            }
            else if(response.vote_error){
               console.log(response.vote_error);
            } else {
               jQuery("#vote_counter_" + response.comment_id).html(response.vote_count);
               alert("Thank you for Voting.");
            }
         }

      })

   })

})