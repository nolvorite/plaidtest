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
      clone = $("#table_copy").clone();
      clone.removeAttr("id");
      clone.removeClass("d-none");

      for(i = 0; i < data.transactions.length; i++){
          typee = data.transactions[i].amount < 0 ? "SALE" : "RETURN";
          datee = data.transactions[i].date;
          datee2 = data.transactions[i].date;
          name = data.transactions[i].name;
          amount = data.transactions[i].amount;
          clone.find(".transaction").append('<tr>\
            <td>'+typee+'</td>\
            <td>'+datee+'</td>\
            <td>'+datee2+'</td>\
            <td>'+name+'</td>\
            <td>'+amount+'</td>\
            </tr>');
      }
      clone.find(".btn").html(data.official_name);
      clone.appendTo("#tables");
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
              }
          }
          console.log(response);
          $("#link-button").html("Add another card").attr("started","started");
      }
      );
  }

  function loadCardData(officialName){


      $.post("fetch.php?type=transactions&official_name="+officialName,{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response,response.total_transactions,response.transaction_truecount);
          if((response.total_transactions *.8) <= response.transaction_truecount && response.total_transactions > 0){
              updateTransactionDisplay(response);   
              $('#modal_def').modal('toggle');
          }else{
              console.log("reload");
              loadCardData(officialName);
          }
      },"json"
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

      publicToken = public_token;

      metaData = metadata;

      console.log("Logged In Successfully.");

      

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

  $('#import-card-feed').on('click',function(e){
      $('#modal_def').modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#modal_title").html("Loading...");
      loadCards();
  });



})(jQuery);