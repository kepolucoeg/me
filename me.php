 <?php
require __DIR__ . '/vendor/autoload.php';
use \LINE\LINEBot\SignatureValidator as SignatureValidator;
// load config
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
// initiate app
$configs =  [
	'settings' => ['displayErrorDetails' => true],
];
$app = new Slim\App($configs);
/* ROUTES */
$app->get('/', function ($request, $response) {
	return "Lanjutkan!";
});
$app->post('/', function ($request, $response)
{
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];
	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);
	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}
	// is this request comes from LINE?
	if($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, $_ENV['648255d1496f65618e51216e72bd641e'], $signature)){
		return $response->withStatus(400, 'Invalid signature');
	}
	// init bot
	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['JSXOCrV1VdnmumUo8UzQogQ49ecXbarYlrzJsjNx9mtbGe8DI/RpRD6SnDdxcz31T5FomVTTVmZXcPnX8sMAlzKuSBYYWg/zYAoHKrzQJIk3+F+VVP0w5zQS7K90G7kiqG/zexnVxcgUgCbmmerShwdB04t89/1O/w1cDnyilFU=']);
	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['648255d1496f65618e51216e72bd641e']]);
	$data = json_decode($body, true);
	foreach ($data['events'] as $event)
	{
		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == 'halo')
		{
			$message = "Halo juga";
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
	}
	foreach ($data['events'] as $event)
	{
		$userMessage = $event['message']['text'];
		if(strtolower($userMessage) == '01')
		{
			$message = "Kingdomm";
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
			$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
			return $result->getHTTPStatus() . ' ' . $result->getRawBody();
		
		}
	}
});
// $app->get('/push/{to}/{message}', function ($request, $response, $args)
// {
// 	$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV['JSXOCrV1VdnmumUo8UzQogQ49ecXbarYlrzJsjNx9mtbGe8DI/RpRD6SnDdxcz31T5FomVTTVmZXcPnX8sMAlzKuSBYYWg/zYAoHKrzQJIk3+F+VVP0w5zQS7K90G7kiqG/zexnVxcgUgCbmmerShwdB04t89/1O/w1cDnyilFU=']);
// 	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV['648255d1496f65618e51216e72bd641e']]);
// 	$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($args['message']);
// 	$result = $bot->pushMessage($args['to'], $textMessageBuilder);
// 	return $result->getHTTPStatus() . ' ' . $result->getRawBody();
// });
/* JUST RUN IT */
$app->run();
