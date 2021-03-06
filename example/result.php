<?php
use Omnipay\Omnipay;

require __DIR__.'/../vendor/autoload.php';

$server = "http://$_SERVER[HTTP_HOST]";

//These parameters are just examples and copied from https://hyperpay.docs.oppwa.com/tutorials/integration-guide
//You should use your own credentials
$accessToken = 'OGE4Mjk0MTc0ZDA1OTViYjAxNGQwNWQ4MjllNzAxZDF8OVRuSlBjMm45aA==';
$entityId = '8a8294174d0595bb014d05d82e5b01d2';

$error = false;
$errorMessage = '';
$response = null;

if ($_GET['resourcePath']) {
    //Setup payment gateway
    $gateway = Omnipay::create('HyperPay_COPYandPAY');

    $gateway->initialize(array(
        'accessToken'  => $accessToken,
        'entityId'  => $entityId,
        'testMode'  => true
    ));

    try {
        $transaction = $gateway->completePurchase(array(
            'resourcePath'      => $_GET['resourcePath']
        ));
    
        $response = $transaction->send();
    } catch (\Exception $e) {
        $errorMessage = 'Exception caught while attempting authorize.<br>';
        $errorMessage .= 'Exception type == ' . get_class($e) . '<br>';
        $errorMessage .= 'Message == ' . $e->getMessage();
    }
} else {
    $error = true;
    $errorMessage = 'Missing Parameters';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.3/examples/starter-template/">

    <title>Test HyperPay</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" 
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" 
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <style>
        body {
            padding-top: 50px;
        }
        .starter-template {
            padding: 40px 15px;
            text-align: center;
        }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">        
        <div class="navbar-header">
          <button type="button" 
            class="navbar-toggle collapsed" data-toggle="collapse" 
            data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">HyperPay</a>
        </div>
      </div>
    </nav>

    <div class="container">        
        <div class="starter-template">
            <h1>Testing HyperPay Integration</h1>
            <p class="lead">Use this example as a way to quickly start integration with HyperPay.
            <br> This example is using Omnipay Framework.</p>
        </div>
        <ol class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li class="active">Complete Purchase</li>
        </ol>
<?php if ($error) {?>
        <div class="alert alert-danger"><?php echo $errorMessage;?></div>
<?php }?>
<?php if (!empty($response)) {?>
    <?php if ($response->isSuccessful()) {?>
        <div class="alert alert-info">Status: <?php echo $response->getCode();?></div>
        <div class="alert alert-info">Code: <?php echo $response->getResultCode();?></div>        
        <div class="alert alert-info">Message: <?php echo $response->getMessage();?></div>
        <div class="alert alert-info">Card:<br><?php print_r($response->getCard());?></div>
        <div class="alert alert-info">Data:<br><?php print_r($response->getData());?></div>
    <?php } else {?>
        <div class="alert alert-danger">Status: <?php echo $response->getCode();?></div>
        <div class="alert alert-danger">Message: <?php echo $response->getMessage();?></div>
        <div class="alert alert-danger">Data:<br><?php print_r($response->getData());?></div>
        <?php if (is_array($response->getErrors())) {?>
        <div class="alert alert-danger">Errors:</div>
        <table class="table">
        <thead>
            <th>Name</th>
            <th>Value</th>
            <th>Message</th>
        </thead>
        <tbody>
            <?php foreach ($response->getErrors() as $e) {?>
                <tr>
                <td><?php echo $e['name'];?></td>
                <td><?php echo $e['value'];?></td>
                <td><?php echo $e['message'];?></td>
                </tr>
            <?php }?>
        </tbody>
        </table>
        <?php } ?>
    <?php }?>
<?php } else {?>
        <div class="alert alert-danger">Error: Empty Response</div>
<?php }?>
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" 
        crossorigin="anonymous"></script>
  </body>
</html>