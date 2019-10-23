(function($) {

  var publicToken = "";
  var metaData = {};

  /*<tr><th title="Field #1">Type</th>
                <th title="Field #2">Trans Date</th>
                <th title="Field #3">Post Date</th>
                <th title="Field #4">Description</th>
                <th title="Field #5">Amount</th>
                </tr>*/

  $.post("fetch.php?type=update_user",{filler: 'true'},
      function(msg){
          console.log(msg);
      }
  );

  function updateTransactionDisplay(data){
      $("#tables").prepend(data.html);
  }

  function loadCards(){
      $.post("fetch.php?type=transactions",{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response);
          response = $.parseJSON(response);
          $("#modal_title").html("Select a credit card...");
          $("#modal_text").html("<div id='cc_selection'></div>");
          for(i = 0; i < response.accounts.length; i++){
              if(response.accounts[i].subtype === "credit card"){
                  $("#cc_selection").append('<a href="" official_name="'+response.accounts[i].official_name+'">'+response.accounts[i].official_name+'</a>')
                  $("#link-button").html("Add another card").attr("started","started");
              }
          }
          console.log(response);
          
      }
      );
  }

  function loadCardData(officialName){


      $.post("fetch.php?type=transactions&official_name="+officialName,{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response);
          response = $.parseJSON(response);
          console.log(response,response.total_transactions);
          if(typeof response.error !== "undefined"){
              alert(response.error);
          }else {
              if(response.total_transactions > 0){
                  updateTransactionDisplay(response);   
                  $('#modal_def').modal('toggle');
              }else{
                  console.log("reload");
                  loadCardData(officialName);
              }
          }
      }
      );
  }

  var handler = Plaid.create({
    clientName: 'Abukai CC',
    countryCodes: ['US'],
    env: 'sandbox',
    key: 'a098c6bb0a982318837f9c7018573a',
    product: ['auth','transactions'],
    language: 'en',
    userLegalName: 'Hans Marcon',
    userEmailAddress: 'hmarcon@abukai.com',
    onLoad: function() {
      // Optional, called when Link loads
    },
    onSuccess: function(public_token, metadata) {
      // Send the public_token to your app server.
      // The metadata object contains info about the institution the
      // user selected and the account ID or IDs, if the
      // Select Account view is enabled.

      console.log("Logged into bank account successfully.",metadata);

      $("#bank_info").html("Currently logged into your "+metadata.institution.name+" account.");

      $.post("fetch.php?type=session_log",{institution: metadata.institution, public_token: public_token},function(alert){
          console.log("Logged in bank session.")
      });

      publicToken = public_token;

      metaData = metadata;
      

      if($("#link-button").is("[started]")){
          $("#import-card-feed").trigger('click');
      }else{
          $("#menu").show();
          
      }

    },
    onExit: function(err, metadata) {
      // The user exited the Link flow.
      if (err != null) {
        // The user encountered a Plaid API error prior to exiting.
      }
      // metadata contains information about the institution
      // that the user selected and the most recent API request IDs.
      // Storing this information can be helpful for support.
    },
    onEvent: function(eventName, metadata) {
      // Optionally capture Link flow events, streamed through
      // this callback as your users connect an Item to Plaid.
      // For example:
      // eventName = "TRANSITION_VIEW"
      // metadata  = {
      //   link_session_id: "123-abc",
      //   mfa_type:        "questions",
      //   timestamp:       "2017-09-14T14:42:19.350Z",
      //   view_name:       "MFA",
      // }
    }
  });

  /////////////

  //console.log(metaData);

  $('#link-button').on('click', function(e) {
    handler.open();
  });

  $("body").on('click','.toggle-btn',function(event){
    $(this).next('.collapse').collapse('toggle');
  });


  $("body").on('click','#cc_selection a[official_name]',function(event){
      event.preventDefault();
      officialName = $(this).attr("official_name");
      loadCardData(officialName); 
      $("#modal_title").html("Waiting for card data to appear...");

  });

  $('body').on('click','#import-card-feed',function(e){
      $('#modal_def').modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#modal_title").html("Loading...");
      loadCards();
  });

  $("body").on('click','.nickname-btn',function(event){
    $('#modal_def').modal({
      backdrop: 'static',
      keyboard: false
    });
    $("#modal_text").html("");
    $("#modal_title").html("Managing Nickname");

  });

  $("body").on('click','.delete-card-feed-btn',function(event){
    $('#modal_def').modal({
      backdrop: 'static',
      keyboard: false
    });
    $("#modal_text").html("Are you sure you want to do this? If so, click the \"Confirm\" button below. ");
    $("#modal_title").html("Delete Card Feed");
    $("#modal-def .modal-footer").html("<button class='btn btn-danger confirm-deletion'>Confirm</button>")
  });

})(jQuery);