<?php

	echo 'Test d\'envoi documents';


$result = test_template();
var_dump($result);

function test_template(){
  $email = 'olivierpierre85@gmail.com';	// your account email.
  $password = 'ngi54JL&';		// your account password
  $integratorKey = '5bf42f5f-d4f9-4b62-9c7e-57b2a1775a92'; // your account integrator key, found on (Preferences -> API page)
  $templateId = '32503515-4bab-482d-95d3-dd0a87f8862e';
  $templateRoleName = 'lender';

  $recipientName = 'Olivier le roi';
  $emailRecipient = 'arthurvirus@hotmail.com';

  // api service point
  $url = "https://demo.docusign.net/restapi/v2/login_information"; // change for production
 //$url = "httpsertrterte://www.google.com";
  ///////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////
  //
  // Start...

  // construct the authentication header:
  $header = "<DocuSignCredentials><Username>" . $email . "</Username><Password>" . $password . "</Password><IntegratorKey>" . $integratorKey . "</IntegratorKey></DocuSignCredentials>";

  /////////////////////////////////////////////////////////////////////////////////////////////////
  // STEP 1 - Login (to retrieve baseUrl and accountId)
  /////////////////////////////////////////////////////////////////////////////////////////////////
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//TODO not so good

  $json_response = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  if ( $status != 200 ) {
    //return (['ok' => false, 'errMsg' => "Error calling DocuSign, status is: " . $status]);
    return curl_error($curl);
  }

  $response = json_decode($json_response, true);
  $accountId = $response["loginAccounts"][0]["accountId"];
  $baseUrl = $response["loginAccounts"][0]["baseUrl"];
  curl_close($curl);

  /////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - Create and send
	/////////////////////////////////////////////////////////////////////////////////////////////////
  $data = array("accountId" => $accountId,
  	"emailSubject" => "DocuSign API - Signature Request from Template",
  	"templateId" => $templateId,
  	"templateRoles" => array(
  			array(
        "email" => $emailRecipient,
        "name" => $recipientName,
        "roleName" => $templateRoleName,
        "tabs" => array(
            "textTabs" => array(
                array(
                    "tabLabel"=> "testtest",
                    "value" => "WOOOOOW"
                )
            )
        )
       )),
  	"status" => "sent");

  $data_string = json_encode($data);
  $curl = curl_init($baseUrl . "/envelopes" );
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  	'Content-Type: application/json',
  	'Content-Length: ' . strlen($data_string),
  	"X-DocuSign-Authentication: $header" )
  );

  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//TODO not so good

  $json_response = curl_exec($curl);
  $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  if ( $status != 201 ) {

  	//echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
  	//print_r($json_response); echo "\n";
    return($status.'_'.$json_response);
  }

  $response = json_decode($json_response, true);
  $envelopeId = $response["envelopeId"];

  return($response);
}
