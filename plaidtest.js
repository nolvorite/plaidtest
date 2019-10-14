(function($) {

  var publicToken = "";
  var metaData = {};

  /*<tr><th title="Field #1">Type</th>
                <th title="Field #2">Trans Date</th>
                <th title="Field #3">Post Date</th>
                <th title="Field #4">Description</th>
                <th title="Field #5">Amount</th>
                </tr>*/

  function updateTransactionDisplay(data){
      for(i = 0; i < data.length; i++){
          typee = data[i].amount < 0 ? "SALE" : "RETURN";
          datee = data[i].date;
          datee2 = data[i].date;
          name = data[i].name;
          amount = data[i].amount;
          $("#transactions tbody").append('<tr>\
            <td>'+typee+'</td>\
            <td>'+datee+'</td>\
            <td>'+datee2+'</td>\
            <td>'+name+'</td>\
            <td>'+amount+'</td>\
            </tr>');

      }
  }

  function getListOfCards(data){

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

      publicToken = public_token;

      metaData = metadata;

      console.log("Logged In Successfully.");

      $("#guest").hide();
      $("#menu").show();


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

  $("body").on('click','#cc_selection a[id_sel]',function(event){
      event.preventDefault();
      accountId = $(this).attr("id_sel");

      $.post("fetch.php?type=transactions&account_id="+accountId,{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response);
          //updateTransactionDisplay(response.transactions);   
          $('#modal_def').modal('toggle');
      }//,"json"
      );

  });

  $('#import-card-feed').on('click',function(e){
      $('#modal_def').modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#modal_title").html("Loading...");
      $.post("fetch.php?type=transactions",{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response);
          $("#modal_title").html("Select a credit card...");
          $("#modal_text").html("<div id='cc_selection'></div>");
          for(i = 0; i < response.accounts.length; i++){
              if(response.accounts[i].subtype === "credit card"){
                  $("#cc_selection").append('<a href="" id_sel="'+response.accounts[i].account_id+'">'+response.accounts[i].official_name+'</a>')
              }
          }
          console.log(response);
          //updateTransactionDisplay(response.transactions);
      }, "json");
  });



})(jQuery);