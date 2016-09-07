jQuery(document).ready( function() {

   jQuery(".user_vote").click( function(event) {

      event.preventDefault();

      comment_id = jQuery(this).attr("data-comment_id")
      nonce = jQuery(this).attr("data-nonce")
      url = jQuery(this).attr("href")

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
            if(response.vote_error){
               //alert("We're experiencing issues. Please contact an administrator.");
               console.log(response.vote_error);
            } else {
               jQuery("#vote_counter_" + response.comment_id).html(response.vote_count);

               alert("Thank you for Voting.");
            }
         }

      })

   })

})