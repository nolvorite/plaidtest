<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style type="text/css">
        #menu{display:none;}
    </style>

    <title>Plaid Test</title>
  </head>
  <body>

    <div class="container"><div class="row">

        <div class="col-sm-12">

            <div id="guest">
                <button id="link-button" class="btn btn-primary">Login With Plaid</button>
            </div>

            <div id="menu">
                <button id="import-card-feed" class="btn btn-success">Import Card Feed</button>
            </div>

            <table class="table table-striped" id="transactions">
                <thead><tr><th title="Field #1">Type</th>
                <th title="Field #2">Trans Date</th>
                <th title="Field #3">Post Date</th>
                <th title="Field #4">Description</th>
                <th title="Field #5">Amount</th>
                </tr></thead>
                <tbody>

                </tbody>
            </table>
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
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>
    <script type="text/javascript" src="plaidtest.js">
    </script>

  </body>
</html>
