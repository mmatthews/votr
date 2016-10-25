jQuery(document).ready( function() {

   jQuery(".user_vote").click( function(event) {

      event.preventDefault();

      comment_id = jQuery(this).attr("data-comment_id")
      nonce = jQuery(this).attr("data-nonce")
      url = jQuery(this).attr("data-link")
      user_id = jQuery(this).attr("data-ui")

      if(jQuery(this).hasClass('upvote')){
         mydata = {action: "vote", comment_id : comment_id, nonce: nonce, direction: true, user_id: user_id}
      } else {
         mydata = {action: "vote", comment_id : comment_id, nonce: nonce, direction: false, user_id: user_id}
      }

      jQuery.ajax({
         type : "POST",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : mydata,
         handleError: function(response){
            console.log('AJAX failed. Please contact an administrator.')
         },

         success: function(response) {
            //console.dir(response)

            if(response.update_comment == true){
               jQuery("#comment-" + response.comment_id).slideUp();
            }

            if(response.denied){
               swal({
                  title: "You've already voted.",
                  type: 'error',
                  timer: 1000,
                  showConfirmButton: false
               });
            }
            else if(response.vote_error){
               //console.log(response.vote_error)
            } else {
               jQuery("#vote_counter_" + response.comment_id).html(response.vote_count)
               swal({
                  title: "Thank You for Voting!",
                  type: 'success',
                  timer: 1000,
                  showConfirmButton: false
               });
            }
         }

      })

   })

})