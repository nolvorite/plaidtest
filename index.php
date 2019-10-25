<?php
    ob_start();
    session_start();

    require("db.php");


    $cardDQ = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id = ".$_SESSION['userdata']['user_id']."");
    $cardData = mysqli_fetch_all($cardDQ, MYSQLI_ASSOC);
    $cards = [];
    $needsUpdates = false;

    //search all cards, find their date, convert to unix timestamp

    foreach($cardData as $cardz){
        $cards[$cardz['card_id']] = $cardz;
        $lastUpdated = strtotime($cardz['last_updated']);
        $needsUpdates = (microtime(true) - $lastUpdated >= (60 * 60 * 24)) ? true : $needsUpdates;
    }

    if($needsUpdates){ 
        //header("Location: update_cards.php");
    }else {

    ///////////

    $cardDataHTML = "";

    foreach($cards as $key => $card){

        $transactionsQ = mysqli_query($dbCon, "SELECT * FROM transactions WHERE card_id = $card[card_id] ORDER BY date_transacted DESC");
        $transactions = mysqli_fetch_all($transactionsQ,MYSQLI_ASSOC);
        $cards[$key]['transactions'] = $transactions;

        $cardDataHTML .= tableAppend($cards[$key],$card['official_name']);

    }


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style type="text/css">
        .table-canvas{max-height:350px;overflow-y:auto;}
        #bank_info{    padding: .5rem;    vertical-align: middle;    margin-left: 10px;}
    </style>

    <title>Plaid Test</title>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="index.php">Abukai CC</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <!-- <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li> 
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Dropdown
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li> 
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Disabled</a>
          </li>
          -->
          
        </ul>
        <!-- <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form> -->
      </div>
    </nav>

    <div class="container"><div class="row">

        <div class="col-sm-12">

            <div id="bank_panel">
                <button id="link-button" class="btn btn-primary">Select a bank...</button>
                <?php if(isset($_SESSION['institution'])): ?><span id="bank_info" class="alert alert-success">Currently logged into your <?php echo $_SESSION['institution']['name']; ?> account.</span><?php endif; ?>
            </div>

            <div id="menu" class="d-none">
                <button id="import-card-feed" class="btn btn-success">Import Card Feed</button>
            </div>

            <div id="tables">
              <?php echo $cardDataHTML; ?>
            </div>

            
        </div>

    </div></div>

    <div class="modal" id="modal_def" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modal_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="modal_text">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <div>
      <?php var_dump($_SESSION); ?>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    <script type="text/javascript">
        var publicToken = "<?php if(isset($_SESSION['public_token'])){ echo $_SESSION['public_token']; }?>";
        <?php if(isset($_SESSION['metadata'])){ ?>
        var metaData = $.parseJSON('<?php echo json_encode($_SESSION['metadata']); ?>');
        <?php } else { ?>
        var metaData = {};
        <?php } ?>
        console.log(metaData);
    </script>
    <script type="text/javascript" src="plaidtest.js">
    </script>

  </body>
</html>
<?php } ?>