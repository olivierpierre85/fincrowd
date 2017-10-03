<?php
echo 'test';
$result =  request_signature_on_a_document(
'arthurvirus@hotmail.com',  // signer's email
'Grand slam',   // signer's name -- first name last name
'convention',    // the "human" name for the document
'convention_de_pret_modele_v2.pdf' // including directory information
);

echo 'flute!';

function request_signature_on_a_document(
	$recipientEmail,  // signer's email
	$recipientName,   // signer's name -- first name last name
    $documentName,    // the "human" name for the document
    $documentFileName // including directory information
	) {
	// RETURNS
	// Associative array with elements:
	//	ok -- true for success
	//  errMsg -- only if !ok
	//  The following are valid only if ok:
	//  envelopeId
	//	accountId
	//	baseUrl

  $url = "https://demo.docusign.net/restapi/v2/login_information"; // change for production

  $email = 'olivierpierre85@gmail.com';	// your account email.
  $password = 'ngi54JL&';		// your account password //not great
  $integratorKey = '5bf42f5f-d4f9-4b62-9c7e-57b2a1775a92'; // your account integrator key, found on (Preferences -> API page)
  //$templateId = '32503515-4bab-482d-95d3-dd0a87f8862e';
  $templateId = '0da1dcd6-4259-4d72-a9f0-e8adb0022a28';
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
		return (['ok' => false, 'errMsg' => "Error calling DocuSign, status is: " . $status]);
	}
	$response = json_decode($json_response, true);
	$accountId = $response["loginAccounts"][0]["accountId"];
	$baseUrl = $response["loginAccounts"][0]["baseUrl"];
	curl_close($curl);
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// STEP 2 - Create and send envelope with one recipient, one tab, and one document
	/////////////////////////////////////////////////////////////////////////////////////////////////
	// the following envelope request body will place 1 signature tab on the document, located
	// 100 pixels to the right and 100 pixels down from the top left of the document
	$data =
		array (
			"emailSubject" => "DocuSign API - Please sign " . $documentName,
			"documents" => array(
				//array("documentId" => "1", "name" => $documentName, "filePath" => $documentFileName),
        //array("documentId" => "2", "name" => $documentName, "filePath" => $documentFileName),
          array(
            "documentBase64"=> base64_encode(file_get_contents($documentFileName)),
            "documentId"=> "1",
            "fileExtension"=> "pdf",
            "name"=> "test.pdf"
          ),
          array(
            "documentBase64"=> base64_encode(file_get_contents($documentFileName)),
            "documentId"=> "2",
            "fileExtension"=> "pdf",
            "name"=> "test2.pdf"
          )
				),
			"recipients" => array(
				"signers" => array(
					array(
						"email" => 'arthurvirus@hotmail.com',
						"name" => 'Borrower t',
						"recipientId" => "1",
						"tabs" => array(
							"signHereTabs" => array(
								array(
									"xPosition" => "100",
									"yPosition" => "100",
									"documentId" => "1",
									"pageNumber" => "1"
								)
							)
						)
					)
          ,array(
						"email" => 'olivierpierre85@gmail.com',
						"name" => 'User 2',
						"recipientId" => "2",
						//"excludedDocuments" => ['1'],
						"tabs" => array(
							"signHereTabs" => array(
								array(
									"xPosition" => "200",
									"yPosition" => "200",
									"documentId" => "2",
									"pageNumber" => "1"
								)
							)
						)
					)
				)
			),
		"status" => "sent",
		"enforceSignerVisibility" =>  true,
	);
	$data_string = json_encode($data);
	$file_contents = file_get_contents($documentFileName);
	// Create a multi-part request. First the form data, then the file content
  $requestBody =
  	 "\r\n"
  	."\r\n"
  	."--myboundary\r\n"
  	."Content-Type: application/json\r\n"
  	."Content-Disposition: form-data\r\n"
  	."\r\n"
  	."$data_string\r\n"
  	."--myboundary\r\n"
  	."Content-Type:application/pdf\r\n"
  	."Content-Disposition: file; filename=\"$documentName\"; documentid=1 \r\n"
  	."\r\n"
  	."$file_contents\r\n"
  	."--myboundary--\r\n"
  	."\r\n";
	// Send to the /envelopes end point, which is relative to the baseUrl received above.
	$curl = curl_init($baseUrl . "/envelopes" );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: multipart/form-data;boundary=myboundary',
		'Content-Length: ' . strlen($requestBody),
		"X-DocuSign-Authentication: $header" )
	);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//TODO not so good
	$json_response = curl_exec($curl); // Do it!
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ( $status != 201 ) {
		echo "Error calling DocuSign, status is:" . $status . "\nerror text: ";
		print_r($json_response); echo "\n";
		exit(-1);
	}
	$response = json_decode($json_response, true);
	$envelopeId = $response["envelopeId"];

	return [
	            'ok' => true,
	    'envelopeId' => $envelopeId,
	     'accountId' => $accountId,
	       'baseUrl' => $baseUrl
    ];

} // end of function request_signature_on_a_document
