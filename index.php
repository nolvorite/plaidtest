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

            <table class="table table-striped">
                <thead><tr><th title="Field #1">Type</th>
                <th title="Field #2">Trans Date</th>
                <th title="Field #3">Post Date</th>
                <th title="Field #4">Description</th>
                <th title="Field #5">Amount</th>
                </tr></thead>
                <tbody><tr>
                <td>SALE</td>
                <td>12/3/2013</td>
                <td>12/3/2013</td>
                <td>Amazon Digital Svcs</td>
                <td align="right">-7.99</td>
                </tr>
                <tr>
                <td>SALE</td>
                <td>12/13/2013</td>
                <td>12/15/2013</td>
                <td>GOOGLE *ABUKAI</td>
                <td align="right">-99.99</td>
                </tr>
                <tr>
                <td>SALE</td>
                <td>12/19/2013</td>
                <td>12/20/2013</td>
                <td>Amazon Digital Svcs</td>
                <td align="right">-9.49</td>
                </tr>
                <tr>
                <td>SALE</td>
                <td>12/27/2013</td>
                <td>12/27/2013</td>
                <td>D J*WALL-ST-JOURNAL</td>
                <td align="right">-22.99</td>
                </tr>
                <tr>
                <td>SALE</td>
                <td>12/18/2013</td>
                <td>12/19/2013</td>
                <td>DB BAHN  A-NR TS9KUQ</td>
                <td align="right">-71.48</td>
                </tr>
                <tr>
                <td>RETURN</td>
                <td>12/14/2013</td>
                <td>12/15/2013</td>
                <td>GOOGLE *ABUKAI</td>
                <td align="right">99.99</td>
                </tr>
                </tbody>
            </table>
        </div>

    </div></div>
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
