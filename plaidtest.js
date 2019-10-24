(function($) {

  
  
  var modalMode = "none";
  const modalSettings = {
      default_footer: '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'
  };

  /*<tr><th title="Field #1">Type</th>
                <th title="Field #2">Trans Date</th>
                <th title="Field #3">Post Date</th>
                <th title="Field #4">Description</th>
                <th title="Field #5">Amount</th>
                </tr>*/

  

  function updateTransactionDisplay(data){
      $("#tables").prepend(data.html);
  }

  function openModal(mode){
      modalMode = mode;
      $('#modal_def').modal({
          backdrop: 'static',
          keyboard: false
      });
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
          
      }
      );
  }

  function loadCardData(officialName){


      $.post("fetch.php?type=transactions&official_name="+officialName,{
          publicToken: publicToken,
          metadata: metaData
      },function(response){
          console.log(response);
          try {
              response = $.parseJSON(response);
          } catch (error){
              console.log("Loading failed. Trying again...");
              loadCardData(officialName);
          }
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

      if($("#bank_info").length === 0){
          $("#link-button").after('<span id="bank_info" class="alert alert-success" />');
      }

      $("#bank_info").html("Currently logged into your "+metadata.institution.name+" account.");

      metaData = metadata;

      $.post("fetch.php?type=session_log",{institution: metadata.institution, public_token: public_token,metadata: metaData},function(alert){
          console.log("Logged in bank session.")
      });

      publicToken = public_token;

      
      

      if($("#link-button").is("[started]")){

      }else{
          $("#menu").removeClass("d-none");
          
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

  //delegate all modal events that trigger based on modal mode here

  $(document).ready(function(){

      //everything to update once the page is finished loading

      $.post("fetch.php?type=update_user",{filler: 'true'},
          function(msg){
              console.log(msg);
          }
      );

      if(publicToken !== ""){
          $("#menu").removeClass("d-none");
      }

  });

  $('#modal_def').on('hide.bs.modal', function (e) {
      switch(modalMode){
          case "Delete Card Feed":
          case "Manage Card Feed":
              $("#modal_def .modal_footer").html(modalSettings.default_footer);
          break;
      }
  });

  $('#modal_def').on('show.bs.modal', function (e) {
      switch(modalMode){
          case "Import Card Feed":
              $("#modal_title").html("Loading...");
              loadCards();
          break;
          case "Delete Card Feed":
              $("#modal_text").html("Are you sure you want to do this? If so, click the \"Confirm\" button below. ");
              $("#modal_title").html("Delete Card Feed");
              $("#modal_def .modal-footer").html("<button c_id='"+cardId+" 'class='btn btn-danger confirm-deletion'>Confirm</button>")
          break;
          case "Manage Nickname":
              nickname = $(".table-container").find(".toggle-btn").text().replace(/^[\t ]{1,}(.+)/gm,"$1");
              $("#modal_text").html("<input class='form-control' id='nicknamee' placeholder='Input nickname here...' value='"+nickname+"'>");
              $("#modal_def .modal-footer").append("<button class='btn btn-primary confirm-manage-nickname' c_id='"+cardId+"'>Edit Nickname</button>");
              $("#modal_title").html("Managing Nickname");
          break;
      }
  });

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
      openModal("Import Card Feed");
  });

  $("body").on('click','.nickname-btn',function(event){
      cardId = $(this).parents(".table-container").attr("c_id");
      openModal("Manage Nickname");
  });

  $("body").on('click','.delete-card-feed-btn',function(event){
      cardId = $(this).parents(".table-container").attr("c_id");
      openModal("Delete Card Feed");
  });

  $("body").on("click",".confirm-manage-nickname,.confirm-deletion",function(event){
      card_id = $(this).attr("c_id");
      type = $(this).is(".confirm-manage-nickname") ? "confirm_nickname" : "confirm_deletion";
      data = {card_id: card_id};
      if($(this).is(".confirm-manage-nickname")){
          data.nickname = $("#nicknamee").val();
      }
      $.post("fetch.php?type="+type,data,function(response){
          console.log(response);
          response = $.parseJSON(response);
          console.log(response);
          if(typeof response.error === "undefined"){
              switch(type){
                  case "confirm_nickname":
                      $(".table-container[c_id="+response.card_id+"] .toggle-btn").html(response.nickname);
                      alert(response.notice);
                      setTimeout(function(){
                          $("#modal_def").modal('toggle');
                      },600);
                  break;
                  case "confirm_deletion":
                      alert(response.notice);
                      $("#modal_def").modal('toggle');
                      $(".table-container[c_id="+response.card_id+"]").fadeOut(700);
                  break;
              }
          }else{
              alert("Error doing action. Error: " +response.error);
          }
          
      });
      
  });



})(jQuery);