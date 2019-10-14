(function($) {

  var publicToken = "";

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

  $('#link-button').on('click', function(e) {
    handler.open();
  });

  $('#import-card-feed').on('click',function(e){
      $.post("fetch.php?type=transactions",{
          publicToken: publicToken
      },function(response){
          console.log(response);
      });
  });



})(jQuery);