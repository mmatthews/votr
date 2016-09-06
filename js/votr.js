jQuery(document).ready( function() {

   //alert('Votr.js Loaded');

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
            console.log('AJAX failed. Contact an administrator.');
         },

         success: function(response) {
            console.log(response);

            if(response.vote_error){
               alert(response.vote_error);
               console.log(response.vote_error);
            } else {
               //console.log('Vote Succeeded');
               //alert("Thank you for Voting.");
               jQuery("#vote_counter_" + response.comment_id).html(response.vote_count)
            }
         }

      })

   })

})